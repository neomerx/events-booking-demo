<?php declare(strict_types=1);

namespace App\Services;

use App\EventSection;
use App\Http\Resources\EventSectionResource;
use App\Rules\ActiveAndNonReservedEventSection;
use App\Services\Contracts\EventSectionsServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use PDO;

/**
 * NOTE: The query relies on 'company name' column value as sign if section is reserved.
 * If it is empty then we work with the section as a free one and reserved otherwise.
 *
 * @package App\ApiServices
 */
class EventSectionsService implements EventSectionsServiceInterface
{
    /** @var int Predefined size for logos */
    private const LOGO_WIDTH = 100;

    /** @var int Predefined size for logos */
    private const LOGO_HEIGHT = 100;

    /** @var string Image format to be used for logos */
    private const LOGO_IMAGE_FORMAT = 'png';

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * @param PDO          $pdo
     * @param ImageManager $imageManager
     */
    public function __construct(PDO $pdo, ImageManager $imageManager)
    {
        $this->pdo          = $pdo;
        $this->imageManager = $imageManager;
    }

    /**
     * @inheritdoc
     */
    public function getSections(int $eventId): Collection
    {
        // NOTE: it hides price for reserved sections.
        $sql = <<<EOT
SELECT
  es.event_section_id,
  IF (NULLIF(es.company_name,'') IS NULL, es.price, 0) as price,
  ea.name,
  es.company_name,
  es.company_description,
  es.company_logo_base64,
  es.contact_name,
  es.contact_email,
  es.contact_phone,
  ea.map_shape,
  ea.map_coordinates,
  ea.map_show_logo_at
FROM event_sections es
  INNER JOIN location_sections ea on es.location_section_id = ea.location_section_id
WHERE es.event_id = $eventId;
EOT;

        $statement = $this->getPDO()->query($sql);

        $result = new Collection();
        while (($object = $statement->fetch(PDO::FETCH_OBJ)) !== false) {
            $result->add(new EventSectionResource($object));
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function reserveSection(int $sectionId, array $inputs): void
    {
        $inputs['event_section_id'] = $sectionId;
        $validatedData              = Validator::make($inputs, [
            'event_section_id'    => ['required', new ActiveAndNonReservedEventSection()],
            'company_name'        => ['required', 'string', 'max:100'],
            'company_description' => [ 'optional', 'nullable', 'string', 'max:500'],
            'company_logo'        => ['required', 'image'],
            'contact_name'        => ['required', 'string', 'max:50'],
            'contact_phone'       => ['required', 'string', 'max:15'],
            'contact_email'       => ['required', 'string', 'max:255', 'email'],
        ])->validate();
        unset($validatedData['event_section_id']);
        unset($validatedData['company_logo']);

        // replace uploaded file with base64 representation
        /** @var UploadedFile $companyLogo */
        $companyLogo = $inputs['company_logo'];
        $validatedData['company_logo_base64'] =
            'data:image/png;base64,' . $this->convertImageFileToLogoBase64($companyLogo);

        /** @noinspection PhpUndefinedMethodInspection */
        EventSection::where('event_section_id', '=', $sectionId)->update($validatedData);
    }

    /**
     * @param UploadedFile $image
     *
     * @return string
     */
    private function convertImageFileToLogoBase64(UploadedFile $image): string
    {
        $image = $this->getImageManager()->make($image);

        $originalRatio = ((float)$image->getWidth()) / $image->getHeight();
        $newRatio      = ((float)self::LOGO_WIDTH) / self::LOGO_HEIGHT;

        if ($originalRatio > $newRatio) {
            $image->widen(self::LOGO_WIDTH);
        } else {
            $image->heighten(self::LOGO_HEIGHT);
        }

        $encodedImage = $image->encode(self::LOGO_IMAGE_FORMAT)->getEncoded();
        $imageBase64  = base64_encode($encodedImage);

        return $imageBase64;
    }

    /**
     * @return PDO
     */
    private function getPDO(): PDO
    {
        return $this->pdo;
    }

    /**
     * @return ImageManager
     */
    private function getImageManager(): ImageManager
    {
        return $this->imageManager;
    }
}

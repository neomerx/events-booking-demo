<?php declare(strict_types=1);

namespace App\Api;

use App\Api\Interfaces\EventSectionsServiceInterface;
use App\EventSection;
use App\Http\Resources\EventSectionResource;
use App\Rules\ActiveAndNonReservedEventSection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use PDO;

/**
 * NOTE: The query relies on 'company name' column value as sign if section is reserved.
 * If it is empty then we work with the section as a free one and reserved otherwise.
 *
 * @package App\ApiServices
 */
class EventSectionsService implements EventSectionsServiceInterface
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
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
        // TODO figure out requirements for input company logo (size?, format?) and add custom validator.

        $inputs['event_section_id'] = $sectionId;
        $validatedData              = Validator::make($inputs, [
            'event_section_id'    => ['required', new ActiveAndNonReservedEventSection()],
            'company_name'        => ['required', 'string', 'max:100'],
            'company_description' => ['string', 'max:500'],
            'company_logo_base64' => ['required', 'string'],
            'contact_name'        => ['required', 'string', 'max:50'],
            'contact_phone'       => ['required', 'string', 'max:15'],
            'contact_email'       => ['required', 'string', 'max:255', 'email'],
        ])->validate();
        unset($validatedData['event_section_id']);

        /** @noinspection PhpUndefinedMethodInspection */
        EventSection::where('event_section_id', '=', $sectionId)->update($validatedData);
    }

    /**
     * @return PDO
     */
    private function getPDO(): PDO
    {
        return $this->pdo;
    }
}

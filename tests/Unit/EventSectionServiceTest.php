<?php

namespace Tests\Unit;

use App\Event;
use App\EventSection;
use App\Services\Contracts\EventSectionsServiceInterface;
use App\Services\EventSectionsService;
use Illuminate\Database\Connection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManager;
use Tests\TestCase;

class EventSectionServiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test get event sections for a given event marker.
     *
     * @return void
     */
    public function testGetEventSections(): void
    {
        // first existing event
        /** @noinspection PhpUndefinedMethodInspection */
        $firstEventId = Event::firstOrFail()->event_id;

        $service = $this->createService();

        $sections = $this->iteratorToArray($service->getSections($firstEventId));

        $this->assertTrue(count($sections) > 0);

        foreach ($sections as $section) {
            $this->assertNotEmpty($section->price);
            $this->assertNotEmpty($section->name);
            $this->assertNotEmpty($section->map_shape);
        }
    }

    /**
     * Test reserving section.
     *
     * @return void
     */
    public function testReserveSection(): void
    {
        // find first non-reserved event section
        $nonReservedSectionId = $this->findFirstNonReservedEventSection()->event_section_id;

        $mockFilePath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'Data', 'logo_sample.png']);
        $this->assertTrue(file_exists($mockFilePath));
        $mockFile = UploadedFile::fake()->createWithContent('logo_sample.png', file_get_contents($mockFilePath));

        $companyName = 'Company Name';
        $inputs      = [
            'company_name'  => $companyName,
            'contact_name'  => 'John Doe',
            'contact_phone' => '+11234567890',
            'contact_email' => 'boo@mail.foo',
            'company_logo'  => $mockFile,
        ];

        $service = $this->createService();

        $service->reserveSection($nonReservedSectionId, $inputs);

        // re-read and check it was saved
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var EventSection $updatedSection */
        $updatedSection = EventSection::where('event_section_id', '=', $nonReservedSectionId)->firstOrFail();
        $this->assertEquals($companyName, $updatedSection->company_name);

        // now let's try to reserve second time (should fail)
        /** @noinspection PhpParamsInspection */
        $this->expectException(ValidationException::class);
        $service->reserveSection($nonReservedSectionId, $inputs);
    }

    /**
     * @return EventSection
     */
    private function findFirstNonReservedEventSection(): EventSection
    {
        // first existing event
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var Event $event */
        $event = Event::active()->firstOrFail();

        $found = null;
        foreach ($event->eventSections as $section) {
            if (empty($section->company_name) === true) {
                $found = $section;
                break;
            }
        }

        $this->assertNotNull($found);

        return $found;
    }

    /**
     * @return EventSectionsServiceInterface
     */
    private function createService(): EventSectionsServiceInterface
    {
        /** @var Connection $connection */
        $connection = app()->get(Connection::class);
        $pdo        = $connection->getPdo();

        return new EventSectionsService($pdo, new ImageManager());
    }

    /**
     * @param iterable $items
     *
     * @return array
     */
    private function iteratorToArray(iterable $items): array
    {
        $result = [];
        foreach ($items as $item) {
            $result[] = $item;
        }

        return $result;
    }
}

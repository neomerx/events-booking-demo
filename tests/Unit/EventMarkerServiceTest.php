<?php

namespace Tests\Unit;

use App\Services\EventsService;
use App\Services\EventSectionsService;
use App\Services\Contracts\EventsServiceInterface;
use App\Event;
use Illuminate\Database\Connection;
use Intervention\Image\ImageManager;
use LocationsTableSeeder;
use Tests\TestCase;
use function count;

class EventMarkerServiceTest extends TestCase
{
    /**
     * Test get all active event markers.
     *
     * @return void
     */
    public function testGetAllActiveMarkers(): void
    {
        $service = $this->createService();

        $activeMarkers = $this->iteratorToArray($service->getMarkers());

        // During initial seeding we create one active event for every location
        // so number of active markers should match. Please correct the test if you change this behaviour.
        $expectedActiveMarkers = count(LocationsTableSeeder::LOCATIONS);

        /** @noinspection PhpParamsInspection */
        $this->assertCount($expectedActiveMarkers, $activeMarkers);

        foreach ($activeMarkers as $eventMarker) {
            $this->assertNotEmpty($eventMarker->name);
            $this->assertNotEmpty($eventMarker->address);
        }
    }

    /**
     * Test get all active event markers in area.
     *
     * @return void
     */
    public function testGetAllActiveMarkersInArea(): void
    {
        $service = $this->createService();

        $lat           = LocationsTableSeeder::FIRST_LAT;
        $long          = LocationsTableSeeder::FIRST_LONG;
        $radius        = 5;
        $activeMarkers = $this->iteratorToArray($service->getMarkersInArea($lat, $long, $radius));

        // During initial seeding we create data that will give exactly this number of markers in the
        // specified area. Please correct the test if you change this initial data.
        $expectedActiveMarkers = 5;

        /** @noinspection PhpParamsInspection */
        $this->assertCount($expectedActiveMarkers, $activeMarkers);

        foreach ($activeMarkers as $eventMarker) {
            $this->assertNotEmpty($eventMarker->name);
            $this->assertNotEmpty($eventMarker->address);
        }
    }

    /**
     * Test get extended event marker.
     *
     * @return void
     */
    public function testGetExtended(): void
    {
        // first existing event
        /** @noinspection PhpUndefinedMethodInspection */
        $firstEventId = Event::firstOrFail()->event_id;

        // now read it as extended
        $service = $this->createService();

        $markerExtended = $service->read($firstEventId);
        $this->assertNotNull($markerExtended);
        $this->assertNotEmpty($markerExtended->name);
        $this->assertNotEmpty($markerExtended->address);
        $this->assertNotEmpty($markerExtended->map_image_base64);
        $this->assertNotEmpty($markerExtended->eventSections);
    }

    /**
     * @return EventsServiceInterface
     */
    private function createService(): EventsServiceInterface
    {
        /** @var Connection $connection */
        $connection = $this->app->get(Connection::class);
        $pdo        = $connection->getPdo();

        return new EventsService(new EventSectionsService($pdo, new ImageManager()), $pdo);
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

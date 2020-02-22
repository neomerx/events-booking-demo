<?php

namespace Tests\Unit;

use App\Event;
use App\EventSection;
use App\Location;
use LocationsTableSeeder;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use function count;

class EventModelTest extends TestCase
{
    /**
     * Test relationship.
     *
     * @return void
     */
    public function testLocationRelationship(): void
    {
        /** @var Event $event */
        /** @noinspection PhpUndefinedMethodInspection */
        $event = Event::firstOrFail();

        $location = $event->location;
        $this->assertNotNull($location);
        $this->assertTrue($location instanceof Location);
    }

    /**
     * Test relationship.
     *
     * @return void
     */
    public function testEventSectionsRelationship(): void
    {
        /** @var Event $event */
        /** @noinspection PhpUndefinedMethodInspection */
        $event = Event::firstOrFail();

        $sections = $event->eventSections;
        $this->assertNotEmpty($sections);
        foreach ($sections as $section) {
            $this->assertTrue($section instanceof EventSection);
        }
    }

    /**
     * Test scope.
     *
     * @return void
     */
    public function testSelectActiveEvents(): void
    {
        /** @var Collection $events */
        /** @noinspection PhpUndefinedMethodInspection */
        $events = Event::active()->orderByStartDateAsc()->get();

        // during initial seeding we create one active event for every location
        // so number of active events should match. Please correct the test if you change this behaviour.
        $expectedActiveEvents = count(LocationsTableSeeder::LOCATIONS);

        $this->assertEquals($expectedActiveEvents, $events->count());
    }
}

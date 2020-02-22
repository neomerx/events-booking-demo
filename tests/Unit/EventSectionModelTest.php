<?php

namespace Tests\Unit;

use App\Event;
use App\EventSection;
use Tests\TestCase;

class EventSectionModelTest extends TestCase
{
    /**
     * Test relationship.
     *
     * @return void
     */
    public function testEventRelationship(): void
    {
        /** @var EventSection $section */
        /** @noinspection PhpUndefinedMethodInspection */
        $section = EventSection::firstOrFail();

        $event = $section->event;
        $this->assertNotNull($event);
        $this->assertTrue($event instanceof Event);
    }
}

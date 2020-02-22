<?php

namespace Tests\Unit;

use App\Location;
use Tests\TestCase;

class LocationModelTest extends TestCase
{
    /**
     * Test relationship.
     *
     * @return void
     */
    public function testAreasRelationship(): void
    {
        /** @var Location $location */
        /** @noinspection PhpUndefinedMethodInspection */
        $location = Location::firstOrFail();

        $sections = $location->locationSections;
        $this->assertGreaterThan(0, $sections->count());
    }
}

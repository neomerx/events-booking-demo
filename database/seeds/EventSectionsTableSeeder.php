<?php

use App\Event;
use App\EventSection;
use App\LocationSection;
use Illuminate\Database\Seeder;

final class EventSectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws Exception
     */
    public function run()
    {
        foreach (Event::all() as $event) {
            $eventId  = $event->event_id;
            $location = $event->location;
            foreach ($location->locationSections as $section) {
                assert($section instanceof LocationSection);
                $sectionId = $section->location_section_id;

                /** @noinspection PhpUndefinedMethodInspection */
                EventSection::create([
                    'event_id'            => $eventId,
                    'location_section_id' => $sectionId,
                    'price'               => 100 * rand(20, 50),
                ]);
            }
        }
    }
}

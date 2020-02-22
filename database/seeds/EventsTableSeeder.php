<?php

use App\Event;
use App\Location;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

final class EventsTableSeeder extends Seeder
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
        // let's create current events for all exhibitions and a couple of more to make sure the system
        // correctly works past and future events.

        $faker = Faker::create();

        foreach (Location::all() as $exhibition) {
            $locationId = $exhibition->location_id;

            // past event
            /** @noinspection PhpUndefinedMethodInspection */
            Event::create([
                'location_id' => $locationId,
                'is_active'   => false,
                'name'        => 'Event ' . implode(' ', $faker->words(3)),
                'date_from'   => $faker->dateTimeBetween('-30 days', '-25 days'),
                'date_to'     => $faker->dateTimeBetween('-20 days', '-15 days'),
            ]);

            // current event
            /** @noinspection PhpUndefinedMethodInspection */
            Event::create([
                'location_id' => $locationId,
                'is_active'   => false,
                'name'        => 'Event ' . implode(' ', $faker->words(3)),
                'date_from'   => $faker->dateTimeBetween('-10 days', '-1 day'),
                'date_to'     => $faker->dateTimeBetween('1 day', '5 days'),
            ]);

            // future event
            /** @noinspection PhpUndefinedMethodInspection */
            Event::create([
                'location_id' => $locationId,
                'is_active'   => true,
                'name'        => 'Event ' . implode(' ', $faker->words(3)),
                'date_from'   => $faker->dateTimeBetween('10 days', '15 days'),
                'date_to'     => $faker->dateTimeBetween('20 days', '25 days'),
            ]);
        }
    }
}

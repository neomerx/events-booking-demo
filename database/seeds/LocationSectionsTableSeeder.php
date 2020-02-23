<?php

use App\Location;
use App\LocationSection;
use Illuminate\Database\Seeder;

final class LocationSectionsTableSeeder extends Seeder
{
    // For testing purposes we use the same map image for each exhibition
    // As areas on the map we use rectangular, circle and poly (triangle) for demonstration purposes.
    const EXHIBITION_AREAS = [
        ['A1', 'rect', '15,10,280,190', '29,9'],
        ['A2', 'circle', '140,366,116', '29,60'],
        ['A3', 'poly', '382,16,650,16,382,480', '57,15'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws Exception
     */
    public function run()
    {
        foreach (Location::all() as $exhibition) {
            $locationId = $exhibition->location_id;
            foreach (self::EXHIBITION_AREAS as [$name, $shape, $coordinates, $showLogoAt]) {
                /** @noinspection PhpUndefinedMethodInspection */
                LocationSection::create([
                    'location_id'      => $locationId,
                    'name'             => $name,
                    'map_shape'        => $shape,
                    'map_coordinates'  => $coordinates,
                    'map_show_logo_at' => $showLogoAt,
                ]);
            }
        }
    }
}

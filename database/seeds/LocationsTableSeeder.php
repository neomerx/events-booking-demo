<?php

use App\Location;
use Illuminate\Database\Seeder;

final class LocationsTableSeeder extends Seeder
{
    public const FIRST_LAT = '-33.861034';
    public const FIRST_LONG = '151.171936';
    public const LOCATIONS = [
        ['Lincoln Tower Exhibition', '580 Darling Street, Rozelle, NSW', self::FIRST_LAT . ' ' . self::FIRST_LONG],
        ['Young Henrys Exhibition', '76 Wilford Street, Newtown, NSW', '-33.898113 151.174469'],
        ['Hunter Gatherer Exhibition', 'Greenwood Plaza, 36 Blue St, North Sydney NSW', '-33.840282 151.207474'],
        ['The Potting Shed Exhibition', '7A, 2 Huntley Street, Alexandria, NSW', '-33.910751 151.194168'],
        ['Nomad Exhibition', '16 Foster Street, Surry Hills, NSW', '-33.879917 151.210449'],
        ['Three Blue Ducks Exhibition', '43 Macpherson Street, Bronte, NSW', '-33.906357 151.263763'],
        ['Single Origin Roasters Exhibition', '60-64 Reservoir Street, Surry Hills, NSW', '-33.881123 151.209656'],
        ['Red Lantern Exhibition', '60 Riley Street, Darlinghurst, NSW', '-33.874737 151.215530'],
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
        $map = 'data:image/png;base64,' .
            base64_encode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'exhibition_map.png'));

        $now = (new DateTimeImmutable())->format('Y-m-d H:i:s');

        $connection = (new Location())->getConnection()->getPdo();

        $insertStatement = <<<EOT
INSERT INTO
  locations (name, address, location, map_image_base64, created_at)
VALUES (:name, :address, ST_GEOMFROMTEXT(:point), :file, :created_at)
EOT;

        $statement = $connection->prepare($insertStatement);

        foreach (self::LOCATIONS as [$name, $address, $location]) {
            $statement->bindValue(':name', $name);
            $statement->bindValue(':address', $address);
            $statement->bindValue(':point', "POINT($location)");
            $statement->bindValue(':file', $map);
            $statement->bindValue(':created_at', $now);

            $statement->execute();
        }
    }
}

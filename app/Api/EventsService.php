<?php declare(strict_types=1);

namespace App\Api;

use App\Api\Interfaces\EventSectionsServiceInterface;
use App\Api\Interfaces\EventsServiceInterface;
use App\Http\Resources\EventMarkerResource;
use App\Http\Resources\EventResource;
use Illuminate\Database\Eloquent\Collection;
use PDO;

class EventsService implements EventsServiceInterface
{
    /**
     * @var EventSectionsServiceInterface
     */
    private $sectionsService;

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @param EventSectionsServiceInterface $eventSectionsService
     * @param PDO                           $pdo
     */
    public function __construct(EventSectionsServiceInterface $eventSectionsService, PDO $pdo)
    {
        $this->sectionsService = $eventSectionsService;
        $this->pdo             = $pdo;
    }

    /**
     * @inheritdoc
     */
    public function getMarkers(): Collection
    {
        return $this->fetchMarkersWithSql($this->getSqlSelectActive() . $this->getOrderByFrom());
    }

    /**
     * @inheritdoc
     */
    public function getMarkersInArea(float $latitude, float $longitude, float $radiusInKm): Collection
    {
        // we reuse SQL for all active but add an extra condition to select only in specified area

        $extraCondition = <<<EOT

# 60 nautical miles (1 degree) is ~ 111.1201 km and multiplied by distance in degrees
AND 111.1201 * ST_Length(LineString(PointFromWKB(ex.location), POINT($latitude, $longitude))) <= $radiusInKm

EOT;

        return $this->fetchMarkersWithSql(
            $this->getSqlSelectActive() . $extraCondition . $this->getOrderByFrom()
        );
    }

    /**
     * @inheritdoc
     */
    public function read(int $eventId): ?EventResource
    {
        $sql = <<<EOT

SELECT
  ev.*,
  ex.address,
  x(ex.location) AS latitude,
  y(ex.location) AS longitude,
  ex.map_image_base64
FROM events ev
  INNER JOIN locations ex on ev.location_id = ex.location_id
WHERE ev.event_id = $eventId;

EOT;

        $event = $this->fetchEventWithSql($sql);

        if ($event !== null) {
            $event->eventSections = $this->getSectionService()->getSections($eventId);
        }

        return new EventResource($event);
    }

    /**
     * Fetch all event markers with SQL given.
     *
     * @param string $sql
     *
     * @return Collection|EventMarkerResource[]
     */
    private function fetchMarkersWithSql(string $sql): Collection
    {
        $statement = $this->getPDO()->query($sql);

        $result = new Collection();
        while (($object = $statement->fetch(PDO::FETCH_OBJ)) !== false) {
            $result->add(new EventMarkerResource($object));
        }

        return $result;
    }

    /**
     * Fetch event marker extended with SQL given.
     *
     * @param string $sql
     *
     * @return \object|null
     */
    private function fetchEventWithSql(string $sql): ?object
    {
        $objectOrFalse = $this->getPDO()->query($sql)->fetch(PDO::FETCH_OBJ);

        $result = $objectOrFalse === false ? null : $objectOrFalse;

        return $result;
    }

    /**
     * Get SQL for selecting active event markers.
     *
     * @return string
     */
    private function getSqlSelectActive(): string
    {
        $sql = <<<EOT

SELECT
  ev.*,
  ex.address,
  x(ex.location) AS latitude,
  y(ex.location) AS longitude
FROM events ev
  INNER JOIN locations ex on ev.location_id = ex.location_id
WHERE
  ev.is_active = TRUE

EOT;
        return $sql;
    }

    /**
     * @return string
     */
    private function getOrderByFrom(): string
    {
        $sql = <<<EOT

ORDER BY
  ev.date_from ASC

EOT;
        return $sql;
    }

    /**
     * @return PDO
     */
    private function getPDO(): PDO
    {
        return $this->pdo;
    }

    /**
     * @return EventSectionsServiceInterface
     */
    private function getSectionService(): EventSectionsServiceInterface
    {
        return $this->sectionsService;
    }
}

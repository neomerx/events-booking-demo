<?php declare(strict_types=1);

namespace App\Api\Interfaces;

use App\Http\Resources\EventMarkerResource;
use App\Http\Resources\EventResource;
use Illuminate\Database\Eloquent\Collection;

interface EventsServiceInterface
{
    /**
     * Return all active event markers.
     *
     * @return Collection|EventMarkerResource[]
     */
    public function getMarkers(): Collection;

    /**
     * Return all active event markers within a given circle.
     *
     * @param float $latitude   Latitude of the circle center.
     * @param float $longitude  Longitude of the circle center.
     * @param float $radiusInKm Approximate radius in km.
     *
     * @return Collection|EventMarkerResource[]
     */
    public function getMarkersInArea(float $latitude, float $longitude, float $radiusInKm): Collection;

    /**
     * Get event by its ID (or null if not found).
     *
     * @param int $eventId
     *
     * @return EventResource|null
     */
    public function read(int $eventId): ?EventResource;
}

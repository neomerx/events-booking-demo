<?php

namespace App\Http\Controllers;

use App\Api\Interfaces\EventsServiceInterface;
use App\Http\Resources\EventResource;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Resources\EventMarkerResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

final class EventsController extends Controller
{
    public const PARAM_LAT = 'lat';
    public const PARAM_LONG = 'long';
    public const PARAM_RANGE = 'range';

    /** @var EventsServiceInterface */
    private $eventService;

    /**
     * @param EventsServiceInterface $eventService
     */
    public function __construct(EventsServiceInterface $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * @param Request $request
     *
     * @return Collection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request): Collection
    {
        $this->authorize('view-all', EventMarkerResource::class);

        $lat   = $request->get(self::PARAM_LAT, null);
        $long  = $request->get(self::PARAM_LONG, null);
        $range = $request->get(self::PARAM_RANGE, null);
        if ($lat !== null && $long !== null && $range !== null) {
            $lat   = (float)$lat;
            $long  = (float)$long;
            $range = (float)$range;
            if ($range > 0) {
                return $this->getEventService()->getMarkersInArea($lat, $long, $range);
            }
        }

        return $this->getEventService()->getMarkers();
    }

    /**
     * @param int $eventMarkerId
     *
     * @return EventResource
     *
     * @throws AuthorizationException
     */
    public function read(int $eventMarkerId): EventResource
    {
        $event = $this->getEventService()->read($eventMarkerId);
        if ($event === null) {
            /** @noinspection PhpParamsInspection */
            throw new HttpException(404);
        }

        $this->authorize('view', $event);

        return $event;
    }

    /**
     * @return EventsServiceInterface
     */
    private function getEventService(): EventsServiceInterface
    {
        return $this->eventService;
    }
}

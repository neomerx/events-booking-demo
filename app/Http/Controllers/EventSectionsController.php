<?php

namespace App\Http\Controllers;

use App\Api\Interfaces\EventSectionsServiceInterface;
use App\Events\SectionReserved;
use App\Http\Resources\EventSectionResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class EventSectionsController extends Controller
{
    /** @var EventSectionsServiceInterface */
    private $eventSectionsService;

    /**
     * @param EventSectionsServiceInterface $eventSectionsService
     */
    public function __construct(EventSectionsServiceInterface $eventSectionsService)
    {
        $this->eventSectionsService = $eventSectionsService;
    }

    /**
     * @param int     $sectionId
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function reserve(int $sectionId, Request $request): JsonResponse
    {
        $this->authorize('reserve', [EventSectionResource::class, $sectionId, $request->input()]);

        $this->getEventSectionService()->reserveSection($sectionId, $request->input());

        event(new SectionReserved($sectionId));

        return new JsonResponse();
    }

    /**
     * @return EventSectionsServiceInterface
     */
    private function getEventSectionService(): EventSectionsServiceInterface
    {
        return $this->eventSectionsService;
    }
}

<?php declare(strict_types=1);

namespace App\Api\Interfaces;

use App\Http\Resources\EventSectionResource;
use Illuminate\Database\Eloquent\Collection;

interface EventSectionsServiceInterface
{
    /**
     * Get event sections.
     *
     * @param int $eventId
     *
     * @return Collection|EventSectionResource[]
     */
    public function getSections(int $eventId): Collection;

    /**
     * Reserve event section.
     *
     * @param int   $sectionId Section ID.
     * @param array $inputs    Company data (e.g. phone, email, etc)
     *
     * @return void
     */
    public function reserveSection(int $sectionId, array $inputs): void;
}

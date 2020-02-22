<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use function is_array;

class EventSectionPolicy
{
    use HandlesAuthorization;

    /**
     * If current user can reserve an event section.
     *
     * @param User|null $user
     * @param int       $sectionId
     * @param array     $changes
     *
     * @return bool
     */
    public function reserve(?User $user, int $sectionId, array $changes): bool
    {
        // to suppress unused warning
        assert($user !== null || $user === null);
        assert($sectionId > 0);
        assert(is_array($changes));

        // currently there are no limitations

        return true;
    }
}

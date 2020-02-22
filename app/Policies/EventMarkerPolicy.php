<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventMarkerPolicy
{
    use HandlesAuthorization;

    /**
     * If current user can view markers.
     *
     * @param User|null $user
     *
     * @return bool
     */
    public function viewAll(?User $user): bool
    {
        // to suppress unused warning
        assert($user !== null || $user === null);

        // currently there are no limitations

        return true;
    }
}

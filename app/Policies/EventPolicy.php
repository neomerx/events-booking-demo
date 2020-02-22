<?php

namespace App\Policies;

use App\Http\Resources\EventResource;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use function assert;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * If current user can view event.
     *
     * @param User|null     $user
     * @param EventResource $resource
     *
     * @return bool
     */
    public function view(?User $user, EventResource $resource): bool
    {
        // to suppress unused warning
        assert($user !== null || $user === null);
        assert($resource !== null);

        // currently there are no limitations

        return true;
    }
}

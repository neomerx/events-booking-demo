<?php

namespace App\Providers;

use App\Http\Resources\EventResource;
use App\Http\Resources\EventMarkerResource;
use App\Http\Resources\EventSectionResource;
use App\Policies\EventPolicy;
use App\Policies\EventMarkerPolicy;
use App\Policies\EventSectionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        EventMarkerResource::class  => EventMarkerPolicy::class,
        EventResource::class        => EventPolicy::class,
        EventSectionResource::class => EventSectionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}

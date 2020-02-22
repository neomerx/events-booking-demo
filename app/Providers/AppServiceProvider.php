<?php

namespace App\Providers;

use App\Api\EventSectionsService;
use App\Api\EventsService;
use App\Api\Interfaces\EventSectionsServiceInterface;
use App\Api\Interfaces\EventsServiceInterface;
use Illuminate\Database\Connection;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EventsServiceInterface::class, function (ContainerInterface $container) {
            /** @var Connection $connection */
            $connection = $container->get(Connection::class);
            $pdo        = $connection->getPdo();

            return new EventsService(new EventSectionsService($pdo), $pdo);
        });
        $this->app->bind(EventSectionsServiceInterface::class, function (ContainerInterface $container) {
            /** @var Connection $connection */
            $connection = $container->get(Connection::class);
            $pdo        = $connection->getPdo();

            return new EventSectionsService($pdo);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();
    }
}

<?php

namespace App\Providers;

use App\Services\Contracts\EventSectionsServiceInterface;
use App\Services\Contracts\EventsServiceInterface;
use App\Services\EventSectionsService;
use App\Services\EventsService;
use Illuminate\Database\Connection;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;
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
        $this->app->bind(ImageManager::class, function () {
            return new ImageManager();
        });
        $this->app->bind(EventsServiceInterface::class, function (ContainerInterface $container) {
            /** @var Connection $connection */
            $connection = $container->get(Connection::class);
            $pdo        = $connection->getPdo();
            /** @var EventSectionsServiceInterface $eventSectionsService */
            $eventSectionsService = $container->get(EventSectionsServiceInterface::class);

            return new EventsService($eventSectionsService, $pdo);
        });
        $this->app->bind(EventSectionsServiceInterface::class, function (ContainerInterface $container) {
            /** @var Connection $connection */
            $connection = $container->get(Connection::class);
            $pdo        = $connection->getPdo();
            /** @var ImageManager $imageManager */
            $imageManager = $container->get(ImageManager::class);

            return new EventSectionsService($pdo, $imageManager);
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

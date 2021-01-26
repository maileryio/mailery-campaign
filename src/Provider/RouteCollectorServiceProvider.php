<?php

namespace Mailery\Campaign\Provider;

use Yiisoft\Di\Container;
use Yiisoft\Di\Support\ServiceProvider;
use Yiisoft\Router\RouteCollectorInterface;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Mailery\Campaign\Controller\DefaultController;

final class RouteCollectorServiceProvider extends ServiceProvider
{
    public function register(Container $container): void
    {
        /** @var RouteCollectorInterface $collector */
        $collector = $container->get(RouteCollectorInterface::class);

        $collector->addGroup(
            Group::create(
                '/brand/{brandId:\d+}',
                [
                    // Campaigns:
                    Route::get('/campaigns', [DefaultController::class, 'index'])
                        ->name('/campaign/default/index'),
                    Route::get('/campaign/default/view/{id:\d+}', [DefaultController::class, 'view'])
                        ->name('/campaign/default/view'),
                    Route::methods(['GET', 'POST'], '/campaign/default/create', [DefaultController::class, 'create'])
                        ->name('/campaign/default/create'),
                    Route::methods(['GET', 'POST'], '/campaign/default/edit/{id:\d+}', [DefaultController::class, 'edit'])
                        ->name('/campaign/default/edit'),
                    Route::delete('/campaign/default/delete/{id:\d+}', [DefaultController::class, 'delete'])
                        ->name('/campaign/default/delete'),
                ]
            )
        );
    }
}

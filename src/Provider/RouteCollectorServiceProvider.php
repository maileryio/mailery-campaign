<?php

namespace Mailery\Campaign\Provider;

use Psr\Container\ContainerInterface;
use Yiisoft\Di\Support\ServiceProvider;
use Yiisoft\Router\RouteCollectorInterface;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Mailery\Campaign\Controller\DefaultController;

final class RouteCollectorServiceProvider extends ServiceProvider
{
    /**
     * @param ContainerInterface $container
     * @return void
     */
    public function register(ContainerInterface $container): void
    {
        /** @var RouteCollectorInterface $collector */
        $collector = $container->get(RouteCollectorInterface::class);

        $collector->addGroup(
            Group::create('/brand/{brandId:\d+}')
                ->routes(
                    // Campaigns:
                    Route::get('/campaigns')
                        ->name('/campaign/default/index')
                        ->action([DefaultController::class, 'index']),
                    Route::get('/campaign/default/view/{id:\d+}')
                        ->name('/campaign/default/view')
                        ->action([DefaultController::class, 'view']),
                    Route::methods(['GET', 'POST'], '/campaign/default/create')
                        ->name('/campaign/default/create')
                        ->action([DefaultController::class, 'create']),
                    Route::methods(['GET', 'POST'], '/campaign/default/edit/{id:\d+}')
                        ->name('/campaign/default/edit')
                        ->action([DefaultController::class, 'edit']),
                    Route::delete('/campaign/default/delete/{id:\d+}')
                        ->name('/campaign/default/delete')
                        ->action([DefaultController::class, 'delete'])
                )
        );
    }
}

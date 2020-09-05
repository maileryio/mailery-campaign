<?php

namespace Mailery\Campaign\Provider;

use Yiisoft\Di\Container;
use Yiisoft\Di\Support\ServiceProvider;
use Yiisoft\Router\RouteCollectorInterface;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Mailery\Campaign\Controller\CampaignController;

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
                    Route::get('/campaigns', [CampaignController::class, 'index'])
                        ->name('/campaign/campaign/index'),
                    Route::get('/campaign/campaign/view/{id:\d+}', [CampaignController::class, 'view'])
                        ->name('/campaign/campaign/view'),
                    Route::methods(['GET', 'POST'], '/campaign/campaign/create', [CampaignController::class, 'create'])
                        ->name('/campaign/campaign/create'),
                    Route::methods(['GET', 'POST'], '/campaign/campaign/edit/{id:\d+}', [CampaignController::class, 'edit'])
                        ->name('/campaign/campaign/edit'),
                    Route::delete('/campaign/campaign/delete/{id:\d+}', [CampaignController::class, 'delete'])
                        ->name('/campaign/campaign/delete'),
                ]
            )
        );
    }
}

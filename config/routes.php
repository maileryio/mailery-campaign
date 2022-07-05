<?php

declare(strict_types=1);

use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Mailery\Campaign\Controller\DefaultController;
use Mailery\Campaign\Controller\SendoutController;

return [
    Group::create('/brand/{brandId:\d+}')
        ->routes(
            Route::get('/campaigns')
                ->name('/campaign/default/index')
                ->action([DefaultController::class, 'index']),

            Route::methods(['POST'], '/campaign/sendout/create/{id:\d+}')
                ->name('/campaign/sendout/create')
                ->action([SendoutController::class, 'create']),

            Route::methods(['POST'], '/campaign/sendout/test/{id:\d+}')
                ->name('/campaign/sendout/test')
                ->action([SendoutController::class, 'test']),
        )
];

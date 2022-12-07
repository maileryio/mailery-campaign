<?php

declare(strict_types=1);

use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Mailery\Campaign\Controller\DefaultController;
use Mailery\Campaign\Controller\GuestController;
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
        ),
    Group::create('/campaign')
        ->routes(
            Route::get('/w/{hash:.+}')
                ->name('/campaign/guest/webversion')
                ->action([GuestController::class, 'webversion']),
            Route::get('/u/{hash:.+}')
                ->name('/campaign/guest/unsubscribe')
                ->action([GuestController::class, 'unsubscribe']),
            Route::get('/s/{hash:.+}')
                ->name('/campaign/guest/subscribe')
                ->action([GuestController::class, 'subscribe']),
        )
];

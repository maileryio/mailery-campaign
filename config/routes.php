<?php

declare(strict_types=1);

use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Mailery\Campaign\Controller\DefaultController;

return [
    Group::create('/brand/{brandId:\d+}')
        ->routes(
            // Campaigns:
            Route::get('/campaigns')
                ->name('/campaign/default/index')
                ->action([DefaultController::class, 'index']),
        )
];

<?php

declare(strict_types=1);

/**
 * Campaign module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-campaign
 * @package   Mailery\Campaign
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

use Mailery\Menu\MenuItem;
use Mailery\Campaign\Controller\CampaignController;
use Opis\Closure\SerializableClosure;
use Yiisoft\Router\Route;
use Yiisoft\Router\UrlGeneratorInterface;

return [
    'yiisoft/yii-cycle' => [
        'annotated-entity-paths' => [
            '@vendor/maileryio/mailery-campaign/src/Entity',
        ],
    ],

    'router' => [
        'routes' => [
            // Campaigns:
            '/campaign/campaign/index' => Route::get('/brand/{brandId:\d+}/campaigns', [CampaignController::class, 'index'])
                ->name('/campaign/campaign/index'),
            '/campaign/campaign/view' => Route::get('/brand/{brandId:\d+}/campaign/campaign/view/{id:\d+}', [CampaignController::class, 'view'])
                ->name('/campaign/campaign/view'),
            '/campaign/campaign/create' => Route::methods(['GET', 'POST'], '/brand/{brandId:\d+}/campaign/campaign/create', [CampaignController::class, 'create'])
                ->name('/campaign/campaign/create'),
            '/campaign/campaign/edit' => Route::methods(['GET', 'POST'], '/brand/{brandId:\d+}/campaign/campaign/edit/{id:\d+}', [CampaignController::class, 'edit'])
                ->name('/campaign/campaign/edit'),
            '/campaign/campaign/delete' => Route::delete('/brand/{brandId:\d+}/campaign/campaign/delete/{id:\d+}', [CampaignController::class, 'delete'])
                ->name('/campaign/campaign/delete'),
        ],
    ],

    'menu' => [
        'sidebar' => [
            'items' => [
                'campaigns' => (new MenuItem())
                    ->withLabel('Campaigns')
                    ->withIcon('campaign')
                    ->withChildItems([
                        'campaigns' => (new MenuItem())
                            ->withLabel('All Campaigns')
                            ->withUrl(new SerializableClosure(function (UrlGeneratorInterface $urlGenerator) {
                                return $urlGenerator->generate('/campaign/campaign/index');
                            }))
                            ->withActiveRouteNames([
                                '/campaign/campaign/index',
                                '/campaign/campaign/view',
                                '/campaign/campaign/create',
                                '/campaign/campaign/edit',
                                '/campaign/campaign/delete',
                            ])
                            ->withOrder(100),
                    ])
                    ->withOrder(200),
            ],
        ],
    ],
];

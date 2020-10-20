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
use Opis\Closure\SerializableClosure;
use Yiisoft\Router\UrlGeneratorInterface;

return [
    'maileryio/mailery-campaign' => [
        'types' => [],
    ],

    'yiisoft/yii-cycle' => [
        'annotated-entity-paths' => [
            '@vendor/maileryio/mailery-campaign/src/Entity',
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

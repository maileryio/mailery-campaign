<?php

declare(strict_types=1);

/**
 * Campaign module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-campaign
 * @package   Mailery\Campaign
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

use Mailery\Campaign\Command\ScheduleCampaignCommand;
use Mailery\Campaign\Command\SendCampaignCommand;
use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\CampaignGroup;
use Mailery\Campaign\Messenger\Message\SendCampaign;
use Mailery\Campaign\Messenger\Handler\SendCampaignHandler;
use Mailery\Messenger\Transport\BeanstalkdTransportFactory;
use Symfony\Component\Messenger\Retry\MultiplierRetryStrategy;
use Yiisoft\Definitions\DynamicReference;
use Yiisoft\Definitions\Reference;
use Yiisoft\Router\UrlGeneratorInterface;

return [
    'maileryio/mailery-campaign' => [
        'types' => [],
    ],

    'maileryio/mailery-activity-log' => [
        'entity-groups' => [
            'campaign' => [
                'label' => DynamicReference::to(static fn () => 'Campaign'),
                'entities' => [
                    Campaign::class,
                    CampaignGroup::class,
                ],
            ],
        ],
    ],

    'maileryio/mailery-messenger' => [
        'handlers' => [
            SendCampaign::class => [SendCampaignHandler::class],
        ],
        'senders' => [
            SendCampaign::class => ['sendout'],
        ],
        'recievers' => [
            'sendout' => [
                'transport' => DynamicReference::to(new BeanstalkdTransportFactory([
                    'tube_name' => 'sendout'
                ])),
                'retryStrategy' => Reference::to(MultiplierRetryStrategy::class),
            ],
        ],
    ],

    'yiisoft/yii-console' => [
        'commands' => [
            'campaign/schedule' => ScheduleCampaignCommand::class,
            'campaign/send' => SendCampaignCommand::class,
        ],
    ],

    'yiisoft/yii-cycle' => [
        'entity-paths' => [
            '@vendor/maileryio/mailery-campaign/src/Entity',
        ],
    ],

    'maileryio/mailery-menu-sidebar' => [
        'items' => [
            'campaigns' => [
                'label' => static function () {
                    return 'Campaigns';
                },
                'icon' => 'campaign',
                'items' => [
                    'campaigns' => [
                        'label' => static function () {
                            return 'All Campaigns';
                        },
                        'url' => static function (UrlGeneratorInterface $urlGenerator) {
                            return $urlGenerator->generate('/campaign/default/index');
                        },
                        'activeRouteNames' => [
                            '/campaign/default/index',
                            '/campaign/default/view',
                            '/campaign/default/create',
                            '/campaign/default/edit',
                            '/campaign/default/delete',
                        ],
                    ],
                ],
            ],
        ],
    ],
];

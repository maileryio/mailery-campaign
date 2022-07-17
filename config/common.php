<?php

declare(strict_types=1);

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Campaign\Model\CampaignTypeList;
use Mailery\Campaign\Repository\CampaignRepository;
use Mailery\Campaign\Repository\RecipientRepository;
use Psr\Container\ContainerInterface;
use Cycle\ORM\ORMInterface;

return [
    CampaignTypeList::class => [
        '__construct()' => [
            'elements' => $params['maileryio/mailery-campaign']['types'],
        ],
    ],

    CampaignRepository::class => static function (ContainerInterface $container) {
        return $container
            ->get(ORMInterface::class)
            ->getRepository(Campaign::class);
    },

    RecipientRepository::class => static function (ContainerInterface $container) {
        return $container
            ->get(ORMInterface::class)
            ->getRepository(Recipient::class);
    },
];

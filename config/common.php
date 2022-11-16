<?php

declare(strict_types=1);

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Model\CampaignTypeList;
use Mailery\Campaign\Repository\CampaignRepository;
use Mailery\Campaign\Repository\RecipientRepository;
use Mailery\Campaign\Repository\SendoutRepository;
use Cycle\ORM\ORMInterface;

return [
    CampaignTypeList::class => [
        '__construct()' => [
            'elements' => $params['maileryio/mailery-campaign']['types'],
        ],
    ],

    CampaignRepository::class => static function (ORMInterface $orm) {
        return $orm->getRepository(Campaign::class);
    },

    RecipientRepository::class => static function (ORMInterface $orm) {
        return $orm->getRepository(Recipient::class);
    },

    SendoutRepository::class => static function (ORMInterface $orm) {
        return $orm->getRepository(Sendout::class);
    },
];

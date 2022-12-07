<?php

declare(strict_types=1);

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Model\CampaignTypeList;
use Mailery\Campaign\Repository\CampaignRepository;
use Mailery\Campaign\Repository\RecipientRepository;
use Mailery\Campaign\Repository\SendoutRepository;
use Mailery\Campaign\Renderer\WrappedUrlGenerator;
use Mailery\Campaign\Controller\GuestController;
use Mailery\Security\Security;
use Mailery\Security\MappedSerializer;
use Cycle\ORM\ORMInterface;
use Yiisoft\Definitions\DynamicReference;
use Yiisoft\Router\UrlGeneratorInterface;

$fnSecurityFactory = static function (Security $security) {
    return $security->withSerializer(new MappedSerializer([
        'recipientId' => 'r',
        'subscriberId' => 's',
    ]));
};

return [
    CampaignTypeList::class => [
        '__construct()' => [
            'elements' => $params['maileryio/mailery-campaign']['types'],
        ],
    ],

    WrappedUrlGenerator::class => [
        '__construct()' => [
            'urlGenerator' => DynamicReference::to(static function (UrlGeneratorInterface $urlGenerator) {
                $clonedUrlGenerator = clone $urlGenerator;

                $reflectionProperty = new \ReflectionProperty($clonedUrlGenerator, 'defaultArguments');
                $reflectionProperty->setAccessible(true);

                $defaultArguments = $reflectionProperty->getValue($clonedUrlGenerator);

                unset($defaultArguments['brandId']);

                $reflectionProperty->setValue($clonedUrlGenerator, $defaultArguments);

                return $clonedUrlGenerator;
            }),
            'security' => DynamicReference::to($fnSecurityFactory),
        ],
    ],

    GuestController::class => [
        '__construct()' => [
            'security' => DynamicReference::to($fnSecurityFactory),
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

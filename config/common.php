<?php

declare(strict_types=1);

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Model\CampaignTypeList;
use Mailery\Campaign\Repository\CampaignRepository;
use Mailery\Campaign\Repository\RecipientRepository;
use Mailery\Campaign\Repository\SendoutRepository;
use Mailery\Campaign\Service\SecurityService;
use Mailery\Campaign\Security\MappedSerializer;
use Mailery\Campaign\Renderer\WrappedUrlGenerator;
use Mailery\Campaign\Controller\GuestController;
use Cycle\ORM\ORMInterface;
use Yiisoft\Definitions\DynamicReference;
use Yiisoft\Router\UrlGeneratorInterface;

$securitySerializeParamsMap = [
    'recipientId' => 'r',
    'subscriberId' => 's',
];

return [
    CampaignTypeList::class => [
        '__construct()' => [
            'elements' => $params['maileryio/mailery-campaign']['types'],
        ],
    ],

    SecurityService::class => [
        '__construct()' => [
            'encryptKey' => $params['maileryio/mailery-security']['encryptKey'],
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
            'securityService' => DynamicReference::to(static function (SecurityService $securityService) use($securitySerializeParamsMap) {
                return $securityService->withSerializer(new MappedSerializer($securitySerializeParamsMap));
            }),
        ],
    ],

    GuestController::class => [
        '__construct()' => [
            'securityService' => DynamicReference::to(static function (SecurityService $securityService) use($securitySerializeParamsMap) {
                return $securityService->withSerializer(new MappedSerializer($securitySerializeParamsMap));
            }),
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

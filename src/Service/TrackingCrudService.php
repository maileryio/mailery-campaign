<?php

namespace Mailery\Campaign\Service;

use Cycle\ORM\EntityManagerInterface;
use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\ValueObject\TrackingValueObject;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class TrackingCrudService
{

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}


    /**
     * @param Campaign $campaign
     * @param TrackingValueObject $valueObject
     * @return Campaign
     */
    public function update(Campaign $campaign, TrackingValueObject $valueObject): Campaign
    {
        $campaign = $campaign
            ->setTrackClicks($valueObject->getTrackClicks())
            ->setTrackOpens($valueObject->getTrackOpens())
            ->setEnableUtmTags($valueObject->getEnableUtmTags())
            ->setUtmTags($valueObject->getUtmTags())
        ;

        (new EntityWriter($this->entityManager))->write([$campaign]);

        return $campaign;
    }

}

<?php

namespace Mailery\Campaign\Service;

use Cycle\ORM\EntityManagerInterface;
use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\ValueObject\CampaignValueObject;
use Mailery\Brand\Entity\Brand;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class CampaignCrudService
{
    /**
     * @var Brand
     */
    private Brand $brand;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @param Brand $brand
     * @return self
     */
    public function withBrand(Brand $brand): self
    {
        $new = clone $this;
        $new->brand = $brand;

        return $new;
    }

    /**
     * @param Campaign $campaign
     * @param CampaignValueObject $valueObject
     * @return Campaign
     */
    public function update(Campaign $campaign, CampaignValueObject $valueObject): Campaign
    {
        $campaign = $campaign
            ->setName($valueObject->getName())
            ->setSender($valueObject->getSender())
            ->setTemplate($valueObject->getTemplate())
            ->setStatus($valueObject->getStatus())
        ;

        foreach ($campaign->getGroups() as $group) {
            $campaign->getGroups()->removeElement($group);
        }

        foreach ($valueObject->getGroups() as $group) {
            $campaign->getGroups()->add($group);
        }

        (new EntityWriter($this->entityManager))->write([$campaign]);

        return $campaign;
    }

    /**
     * @param Campaign $campaign
     * @return bool
     */
    public function delete(Campaign $campaign): bool
    {
        foreach ($campaign->getGroups() as $groupPivot) {
             $campaign->getGroups()->removeElement($groupPivot);
        }

        (new EntityWriter($this->entityManager))->delete([$campaign]);

        return true;
    }
}

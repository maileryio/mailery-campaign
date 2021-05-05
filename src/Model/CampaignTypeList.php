<?php

namespace Mailery\Campaign\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Mailery\Campaign\Model\CampaignTypeInterface;

final class CampaignTypeList extends ArrayCollection
{
    /**
     * @param object $campaign
     * @return CampaignTypeInterface|null
     */
    public function findByEntity(object $campaign): ?CampaignTypeInterface
    {
        return $this->filter(function (CampaignTypeInterface $campaignType) use($campaign) {
            return $campaignType->isEntitySameType($campaign);
        })->first() ?: null;
    }
}

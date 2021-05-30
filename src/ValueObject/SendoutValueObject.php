<?php

namespace Mailery\Campaign\ValueObject;

use Mailery\Campaign\Entity\Campaign;

class SendoutValueObject
{
    /**
     * @var Campaign
     */
    private Campaign $campaign;

    /**
     * @param bool $isTest
     * @return self
     */
    public function withIsTest(bool $isTest): self
    {
        $new = clone $this;
        $new->isTest = $isTest;

        return $new;
    }

    /**
     * @param Campaign $campaign
     * @return self
     */
    public function withCampaign(Campaign $campaign): self
    {
        $new = clone $this;
        $new->campaign = $campaign;

        return $new;
    }

    /**
     * @return bool
     */
    public function getIsTest(): bool
    {
        return $this->isTest;
    }

    /**
     * @return Campaign
     */
    public function getCampagn(): Campaign
    {
        return $this->campaign;
    }
}

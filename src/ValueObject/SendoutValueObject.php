<?php

namespace Mailery\Campaign\ValueObject;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Field\SendoutMode;

class SendoutValueObject
{
    /**
     * @var SendoutMode
     */
    private SendoutMode $mode;

    /**
     * @var Campaign
     */
    private Campaign $campaign;

    /**
     * @param SendoutMode $mode
     * @return self
     */
    public function withMode(SendoutMode $mode): self
    {
        $new = clone $this;
        $new->mode = $mode;

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
     * @return SendoutMode
     */
    public function getMode(): SendoutMode
    {
        return $this->mode;
    }

    /**
     * @return Campaign
     */
    public function getCampagn(): Campaign
    {
        return $this->campaign;
    }
}

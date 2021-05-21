<?php

namespace Mailery\Campaign\ValueObject;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Recipient\Model\RecipientIterator;

class SendoutValueObject
{
    /**
     * @var bool
     */
    private bool $isTest = false;

    /**
     * @var Campaign
     */
    private Campaign $campaign;

    /**
     * @var RecipientIterator
     */
    private RecipientIterator $recipients;

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
     * @param RecipientIterator $recipients
     * @return self
     */
    public function withRecipients(RecipientIterator $recipients): self
    {
        $new = clone $this;
        $new->recipients = $recipients;

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

    /**
     * @return RecipientIterator
     */
    public function getRecipients(): RecipientIterator
    {
        return $this->recipients;
    }
}

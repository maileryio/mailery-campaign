<?php

namespace Mailery\Campaign\ValueObject;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Form\SendTestForm;

class SendoutValueObject
{
    /**
     * @var string
     */
    private string $recipients;

    /**
     * @var Campaign
     */
    private Campaign $campaign;

    /**
     * @param SendTestForm $form
     * @return self
     */
    public static function fromTestForm(SendTestForm $form): self
    {
        $new = new self();
        $new->recipients = $form->getAttributeValue('recipients');

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
     * @return Campaign
     */
    public function getCampagn(): Campaign
    {
        return $this->campaign;
    }
}

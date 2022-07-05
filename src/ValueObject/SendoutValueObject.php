<?php

namespace Mailery\Campaign\ValueObject;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Field\SendoutMode;
use Mailery\Campaign\Field\SendoutStatus;

class SendoutValueObject
{
    /**
     * @var SendoutMode
     */
    private SendoutMode $mode;

    /**
     * @var SendoutStatus
     */
    private SendoutStatus $status;

    /**
     * @var Campaign
     */
    private Campaign $campaign;

    /**
     * @param Sendout $entity
     * @return self
     */
    public static function fromEntity(Sendout $entity): self
    {
        $new = new self();
        $new->mode = $entity->getMode();
        $new->status = $entity->getStatus();
        $new->campaign = $entity->getCampaign();

        return $new;
    }

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
     * @param SendoutStatus $status
     * @return self
     */
    public function withStatus(SendoutStatus $status): self
    {
        $new = clone $this;
        $new->status = $status;

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
     * @return SendoutStatus
     */
    public function getStatus(): SendoutStatus
    {
        return $this->status;
    }

    /**
     * @return Campaign
     */
    public function getCampagn(): Campaign
    {
        return $this->campaign;
    }

    /**
     * @return self
     */
    public function asCreated(): self
    {
        return $this->withStatus($this->status->asCreated());
    }

    /**
     * @return self
     */
    public function asPending(): self
    {
        return $this->withStatus($this->status->asPending());
    }

    /**
     * @return self
     */
    public function asFinished(): self
    {
        return $this->withStatus($this->status->asFinished());
    }

    /**
     * @return self
     */
    public function asErrored(): self
    {
        return $this->withStatus($this->status->asErrored());
    }
}

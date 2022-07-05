<?php

namespace Mailery\Campaign\ValueObject;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Form\CampaignForm;
use Mailery\Campaign\Field\CampaignStatus;
use Mailery\Template\Entity\Template;
use Mailery\Sender\Entity\Sender;
use Mailery\Subscriber\Entity\Group;

class CampaignValueObject
{

    /**
     * @var string|null
     */
    private ?string $name;

    /**
     * @var CampaignStatus
     */
    private CampaignStatus $status;

    /**
     * @var Sender|null
     */
    private ?Sender $sender;

    /**
     * @var Template|null
     */
    private ?Template $template;

    /**
     * @var Group[]
     */
    private array $groups;

    /**
     * @param Campaign $campaign
     * @return self
     */
    public static function fromEntity(Campaign $campaign): self
    {
        $new = new self();
        $new->name = $campaign->getName();
        $new->status = $campaign->getStatus();
        $new->sender = $campaign->getSender();
        $new->template = $campaign->getTemplate();
        $new->groups = $campaign->getGroups()->toArray();

        return $new;
    }

    /**
     * @param CampaignForm $form
     * @return self
     */
    public static function fromForm(CampaignForm $form): self
    {
        $new = new self();
        $new->name = $form->getName();
        $new->sender = $form->getSender();
        $new->template = $form->getTemplate();
        $new->groups = $form->getGroups();

        return $new;
    }

    /**
     * @param CampaignStatus $status
     * @return self
     */
    public function withStatus(CampaignStatus $status): self
    {
        $new = clone $this;
        $new->status = $status;

        return $new;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return CampaignStatus
     */
    public function getStatus(): CampaignStatus
    {
        return $this->status;
    }

    /**
     * @return Sender|null
     */
    public function getSender(): ?Sender
    {
        return $this->sender;
    }

    /**
     * @return Template|null
     */
    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    /**
     * @return Group[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return self
     */
    public function asDraft(): self
    {
        return $this->withStatus($this->status->asDraft());
    }

    /**
     * @return self
     */
    public function asScheduled(): self
    {
        return $this->withStatus($this->status->asScheduled());
    }

    /**
     * @return self
     */
    public function asQueued(): self
    {
        return $this->withStatus($this->status->asQueued());
    }

    /**
     * @return self
     */
    public function asSending(): self
    {
        return $this->withStatus($this->status->asSending());
    }

    /**
     * @return self
     */
    public function asSent(): self
    {
        return $this->withStatus($this->status->asSent());
    }

    /**
     * @return self
     */
    public function asErrored(): self
    {
        return $this->withStatus($this->status->asErrored());
    }

}

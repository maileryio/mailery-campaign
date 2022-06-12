<?php

declare(strict_types=1);

/**
 * Campaign module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-campaign
 * @package   Mailery\Campaign
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Campaign\Entity;

use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Relation\ManyToMany;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Mailery\Brand\Entity\Brand;
use Mailery\Template\Entity\Template;
use Mailery\Sender\Entity\Sender;
use Cycle\ORM\Collection\Pivoted\PivotedCollection;
use Mailery\Campaign\Repository\CampaignRepository;
use Mailery\Activity\Log\Mapper\LoggableMapper;
use Cycle\ORM\Collection\DoctrineCollectionFactory;
use Cycle\ORM\Entity\Behavior;
use Mailery\Subscriber\Entity\Group;
use Mailery\Campaign\Entity\CampaignGroup;
use Mailery\Campaign\Field\CampaignStatus;
use Mailery\Campaign\Field\UtmTags;
use Cycle\Annotated\Annotation\Inheritance\DiscriminatorColumn;

/**
* This doc block required for STI/JTI
*/
#[Entity(
    table: 'campaigns',
    repository: CampaignRepository::class,
    mapper: LoggableMapper::class,
)]
#[Behavior\CreatedAt(
    field: 'createdAt',
    column: 'created_at',
)]
#[Behavior\UpdatedAt(
    field: 'updatedAt',
    column: 'updated_at',
)]
#[DiscriminatorColumn(name: 'type')]
abstract class Campaign
{
    #[Column(type: 'primary')]
    protected int $id;

    #[Column(type: 'string(255)')]
    protected string $name;

    #[BelongsTo(target: Brand::class)]
    protected Brand $brand;

    #[BelongsTo(target: Sender::class, load: 'eager')]
    protected Sender $sender;

    #[BelongsTo(target: Template::class, load: 'eager')]
    protected Template $template;

    #[ManyToMany(target: Group::class, though: CampaignGroup::class, thoughInnerKey: 'campaign_id', thoughOuterKey: 'subscriber_group_id', collection: DoctrineCollectionFactory::class)]
    protected PivotedCollection $groups;

    #[Column(type: 'enum(draft, scheduled, queued, sending, sent)', default: 'draft', typecast: CampaignStatus::class)]
    protected CampaignStatus $status;

    #[Column(type: 'string(255)')]
    protected string $type;

    #[Column(type: 'boolean', default: false)]
    protected bool $trackClicks = false;

    #[Column(type: 'boolean', default: false)]
    protected bool $trackOpens;

    #[Column(type: 'boolean', default: false)]
    protected bool $enableUtmTags = false;

    #[Column(type: 'string', nullable: true, typecast: UtmTags::class)]
    protected ?UtmTags $utmTags = null;

    #[Column(type: 'datetime')]
    protected \DateTimeImmutable $createdAt;

    #[Column(type: 'datetime', nullable: true)]
    protected ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->groups = new PivotedCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     * @return self
     */
    public function setBrand(Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Sender
     */
    public function getSender(): Sender
    {
        return $this->sender;
    }

    /**
     * @param Sender $sender
     * @return self
     */
    public function setSender(Sender $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return Template
     */
    public function getTemplate(): Template
    {
        return $this->template;
    }

    /**
     * @param Template $template
     * @return self
     */
    public function setTemplate(Template $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return PivotedCollection
     */
    public function getGroups(): PivotedCollection
    {
        return $this->groups;
    }

    /**
     * @param PivotedCollection $groups
     * @return self
     */
    public function setGroups(PivotedCollection $groups): self
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @return CampaignStatus
     */
    public function getStatus(): CampaignStatus
    {
        return $this->status;
    }

    /**
     * @param CampaignStatus $status
     * @return self
     */
    public function setStatus(CampaignStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return bool
     */
    public function getTrackClicks(): bool
    {
        return $this->trackClicks;
    }

    /**
     * @param bool $trackClicks
     * @return self
     */
    public function setTrackClicks(bool $trackClicks): self
    {
        $this->trackClicks = $trackClicks;

        return $this;
    }

    /**
     * @return bool
     */
    public function getTrackOpens(): bool
    {
        return $this->trackOpens;
    }

    /**
     * @param bool $trackOpens
     * @return self
     */
    public function setTrackOpens(bool $trackOpens): self
    {
        $this->trackOpens = $trackOpens;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEnableUtmTags(): bool
    {
        return $this->enableUtmTags;
    }

    /**
     * @param bool $enableUtmTags
     * @return self
     */
    public function setEnableUtmTags(bool $enableUtmTags): self
    {
        $this->enableUtmTags = $enableUtmTags;

        return $this;
    }

    /**
     * @return UtmTags|null
     */
    public function getUtmTags(): ?UtmTags
    {
        return $this->utmTags;
    }

    /**
     * @param UtmTags|null $utmTags
     * @return self
     */
    public function setUtmTags(?UtmTags $utmTags): self
    {
        $this->utmTags = $utmTags;

        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return bool
     */
    public function canBeEdited(): bool
    {
        return $this->getStatus()->isDraft();
    }
}

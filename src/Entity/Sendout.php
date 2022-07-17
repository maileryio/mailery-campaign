<?php

namespace Mailery\Campaign\Entity;

use Mailery\Activity\Log\Entity\LoggableEntityInterface;
use Mailery\Activity\Log\Entity\LoggableEntityTrait;
use Mailery\Activity\Log\Mapper\LoggableMapper;
use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Field\SendoutStatus;
use Mailery\Campaign\Field\SendoutMode;
use Cycle\ORM\Collection\DoctrineCollectionFactory;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\ORM\Entity\Behavior;
use Doctrine\Common\Collections\ArrayCollection;

#[Entity(
    table: 'sendouts',
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
class Sendout implements LoggableEntityInterface
{
    use LoggableEntityTrait;

    #[Column(type: 'primary')]
    private int $id;

    #[Column(type: 'enum(default, test)', typecast: SendoutMode::class)]
    private SendoutMode $mode;

    #[Column(type: 'enum(created, pending, finished, errored)', typecast: SendoutStatus::class)]
    private SendoutStatus $status;

    #[BelongsTo(target: Campaign::class)]
    private Campaign $campaign;

    #[HasMany(target: Recipient::class, collection: DoctrineCollectionFactory::class)]
    private ArrayCollection $recipients;

    #[Column(type: 'string(255)', nullable: true)]
    private ?string $error = null;

    #[Column(type: 'datetime')]
    private \DateTimeImmutable $createdAt;

    #[Column(type: 'datetime', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->recipients = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'Sendout';
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
     * @return SendoutMode
     */
    public function getMode(): SendoutMode
    {
        return $this->mode;
    }

    /**
     * @param SendoutMode $mode
     * @return self
     */
    public function setMode(SendoutMode $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return SendoutStatus
     */
    public function getStatus(): SendoutStatus
    {
        return $this->status;
    }

    /**
     * @param SendoutStatus $status
     * @return self
     */
    public function setStatus(SendoutStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Campaign
     */
    public function getCampaign(): Campaign
    {
        return $this->campaign;
    }

    /**
     * @param Campaign $campaign
     * @return self
     */
    public function setCampaign(Campaign $campaign): self
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRecipients(): ArrayCollection
    {
        return $this->recipients;
    }

    /**
     * @param ArrayCollection $recipients
     * @return self
     */
    public function setRecipients(ArrayCollection $recipients): self
    {
        $this->recipients = $recipients;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string|null $error
     * @return self
     */
    public function setError(?string $error): self
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
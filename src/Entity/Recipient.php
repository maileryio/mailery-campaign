<?php

namespace Mailery\Campaign\Entity;

use Mailery\Activity\Log\Entity\LoggableEntityInterface;
use Mailery\Activity\Log\Entity\LoggableEntityTrait;
use Mailery\Activity\Log\Mapper\LoggableMapper;
use Mailery\Subscriber\Entity\Subscriber;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Repository\RecipientRepository;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\RefersTo;
use Cycle\ORM\Entity\Behavior;

#[Entity(
    table: 'sendout_recipients',
    repository: RecipientRepository::class,
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
class Recipient implements LoggableEntityInterface
{
    use LoggableEntityTrait;

    #[Column(type: 'primary')]
    private int $id;

    #[Column(type: 'string(255)')]
    private string $name;

    #[Column(type: 'string(255)')]
    private string $identifier;

    #[BelongsTo(target: Sendout::class)]
    private Sendout $sendout;

    #[RefersTo(target: Subscriber::class, nullable: true)]
    private ?Subscriber $subscriber = null;

    #[Column(type: 'string(255)', nullable: true)]
    private ?string $error = null;

    #[Column(type: 'boolean', default: false)]
    protected bool $sent = false;

    #[Column(type: 'boolean', default: false)]
    protected bool $recieved = false;

    #[Column(type: 'boolean', default: false)]
    protected bool $opened = false;

    #[Column(type: 'boolean', default: false)]
    protected bool $clicked = false;

    #[Column(type: 'boolean', default: false)]
    protected bool $bounced = false;

    #[Column(type: 'boolean', default: false)]
    protected bool $unsubscribed = false;

    #[Column(type: 'boolean', default: false)]
    protected bool $complained = false;

    #[Column(type: 'datetime')]
    private \DateTimeImmutable $createdAt;

    #[Column(type: 'datetime', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'Recipient';
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
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return self
     */
    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return Sendout
     */
    public function getSendout(): Sendout
    {
        return $this->sendout;
    }

    /**
     * @param Sendout $sendout
     * @return self
     */
    public function setSendout(Sendout $sendout): self
    {
        $this->sendout = $sendout;

        return $this;
    }

    /**
     * @return Subscriber|null
     */
    public function getSubscriber(): ?Subscriber
    {
        return $this->subscriber;
    }

    /**
     * @param Subscriber $subscriber
     * @return self
     */
    public function setSubscriber(Subscriber $subscriber): self
    {
        $this->subscriber = $subscriber;

        return $this;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $error
     * @return self
     */
    public function setError(string $error): self
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->sent;
    }

    /**
     * @param bool $sent
     * @return self
     */
    public function setSent(bool $sent): self
    {
        $this->sent = $sent;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRecieved(): bool
    {
        return $this->recieved;
    }

    /**
     * @param bool $recieved
     * @return self
     */
    public function setRecieved(bool $recieved): self
    {
        $this->recieved = $recieved;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOpened(): bool
    {
        return $this->opened;
    }

    /**
     * @param bool $opened
     * @return self
     */
    public function setOpened(bool $opened): self
    {
        $this->opened = $opened;

        return $this;
    }

    /**
     * @return bool
     */
    public function isClicked(): bool
    {
        return $this->clicked;
    }

    /**
     * @param bool $clicked
     * @return self
     */
    public function setClicked(bool $clicked): self
    {
        $this->clicked = $clicked;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBounced(): bool
    {
        return $this->bounced;
    }

    /**
     * @param bool $bounced
     * @return self
     */
    public function setBounced(bool $bounced): self
    {
        $this->bounced = $bounced;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUnsubscribed(): bool
    {
        return $this->unsubscribed;
    }

    /**
     * @param bool $unsubscribed
     * @return self
     */
    public function setUnsubscribed(bool $unsubscribed): self
    {
        $this->unsubscribed = $unsubscribed;

        return $this;
    }

    /**
     * @return bool
     */
    public function isComplained(): bool
    {
        return $this->complained;
    }

    /**
     * @param bool $complained
     * @return self
     */
    public function setComplained(bool $complained): self
    {
        $this->complained = $complained;

        return $this;
    }

    /**
     * @return bool
     */
    public function canBeSend(): bool
    {
        if ($this->subscriber !== null && !$this->subscriber->isActive()) {
            return false;
        }

        return !$this->isSent();
    }
}
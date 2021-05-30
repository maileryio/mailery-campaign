<?php

namespace Mailery\Campaign\Entity;

use Mailery\Activity\Log\Entity\LoggableEntityInterface;
use Mailery\Activity\Log\Entity\LoggableEntityTrait;
use Mailery\Subscriber\Entity\Subscriber;
use Mailery\Campaign\Entity\Sendout;

/**
 * @Cycle\Annotated\Annotation\Entity(
 *      table = "sendout_recipients",
 *      mapper = "Mailery\Campaign\Mapper\RecipientMapper"
 * )
 */
class Recipient implements LoggableEntityInterface
{
    use LoggableEntityTrait;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "primary")
     * @var int|null
     */
    private $id;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "string(255)")
     * @var string
     */
    private $name;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "string(255)")
     * @var string
     */
    private $identifier;

    /**
     * @Cycle\Annotated\Annotation\Relation\BelongsTo(target = "Mailery\Campaign\Entity\Sendout")
     * @var Sendout
     */
    private $sendout;

    /**
     * @Cycle\Annotated\Annotation\Relation\RefersTo(target = "Mailery\Subscriber\Entity\Subscriber", nullable = true)
     * @var Subscriber|null
     */
    private $subscriber;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'Recipient';
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id ? (string) $this->id : null;
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
     * @return bool
     */
    public function hasSendout(): bool
    {
        return $this->sendout !== null;
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
}
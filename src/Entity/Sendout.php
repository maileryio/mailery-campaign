<?php

namespace Mailery\Campaign\Entity;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Activity\Log\Entity\LoggableEntityInterface;
use Mailery\Activity\Log\Entity\LoggableEntityTrait;
use Cycle\ORM\Collection\Pivoted\PivotedCollection;
use Cycle\ORM\Collection\Pivoted\PivotedCollectionInterface;

/**
 * @Cycle\Annotated\Annotation\Entity(
 *      table = "sendouts",
 *      mapper = "Mailery\Campaign\Mapper\SendoutMapper"
 * )
 */
class Sendout implements LoggableEntityInterface
{
    use LoggableEntityTrait;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "primary")
     * @var int|null
     */
    private $id;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "enum(created, pending, finished)")
     * @var string
     */
    private $status;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "boolean")
     * @var bool
     */
    private $isTest;

    /**
     * @Cycle\Annotated\Annotation\Relation\BelongsTo(target = "Mailery\Campaign\Entity\Campaign")
     * @var Campaign
     */
    private $campaign;

    /**
     * @Cycle\Annotated\Annotation\Relation\HasMany(target = "Mailery\Campaign\Entity\Recipient")
     * @var PivotedCollectionInterface
     */
    private $recipients;

    public function __construct()
    {
        $this->recipients = new PivotedCollection();
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
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getIsTest(): string
    {
        return $this->isTest;
    }

    /**
     * @param string $isTest
     * @return self
     */
    public function setIsTest(string $isTest): self
    {
        $this->isTest = $isTest;

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
     * @return PivotedCollectionInterface
     */
    public function getRecipients(): PivotedCollectionInterface
    {
        return $this->recipients;
    }

    /**
     * @param PivotedCollectionInterface $recipients
     * @return self
     */
    public function setRecipients(PivotedCollectionInterface $recipients): self
    {
        $this->recipients = $recipients;

        return $this;
    }
}
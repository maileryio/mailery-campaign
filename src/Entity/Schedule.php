<?php

namespace Mailery\Campaign\Entity;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Activity\Log\Entity\LoggableEntityInterface;
use Mailery\Activity\Log\Entity\LoggableEntityTrait;
use Mailery\Activity\Log\Mapper\LoggableMapper;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\ORM\Entity\Behavior;

#[Entity(
    table: 'campaign_schedules',
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
class Schedule implements LoggableEntityInterface
{

    use LoggableEntityTrait;

    #[Column(type: 'primary')]
    private int $id;

    #[BelongsTo(target: Campaign::class)]
    private Campaign $campaign;

    #[Column(type: 'datetime')]
    private \DateTimeImmutable $datetime;

    #[Column(type: 'string')]
    private string $timezone;

    #[Column(type: 'datetime')]
    private \DateTimeImmutable $createdAt;

    #[Column(type: 'datetime', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'Campaign Schedule';
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
     * @return \DateTimeImmutable
     */
    public function getDatetime(): \DateTimeImmutable
    {
        return $this->datetime;
    }

    /**
     * @param \DateTimeImmutable $datetime
     * @return self
     */
    public function setDatetime(\DateTimeImmutable $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * @param string $timezone
     * @return self
     */
    public function setTimezone(string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

}

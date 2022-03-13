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

use Mailery\Brand\Entity\Brand;
use Mailery\Channel\Entity\Channel;
use Mailery\Template\Entity\Template;
use Mailery\Sender\Entity\Sender;
use Cycle\ORM\Collection\Pivoted\PivotedCollection;
use Cycle\ORM\Collection\Pivoted\PivotedCollectionInterface;

/**
 * @Cycle\Annotated\Annotation\Entity(
 *      table = "campaigns",
 *      repository = "Mailery\Campaign\Repository\CampaignRepository",
 *      mapper = "Mailery\Campaign\Mapper\DefaultMapper"
 * )
 */
abstract class Campaign
{
    /**
     * @Cycle\Annotated\Annotation\Column(type = "primary")
     * @var int|null
     */
    protected $id;

    /**
     * @Cycle\Annotated\Annotation\Relation\BelongsTo(target = "Mailery\Brand\Entity\Brand")
     * @var Brand
     */
    protected $brand;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "string(255)")
     * @var string
     */
    protected $name;

    /**
     * @Cycle\Annotated\Annotation\Relation\BelongsTo(target = "Mailery\Channel\Entity\Channel", load = "eager")
     * @var Channel
     */
    protected $channel;

    /**
     * @Cycle\Annotated\Annotation\Relation\BelongsTo(target = "Mailery\Sender\Entity\Sender", load = "eager")
     * @var Sender
     */
    protected $sender;

    /**
     * @Cycle\Annotated\Annotation\Relation\BelongsTo(target = "Mailery\Template\Entity\Template", load = "eager")
     * @var Template
     */
    protected $template;

    /**
     * @Cycle\Annotated\Annotation\Relation\ManyToMany(target = "Mailery\Subscriber\Entity\Group", though = "CampaignGroup")
     * @var PivotedCollectionInterface
     */
    protected $groups;

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
     * @return Channel
     */
    public function getChannel(): Channel
    {
        return $this->channel;
    }

    /**
     * @param Channel $channel
     * @return self
     */
    public function setChannel(Channel $channel): self
    {
        $this->channel = $channel;

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
     * @return PivotedCollectionInterface
     */
    public function getGroups(): PivotedCollectionInterface
    {
        return $this->groups;
    }

    /**
     * @param PivotedCollectionInterface $groups
     * @return self
     */
    public function setGroups(PivotedCollectionInterface $groups): self
    {
        $this->groups = $groups;

        return $this;
    }
}

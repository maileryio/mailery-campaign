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

use RuntimeException;
use Mailery\Brand\Entity\Brand;
use Mailery\Common\Entity\RoutableEntityInterface;
use Mailery\Template\Entity\Template;
use Cycle\ORM\Relation\Pivoted\PivotedCollection;
use Cycle\ORM\Relation\Pivoted\PivotedCollectionInterface;

/**
 * @Cycle\Annotated\Annotation\Entity(
 *      table = "campaigns",
 *      repository = "Mailery\Campaign\Repository\CampaignRepository",
 *      mapper = "Mailery\Campaign\Mapper\DefaultMapper"
 * )
 */
abstract class Campaign implements RoutableEntityInterface
{
    /**
     * @Cycle\Annotated\Annotation\Column(type = "primary")
     * @var int|null
     */
    protected $id;

    /**
     * @Cycle\Annotated\Annotation\Relation\BelongsTo(target = "Mailery\Brand\Entity\Brand", nullable = false)
     * @var Brand
     */
    protected $brand;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "string(255)")
     * @var string
     */
    protected $name;

    /**
     * @Cycle\Annotated\Annotation\Relation\BelongsTo(target = "Mailery\Template\Entity\Template", nullable = false)
     * @var Brand
     */
    protected $template;

    /**
     * @Cycle\Annotated\Annotation\Relation\ManyToMany(target = "Mailery\Subscriber\Entity\Group", though = "CampaignGroup", nullable = false)
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

    /**
     * {@inheritdoc}
     */
    public function getEditRouteName(): ?string
    {
        throw new RuntimeException('Must be implemented in nested.');
    }

    /**
     * {@inheritdoc}
     */
    public function getEditRouteParams(): array
    {
        throw new RuntimeException('Must be implemented in nested.');
    }

    /**
     * {@inheritdoc}
     */
    public function getViewRouteName(): ?string
    {
        throw new RuntimeException('Must be implemented in nested.');
    }

    /**
     * {@inheritdoc}
     */
    public function getViewRouteParams(): array
    {
        throw new RuntimeException('Must be implemented in nested.');
    }

    /**
     * {@inheritdoc}
     */
    public function getDeleteRouteName(): ?string
    {
        return '/campaign/default/delete';
    }

    /**
     * {@inheritdoc}
     */
    public function getDeleteRouteParams(): array
    {
        return ['id' => $this->getId()];
    }
}

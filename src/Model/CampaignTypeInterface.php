<?php

namespace Mailery\Campaign\Model;

use Mailery\Campaign\Entity\Campaign;

interface CampaignTypeInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return string
     */
    public function getCreateLabel(): string;

    /**
     * @return string|null
     */
    public function getCreateRouteName(): ?string;

    /**
     * @return array
     */
    public function getCreateRouteParams(): array;

    /**
     * @param Campaign $entity
     * @return bool
     */
    public function isEntitySameType(Campaign $entity): bool;
}

<?php

namespace Mailery\Campaign\Model;

interface CampaignTypeInterface
{
    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return string
     */
    public function getShortLabel(): string;

    /**
     * @return string|null
     */
    public function getCreateRouteName(): ?string;

    /**
     * @return array
     */
    public function getCreateRouteParams(): array;
}

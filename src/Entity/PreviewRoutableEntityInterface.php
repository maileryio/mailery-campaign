<?php

namespace Mailery\Campaign\Entity;

interface PreviewRoutableEntityInterface
{

    /**
     * @return string
     */
    public function getPreviewRouteName(): string;

    /**
     * @return array
     */
    public function getPreviewRouteParams(): array;

}

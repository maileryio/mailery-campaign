<?php

declare(strict_types=1);

namespace Mailery\Campaign\Search;

use Mailery\Widget\Search\Model\SearchBy;

class CampaignSearchBy extends SearchBy
{
    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [self::getOperator(), 'name', $this->getSearchPhrase()];
    }

    /**
     * @inheritdoc
     */
    public static function getOperator(): string
    {
        return 'like';
    }
}

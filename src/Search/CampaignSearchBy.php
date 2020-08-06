<?php

declare(strict_types=1);

namespace Mailery\Campaign\Search;

use Cycle\ORM\Select;
use Cycle\ORM\Select\QueryBuilder;
use Mailery\Widget\Search\Model\SearchBy;

class CampaignSearchBy extends SearchBy
{
    /**
     * {@inheritdoc}
     */
    protected function buildQueryInternal(Select $query, string $searchPhrase): Select
    {
        $newQuery = clone $query;

        $newQuery->andWhere(function (QueryBuilder $select) use ($searchPhrase) {
            return $select
                ->andWhere(['name' => ['like' => '%' . $searchPhrase . '%']]);
        });

        return $newQuery;
    }
}

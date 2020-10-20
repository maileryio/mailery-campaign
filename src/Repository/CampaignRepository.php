<?php

declare(strict_types=1);

namespace Mailery\Campaign\Repository;

use Cycle\ORM\Select\QueryBuilder;
use Cycle\ORM\Select\Repository;
use Mailery\Brand\Entity\Brand;
use Mailery\Campaign\Entity\Campaign;
use Yiisoft\Yii\Cycle\DataReader\SelectDataReader;
use Mailery\Campaign\Filter\CampaignFilter;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Data\Paginator\PaginatorInterface;

class CampaignRepository extends Repository
{
    /**
     * @param array $scope
     * @param array $orderBy
     * @return SelectDataReader
     */
    public function getDataReader(array $scope = [], array $orderBy = []): SelectDataReader
    {
        return new SelectDataReader($this->select()->where($scope)->orderBy($orderBy));
    }

    /**
     * @param CampaignFilter $filter
     * @return PaginatorInterface
     */
    public function getFullPaginator(CampaignFilter $filter): PaginatorInterface
    {
        $dataReader = $this->getDataReader();

        if (!$filter->isEmpty()) {
            $dataReader = $dataReader->withFilter($filter);
        }

        return new OffsetPaginator(
            $dataReader->withSort(
                (new Sort([]))->withOrder(['id' => 'DESC'])
            )
        );
    }

    /**
     * @param Brand $brand
     * @return self
     */
    public function withBrand(Brand $brand): self
    {
        $repo = clone $this;
        $repo->select
            ->andWhere([
                'brand_id' => $brand->getId(),
            ]);

        return $repo;
    }

    /**
     * @param string $name
     * @param Campaign|null $exclude
     * @return Campaign|null
     */
    public function findByName(string $name, ?Campaign $exclude = null): ?Campaign
    {
        return $this
            ->select()
            ->where(function (QueryBuilder $select) use ($name, $exclude) {
                $select->where('name', $name);

                if ($exclude !== null) {
                    $select->where('id', '<>', $exclude->getId());
                }
            })
            ->fetchOne();
    }
}

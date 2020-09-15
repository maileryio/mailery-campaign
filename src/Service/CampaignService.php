<?php

namespace Mailery\Campaign\Service;

use Mailery\Campaign\Repository\CampaignRepository;
use Mailery\Brand\Service\BrandLocator;
use Mailery\Widget\Search\Form\SearchForm;
use Mailery\Widget\Search\Model\SearchByList;
use Mailery\Campaign\Search\CampaignSearchBy;
use Yiisoft\Data\Paginator\PaginatorInterface;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Data\Reader\Filter\FilterInterface;

final class CampaignService
{
    /**
     * @var BrandLocator
     */
    private BrandLocator $brandLocator;

    /**
     * @var CampaignRepository
     */
    private CampaignRepository $campaignRepo;

    /**
     * @param BrandLocator $brandLocator
     * @param CampaignRepository $campaignRepo
     */
    public function __construct(BrandLocator $brandLocator, CampaignRepository $campaignRepo)
    {
        $this->brandLocator = $brandLocator;
        $this->campaignRepo = $campaignRepo;
    }

    /**
     * @return SearchForm
     */
    public function getSearchForm(): SearchForm
    {
        return (new SearchForm())
            ->withSearchByList(new SearchByList([
                new CampaignSearchBy(),
            ]));
    }

    /**
     * @param FilterInterface|null $filter
     * @return PaginatorInterface
     */
    public function getFullPaginator(FilterInterface $filter = null): PaginatorInterface
    {
        $dataReader = $this->campaignRepo
            ->withBrand($this->brandLocator->getBrand())
            ->getDataReader();

        if ($filter !== null) {
            $dataReader = $dataReader->withFilter($filter);
        }

        return new OffsetPaginator(
            $dataReader->withSort(
                (new Sort([]))->withOrder(['id' => 'DESC'])
            )
        );
    }
}

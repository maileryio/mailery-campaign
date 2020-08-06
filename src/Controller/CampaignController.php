<?php

declare(strict_types=1);

namespace Mailery\Campaign\Controller;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Form\CampaignForm;
use Mailery\Campaign\Repository\CampaignRepository;
use Mailery\Campaign\Search\CampaignSearchBy;
use Mailery\Campaign\Service\CampaignService;
use Mailery\Campaign\WebController;
use Mailery\Widget\Dataview\Paginator\OffsetPaginator;
use Mailery\Widget\Search\Data\Reader\Search;
use Mailery\Widget\Search\Form\SearchForm;
use Mailery\Widget\Search\Model\SearchByList;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Http\Method;
use Yiisoft\Router\UrlGeneratorInterface as UrlGenerator;

class CampaignController extends WebController
{
    private const PAGINATION_INDEX = 10;

    /**
     * @param Request $request
     * @param SearchForm $searchForm
     * @return Response
     */
    public function index(Request $request, SearchForm $searchForm): Response
    {
        $searchForm = $searchForm->withSearchByList(new SearchByList([
            new CampaignSearchBy(),
        ]));

        $queryParams = $request->getQueryParams();
        $pageNum = (int) ($queryParams['page'] ?? 1);

        $dataReader = $this->getCampaignRepository()
            ->getDataReader()
            ->withSearch((new Search())->withSearchPhrase($searchForm->getSearchPhrase())->withSearchBy($searchForm->getSearchBy()))
            ->withSort((new Sort([]))->withOrderString('name'));

        $paginator = (new OffsetPaginator($dataReader))
            ->withPageSize(self::PAGINATION_INDEX)
            ->withCurrentPage($pageNum);

        return $this->render('index', compact('searchForm', 'paginator'));
    }

    /**
     * @param Request $request
     * @param SearchForm $searchForm
     * @return Response
     */
    public function view(Request $request, SearchForm $searchForm): Response
    {
        ;
    }

    /**
     * @param Request $request
     * @param CampaignForm $campaignForm
     * @param UrlGenerator $urlGenerator
     * @return Response
     */
    public function create(Request $request, CampaignForm $campaignForm, UrlGenerator $urlGenerator): Response
    {
        ;
    }

    /**
     * @param Request $request
     * @param CampaignForm $campaignForm
     * @param UrlGenerator $urlGenerator
     * @return Response
     */
    public function edit(Request $request, CampaignForm $campaignForm, UrlGenerator $urlGenerator): Response
    {
        ;
    }

    /**
     * @param Request $request
     * @param CampaignService $campaignService
     * @param UrlGenerator $urlGenerator
     * @return Response
     */
    public function delete(Request $request, CampaignService $campaignService, UrlGenerator $urlGenerator): Response
    {
        ;
    }

    /**
     * @return CampaignRepository
     */
    private function getCampaignRepository(): CampaignRepository
    {
        return $this->getOrm()
            ->getRepository(Campaign::class)
            ->withBrand($this->getBrandLocator()->getBrand());
    }
}

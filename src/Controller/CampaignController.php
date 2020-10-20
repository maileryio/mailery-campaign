<?php

declare(strict_types=1);

namespace Mailery\Campaign\Controller;

use Mailery\Campaign\Form\CampaignForm;
use Mailery\Campaign\Repository\CampaignRepository;
use Mailery\Widget\Search\Form\SearchForm;
use Mailery\Widget\Search\Model\SearchByList;
use Mailery\Campaign\Search\CampaignSearchBy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Router\UrlGeneratorInterface as UrlGenerator;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Mailery\Campaign\Model\CampaignTypeList;
use Mailery\Campaign\Filter\CampaignFilter;

class CampaignController
{
    private const PAGINATION_INDEX = 10;

    /**
     * @var ViewRenderer
     */
    private ViewRenderer $viewRenderer;

    /**
     * @var ResponseFactory
     */
    private ResponseFactory $responseFactory;

    /**
     * @var CampaignRepository
     */
    private CampaignRepository $campaignRepo;

    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactory $responseFactory
     * @param CampaignRepository $campaignRepo
     */
    public function __construct(
        ViewRenderer $viewRenderer,
        ResponseFactory $responseFactory,
        CampaignRepository $campaignRepo
    ) {
        $this->viewRenderer = $viewRenderer
            ->withController($this)
            ->withViewBasePath(dirname(dirname(__DIR__)) . '/views')
            ->withCsrf();

        $this->responseFactory = $responseFactory;
        $this->campaignRepo = $campaignRepo;
    }

    /**
     * @param Request $request
     * @param CampaignTypeList $campaignTypes
     * @return Response
     */
    public function index(Request $request, CampaignTypeList $campaignTypes): Response
    {
        $queryParams = $request->getQueryParams();
        $pageNum = (int) ($queryParams['page'] ?? 1);
        $searchBy = $queryParams['searchBy'] ?? null;
        $searchPhrase = $queryParams['search'] ?? null;

        $searchForm = (new SearchForm())
            ->withSearchByList(new SearchByList([
                new CampaignSearchBy(),
            ]))
            ->withSearchBy($searchBy)
            ->withSearchPhrase($searchPhrase);

        $filter = (new CampaignFilter())
            ->withSearchForm($searchForm);

        $paginator = $this->campaignRepo->getFullPaginator($filter)
            ->withPageSize(self::PAGINATION_INDEX)
            ->withCurrentPage($pageNum);

        return $this->viewRenderer->render('index', compact('searchForm', 'paginator', 'campaignTypes'));
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
     * @param UrlGenerator $urlGenerator
     * @return Response
     */
    public function delete(Request $request, UrlGenerator $urlGenerator): Response
    {
        ;
    }
}

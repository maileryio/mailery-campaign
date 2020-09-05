<?php

declare(strict_types=1);

namespace Mailery\Campaign\Controller;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Form\CampaignForm;
use Mailery\Campaign\Repository\CampaignRepository;
use Mailery\Campaign\Service\CampaignService;
use Mailery\Widget\Search\Form\SearchForm;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Router\UrlGeneratorInterface as UrlGenerator;
use Mailery\Web\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;

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
            ->withCsrf();

        $this->responseFactory = $responseFactory;
        $this->campaignRepo = $campaignRepo;
    }

    /**
     * @param Request $request
     * @param CampaignService $campaignService
     * @return Response
     */
    public function index(Request $request, CampaignService $campaignService): Response
    {
        $queryParams = $request->getQueryParams();
        $pageNum = (int) ($queryParams['page'] ?? 1);
        $searchBy = $queryParams['searchBy'] ?? null;
        $searchPhrase = $queryParams['search'] ?? null;

        $searchForm = $campaignService->getSearchForm()
            ->withSearchBy($searchBy)
            ->withSearchPhrase($searchPhrase);

        $paginator = $campaignService->getFullPaginator($searchForm->getSearchBy())
            ->withPageSize(self::PAGINATION_INDEX)
            ->withCurrentPage($pageNum);

        return $this->viewRenderer->render('index', compact('searchForm', 'paginator'));
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
}

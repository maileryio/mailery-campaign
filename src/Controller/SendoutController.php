<?php

declare(strict_types=1);

namespace Mailery\Campaign\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Http\Method;
use Yiisoft\Http\Status;
use Yiisoft\Http\Header;
use Yiisoft\Router\UrlGeneratorInterface as UrlGenerator;
use Mailery\Campaign\Form\SendTestForm;
use Mailery\Campaign\Service\SendingService;
use Mailery\Campaign\Repository\CampaignRepository;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Mailery\Brand\BrandLocatorInterface;
use Yiisoft\Validator\ValidatorInterface;
use Mailery\Channel\Model\ChannelTypeList;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\DataResponse\Formatter\JsonDataResponseFormatter;

class SendoutController
{
    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactory $responseFactory
     * @param UrlGenerator $urlGenerator
     * @param DataResponseFactoryInterface $dataResponseFactory
     * @param CampaignRepository $campaignRepo
     * @param SendingService $sendingService
     * @param BrandLocatorInterface $brandLocator
     */
    public function __construct(
        private ViewRenderer $viewRenderer,
        private ResponseFactory $responseFactory,
        private UrlGenerator $urlGenerator,
        private DataResponseFactoryInterface $dataResponseFactory,
        private CampaignRepository $campaignRepo,
        private SendingService $sendingService,
        BrandLocatorInterface $brandLocator
    ) {
        $this->viewRenderer = $viewRenderer
            ->withController($this)
            ->withViewPath(dirname(dirname(__DIR__)) . '/views');

        $this->campaignRepo = $campaignRepo->withBrand($brandLocator->getBrand());
    }

    /**
     * @param Request $request
     * @param CurrentRoute $currentRoute
     * @return Response
     */
    public function create(Request $request, CurrentRoute $currentRoute): Response
    {
        $campaignId = $currentRoute->getArgument('id');
        if (empty($campaignId) || ($campaign = $this->campaignRepo->findByPK($campaignId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        if ($request->getMethod() === Method::POST) {
            $this->sendingService->sendQueue($campaign);
        }

        return $this->responseFactory
            ->createResponse(Status::SEE_OTHER)
            ->withHeader(Header::LOCATION, $_SERVER['HTTP_REFERER'] ?? $this->urlGenerator->generate($campaign->getViewRouteName(), $campaign->getViewRouteParams()));
    }

    /**
     * @param Request $request
     * @param CurrentRoute $currentRoute
     * @param ValidatorInterface $validator
     * @param SendTestForm $form
     * @param ChannelTypeList $channelTypeList
     * @return Response
     */
    public function test(Request $request, CurrentRoute $currentRoute, ValidatorInterface $validator, SendTestForm $form, ChannelTypeList $channelTypeList): Response
    {
        $campaignId = $currentRoute->getArgument('id');
        if (empty($campaignId) || ($campaign = $this->campaignRepo->findByPK($campaignId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $body = $request->getParsedBody();
        $channelType = $channelTypeList->findByEntity($campaign->getSender()->getChannel());

        $form = $form->withIdentificatorFactory($channelType->getIdentificatorFactory());

        if ($request->getMethod() === Method::POST && $form->load($body) && $validator->validate($form)->isValid()) {
            try {
                $this->sendingService->sendTest($campaign, ...$form->getIdentificators());

                $data = [
                    'success' => true,
                    'message' => 'Test message have been sent!',
                ];
            } catch (\Exception $e) {
                $sendout = $campaign->getLastTestSendout();
                $data = [
                    'success' => false,
                    'message' => $sendout?->getError() ?? 'Server error',
                ];
            }
        } else {
            $messages = $validator->validate($form)->getErrorMessages();
            $data = [
                'success' => false,
                'message' => reset($messages),
            ];
        }

        return $this->dataResponseFactory
            ->createResponse($data)
            ->withResponseFormatter(new JsonDataResponseFormatter());
    }
}

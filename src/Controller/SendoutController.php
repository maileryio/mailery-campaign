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
use Mailery\Campaign\Repository\CampaignRepository;
use Mailery\Campaign\Service\CampaignCrudService;
use Mailery\Campaign\Service\SendoutCrudService;
use Mailery\Campaign\Field\SendoutMode;
use Mailery\Campaign\ValueObject\CampaignValueObject;
use Mailery\Campaign\ValueObject\SendoutValueObject;
use Mailery\Campaign\Messenger\Message\SendCampaign;
use Mailery\Campaign\Messenger\Message\SendCampaignTest;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Mailery\Brand\BrandLocatorInterface;
use Yiisoft\Validator\ValidatorInterface;
use Mailery\Channel\Model\ChannelTypeList;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\DataResponse\Formatter\JsonDataResponseFormatter;
use Symfony\Component\Messenger\MessageBusInterface;

class SendoutController
{
    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactory $responseFactory
     * @param UrlGenerator $urlGenerator
     * @param DataResponseFactoryInterface $dataResponseFactory
     * @param CampaignRepository $campaignRepo
     * @param CampaignCrudService $campaignCrudService
     * @param SendoutCrudService $sendoutCrudService
     * @param MessageBusInterface $messageBus
     * @param BrandLocatorInterface $brandLocator
     */
    public function __construct(
        private ViewRenderer $viewRenderer,
        private ResponseFactory $responseFactory,
        private UrlGenerator $urlGenerator,
        private DataResponseFactoryInterface $dataResponseFactory,
        private CampaignRepository $campaignRepo,
        private CampaignCrudService $campaignCrudService,
        private SendoutCrudService $sendoutCrudService,
        private MessageBusInterface $messageBus,
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
            $sendout = $this->sendoutCrudService->create(
                (new SendoutValueObject())
                    ->withMode(SendoutMode::asDefault())
                    ->withCampaign($campaign)
            );

            $this->campaignCrudService->update(
                $campaign,
                CampaignValueObject::fromEntity($campaign)->asQueued()
            );

            $this->messageBus->dispatch(
                (new SendCampaign($sendout->getId()))
            );
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
     * @param LoggerInterface $logger
     * @return Response
     */
    public function test(Request $request, CurrentRoute $currentRoute, ValidatorInterface $validator, SendTestForm $form, ChannelTypeList $channelTypeList, LoggerInterface $logger): Response
    {
        $campaignId = $currentRoute->getArgument('id');
        if (empty($campaignId) || ($campaign = $this->campaignRepo->findByPK($campaignId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $body = $request->getParsedBody();
        $channelType = $channelTypeList->findByEntity($campaign->getSender()->getChannel());

        $form = $form->withIdentificatorFactory($channelType->getIdentificatorFactory());

        if ($request->getMethod() === Method::POST && $form->load($body) && $validator->validate($form)->isValid()) {
            $sendout = $this->sendoutCrudService->create(
                (new SendoutValueObject())
                    ->withMode(SendoutMode::asTest())
                    ->withCampaign($campaign)
            );

            try {
                $this->messageBus->dispatch(
                    (new SendCampaignTest($sendout->getId()))
                        ->withIdentificators(...$form->getIdentificators())
                );

                $data = [
                    'success' => true,
                    'message' => 'Test message has been sent!',
                ];
            } catch (\Exception $e) {
                $sendout = $campaign->getLastTestSendout();
                $data = [
                    'success' => false,
                    'message' => $sendout?->getError() ?? $e->getMessage(),
                ];

                $logger->error($e->getMessage(), ['exception' => $e]);
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

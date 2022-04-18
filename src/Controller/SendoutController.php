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
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Mailery\Campaign\Repository\CampaignRepository;
use Mailery\Brand\BrandLocatorInterface;
use Mailery\Campaign\Service\SendoutCrudService;
use Mailery\Campaign\ValueObject\SendoutValueObject;
use Yiisoft\Validator\ValidatorInterface;
use Mailery\Channel\Model\ChannelTypeList;
use Yiisoft\Router\CurrentRoute;
use Mailery\Campaign\Field\SendoutMode;
use Yiisoft\Session\Flash\FlashInterface;

class SendoutController
{
    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactory $responseFactory
     * @param UrlGenerator $urlGenerator
     * @param CampaignRepository $campaignRepo
     * @param SendoutCrudService $sendoutCrudService
     * @param BrandLocatorInterface $brandLocator
     */
    public function __construct(
        private ViewRenderer $viewRenderer,
        private ResponseFactory $responseFactory,
        private UrlGenerator $urlGenerator,
        private CampaignRepository $campaignRepo,
        private SendoutCrudService $sendoutCrudService,
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
     * @param ValidatorInterface $validator
     * @param FlashInterface $flash
     * @param SendTestForm $form
     * @param ChannelTypeList $channelTypeList
     * @return Response
     */
    public function test(Request $request, CurrentRoute $currentRoute, ValidatorInterface $validator, FlashInterface $flash, SendTestForm $form, ChannelTypeList $channelTypeList): Response
    {
        $campaignId = $currentRoute->getArgument('id');
        if (empty($campaignId) || ($campaign = $this->campaignRepo->findByPK($campaignId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $body = $request->getParsedBody();

        if ($request->getMethod() === Method::POST && $form->load($body) && $validator->validate($form)->isValid()) {
            $sendout = $this->sendoutCrudService->create(
                (new SendoutValueObject())
                    ->withMode(SendoutMode::asTest())
                    ->withCampaign($campaign)
            );

            $channelType = $channelTypeList->findByEntity($campaign->getChannel());
            $recipientIterator = $channelType
                ->getRecipientIterator()
                ->appendIdentificators($form->getRecipients());

            foreach ($recipientIterator as $recipient) {
                $channelType->getHandler()->handle($sendout, $recipient);
            }

            $flash->add(
                'success',
                [
                    'body' => 'Test message have been sent!',
                ],
                true
            );
        }

        return $this->responseFactory
            ->createResponse(Status::FOUND)
            ->withHeader(Header::LOCATION, $this->urlGenerator->generate($campaign->getViewRouteName(), $campaign->getViewRouteParams()));
    }
}

<?php

declare(strict_types=1);

namespace Mailery\Campaign\Controller;

use Mailery\Campaign\Entity\Recipient;
use Mailery\Campaign\Service\SecurityService;
use Mailery\Campaign\Repository\RecipientRepository;
use Mailery\Campaign\Renderer\WrappedUrlGenerator;
use Mailery\Template\Renderer\BodyRendererInterface;
use Mailery\Template\Renderer\Context;
use Mailery\Subscriber\Entity\Subscriber;
use Mailery\Subscriber\Repository\SubscriberRepository;
use Mailery\Subscriber\Service\SubscriberCrudService;
use Mailery\Subscriber\ValueObject\SubscriberValueObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Http\Status;
use Yiisoft\Router\CurrentRoute;
use Symfony\Component\Mime\Email;
use Yiisoft\Yii\View\ViewRenderer;

class GuestController
{

    public function __construct(
        private ViewRenderer $viewRenderer,
        private ResponseFactory $responseFactory,
        private SecurityService $securityService,
        private BodyRendererInterface $bodyRenderer,
        private RecipientRepository $recipientRepo,
        private SubscriberRepository $subscriberRepo,
        private SubscriberCrudService $subscriberCrudService
    ) {
        $this->viewRenderer = $viewRenderer
            ->withController($this)
            ->withViewPath(dirname(dirname(__DIR__)) . '/views')
            ->withLayout('@views/layout/guest');
    }

    /**
     * @param Request $request
     * @param CurrentRoute $currentRoute
     * @return Response
     */
    public function webversion(Request $request, CurrentRoute $currentRoute): Response
    {
        $data = $this->securityService->decrypt(
            $currentRoute->getArgument('hash')
        );

        $recipientId = $data['recipientId'] ?? null;
        if (empty($recipientId) || ($recipient = $this->recipientRepo->findByPK($recipientId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        /** @var Recipient $recipient */

        $template = $recipient->getSendout()->getCampaign()->getTemplate();

        $message = (new Email())
            ->html($template->getHtmlContent());

        $this->bodyRenderer
            ->withContext(new Context($recipient->getMessageContext()))
            ->render($message);

        $response = $this->responseFactory->createResponse();
        $response->getBody()->write($message->getHtmlBody());

        return $response;
    }

    /**
     * @param Request $request
     * @param CurrentRoute $currentRoute
     * @param WrappedUrlGenerator $wrappedUrlGenerator
     * @return Response
     */
    public function unsubscribe(Request $request, CurrentRoute $currentRoute, WrappedUrlGenerator $wrappedUrlGenerator): Response
    {
        $data = $this->securityService->decrypt(
            $currentRoute->getArgument('hash')
        );

        $subscriberId = $data['subscriberId'] ?? null;
        if (empty($subscriberId) || ($subscriber = $this->subscriberRepo->findByPK($subscriberId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        /** @var Subscriber $subscriber */

        $this->subscriberCrudService
            ->withBrand($subscriber->getBrand())
            ->update(
                $subscriber,
                SubscriberValueObject::fromEntity($subscriber)->asUnsubscribed()
            );

        $wrappedUrlGenerator = $wrappedUrlGenerator->withSubscriber($subscriber);

        return $this->viewRenderer->render('unsubscribed', compact('wrappedUrlGenerator'));
    }

    /**
     * @param Request $request
     * @param CurrentRoute $currentRoute
     * @return Response
     */
    public function subscribe(Request $request, CurrentRoute $currentRoute): Response
    {
        $data = $this->securityService->decrypt(
            $currentRoute->getArgument('hash')
        );

        $subscriberId = $data['subscriberId'] ?? null;
        if (empty($subscriberId) || ($subscriber = $this->subscriberRepo->findByPK($subscriberId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        /** @var Subscriber $subscriber */

        $this->subscriberCrudService
            ->withBrand($subscriber->getBrand())
            ->update(
                $subscriber,
                SubscriberValueObject::fromEntity($subscriber)->asUnsubscribed(false)
            );

        return $this->viewRenderer->render('subscribed');
    }

}

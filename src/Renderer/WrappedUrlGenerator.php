<?php

namespace Mailery\Campaign\Renderer;

use Mailery\Campaign\Entity\Recipient;
use Mailery\Campaign\Service\SecurityService;
use Mailery\Subscriber\Entity\Subscriber;
use Yiisoft\Router\UrlGeneratorInterface;

class WrappedUrlGenerator
{

    /**
     * @var Recipient
     */
    private Recipient $recipient;

    /**
     * @var Subscriber
     */
    private Subscriber $subscriber;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param SecurityService $securityService
     */
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private SecurityService $securityService
    ) {}

    /**
     * @param Recipient $recipient
     * @return self
     */
    public function withRecipient(Recipient $recipient): self
    {
        $new = clone $this;
        $new->recipient = $recipient;

        return $new;
    }

    /**
     * @param Subscriber $subscriber
     * @return self
     */
    public function withSubscriber(Subscriber $subscriber): self
    {
        $new = clone $this;
        $new->subscriber = $subscriber;

        return $new;
    }

    /**
     * @return string
     */
    public function getWebversion(): string
    {
        return $this->urlGenerator->generateAbsolute(
            '/campaign/guest/webversion',
            [
                'hash' => $this->securityService->encrypt([
                    'recipientId' => $this->recipient->getId(),
                ]),
            ]
        );
    }

    /**
     * @return string
     */
    public function getSubscribe(): string
    {
        if ($this->subscriber === null) {
            return '';
        }

        return $this->urlGenerator->generateAbsolute(
            '/campaign/guest/subscribe',
            [
                'hash' => $this->securityService->encrypt([
                    'subscriberId' => $this->subscriber->getId(),
                ]),
            ]
        );
    }

    /**
     * @return string
     */
    public function getUnsubscribe(): string
    {
        if ($this->subscriber === null) {
            return '';
        }

        return $this->urlGenerator->generateAbsolute(
            '/campaign/guest/unsubscribe',
            [
                'hash' => $this->securityService->encrypt([
                    'subscriberId' => $this->subscriber->getId(),
                ]),
            ]
        );
    }

}

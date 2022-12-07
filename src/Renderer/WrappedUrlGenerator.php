<?php

namespace Mailery\Campaign\Renderer;

use Mailery\Campaign\Entity\Recipient;
use Mailery\Security\Security;
use Mailery\Subscriber\Entity\Subscriber;
use Yiisoft\Router\UrlGeneratorInterface;

class WrappedUrlGenerator
{

    /**
     * @var Recipient|null
     */
    private ?Recipient $recipient = null;

    /**
     * @var Subscriber|null
     */
    private ?Subscriber $subscriber = null;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param Security $security
     */
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private Security $security
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
     * @return string|null
     */
    public function getWebversion(): ?string
    {
        if ($this->recipient === null) {
            return null;
        }

        return $this->urlGenerator->generateAbsolute(
            '/campaign/guest/webversion',
            [
                'hash' => $this->security->encrypt([
                    'recipientId' => $this->recipient->getId(),
                ]),
            ]
        );
    }

    /**
     * @return string|null
     */
    public function getSubscribe(): ?string
    {
        if ($this->subscriber === null) {
            return null;
        }

        return $this->urlGenerator->generateAbsolute(
            '/campaign/guest/subscribe',
            [
                'hash' => $this->security->encrypt([
                    'subscriberId' => $this->subscriber->getId(),
                ]),
            ]
        );
    }

    /**
     * @return string|null
     */
    public function getUnsubscribe(): ?string
    {
        if ($this->subscriber === null) {
            return null;
        }

        return $this->urlGenerator->generateAbsolute(
            '/campaign/guest/unsubscribe',
            [
                'hash' => $this->security->encrypt([
                    'subscriberId' => $this->subscriber->getId(),
                ]),
            ]
        );
    }

}

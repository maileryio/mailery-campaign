<?php

namespace Mailery\Campaign\Recipient\Factory;

use Mailery\Campaign\Entity\Recipient;
use Mailery\Campaign\Recipient\Factory\RecipientFactoryInterface;
use Mailery\Campaign\Recipient\Model\IdentificatorInterface as Identificator;
use Mailery\Subscriber\Entity\Subscriber;

class RecipientFactory implements RecipientFactoryInterface
{

    /**
     * @inheritdoc
     */
    public function fromSubscriber(Subscriber $subscriber): Recipient
    {
        return (new Recipient())
            ->setSubscriber($subscriber)
            ->setName($subscriber->getName())
            ->setIdentifier($subscriber->getEmail());
    }

    /**
     * @inheritdoc
     */
    public function fromIdentificator(Identificator $identificator): Recipient
    {
        return (new Recipient())
            ->setName($identificator->getName())
            ->setIdentifier($identificator->getIdentificator());
    }

}

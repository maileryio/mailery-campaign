<?php

namespace Mailery\Campaign\Recipient\Factory;

use Mailery\Campaign\Entity\Recipient;
use Mailery\Campaign\Recipient\Model\IdentificatorInterface as Identificator;
use Mailery\Subscriber\Entity\Subscriber;

interface RecipientFactoryInterface
{

    /**
     * @param Subscriber $subscriber
     * @return Recipient
     */
    public function fromSubscriber(Subscriber $subscriber): Recipient;

    /**
     * @param Identificator $identificator
     * @return Recipient
     */
    public function fromIdentificator(Identificator $identificator): Recipient;

}

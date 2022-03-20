<?php

namespace Mailery\Campaign\Recipient\Model;

use Mailery\Subscriber\Entity\Group;
use Mailery\Subscriber\Entity\Subscriber;
use Mailery\Channel\Factory\RecipientFactoryInterface;
use Mailery\Campaign\Recipient\Model\CallableIterator;

class RecipientIterator extends \AppendIterator
{

    /**
     * @param RecipientFactoryInterface $recipientFactory
     * @return self
     */
    public function __construct(
        private RecipientFactoryInterface $recipientFactory
    ) {
        parent::__construct();
    }

    /**
     * @param Group $groups
     * @return self
     */
    public function appendGroups(Group ...$groups): self
    {
        foreach ($groups as $group) {
            $this->append(new CallableIterator(
                $group->getSubscribers(),
                function (Subscriber $subscriber) {
                    return $this->recipientFactory->fromSubscriber($subscriber);
                }
            ));
        }

        return $this;
    }

    /**
     * @param Subscriber $subscribers
     * @return self
     */
    public function appendSubscribers(Subscriber ...$subscribers): self
    {
        $iterator = new \ArrayIterator();
        foreach ($subscribers as $subscriber) {
            $iterator->append($this->recipientFactory->fromSubscriber($subscriber));
        }

        $this->append($iterator);

        return $this;
    }

    /**
     * @param string $identificators
     * @return self
     */
    public function appendIdentificators(string ...$identificators): self
    {
        $iterator = new \ArrayIterator();
        foreach ($identificators as $identificator) {
            foreach ($this->recipientFactory->fromIdentificator($identificator) as $recipient) {
                $iterator->append($recipient);
            }
        }

        $this->append($iterator);

        return $this;
    }

}

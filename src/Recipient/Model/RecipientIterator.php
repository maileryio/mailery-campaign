<?php

namespace Mailery\Campaign\Recipient\Model;

use Mailery\Subscriber\Entity\Group;
use Mailery\Subscriber\Entity\Subscriber;
use Mailery\Sender\Email\Model\SenderLabel;
use Mailery\Campaign\Entity\Recipient;

class RecipientIterator extends \AppendIterator
{
    /**
     * @param Group $groups
     * @return self
     */
    public function appendGroups(Group ...$groups): self
    {
//       foreach ($groups as $group) {
//           $this->append(new \CallbackFilterIterator(
//               $group->getSubscribers(),
//               function (Subscriber $subscriber) {
//                   return (new Recipient())
//                        ->setSubscriber($subscriber)
//                        ->setName($subscriber->getName())
//                        ->setIdentifier($subscriber->getEmail());
//               }
//           ));
//       }

       return $this;
    }

    /**
     * @param Subscriber $subscribers
     * @return self
     */
    public function appendSubscribers(Subscriber ...$subscribers): self
    {
       $recipients = [];
       foreach ($subscribers as $subscriber) {
           $recipients[] = (new Recipient())
                ->setSubscriber($subscriber)
                ->setName($subscriber->getName())
                ->setIdentifier($subscriber->getEmail());
       }

       $this->append(new \ArrayIterator($recipients));

       return $this;
    }

    /**
     * @param string $identificators
     * @return self
     */
    public function appendIdentificators(string ...$identificators): self
    {
       $recipients = [];
       foreach ($identificators as $identificator) {
           foreach (SenderLabel::fromString($identificator) as $senderLabel) {
               /** @var SenderLabel $senderLabel */
               $recipients[] = (new Recipient())
                    ->setName($senderLabel->getName())
                    ->setIdentifier($senderLabel->getEmail());
           }
       }

       $this->append(new \ArrayIterator($recipients));

       return $this;
    }
}

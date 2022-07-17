<?php

namespace Mailery\Campaign\Counter;

use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Repository\RecipientRepository;

class RecipientCounter
{

    public function __construct(
        private RecipientRepository $repo
    ) {}

    /**
     * @param Sendout $sendout
     * @return self
     */
    public function withSendout(Sendout $sendout): self
    {
        $new = clone $this;
        $new->repo = $new->repo->withSendout($sendout);

        return $new;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->repo
            ->select()
            ->count();
    }

    /**
     * @return int
     */
    public function getSentCount(): int
    {
        return $this->repo
            ->asSent(true)
            ->select()
            ->count();
    }

}

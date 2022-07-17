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

    /**
     * @return int
     */
    public function getOpenedCount(): int
    {
        return $this->repo
            ->asOpened(true)
            ->select()
            ->count();
    }

    /**
     * @return int
     */
    public function getClickedCount(): int
    {
        return $this->repo
            ->asClicked(true)
            ->select()
            ->count();
    }

    /**
     * @return int
     */
    public function getUnsubscribedCount(): int
    {
        return $this->repo
            ->asUnsubscribed(true)
            ->select()
            ->count();
    }

    /**
     * @return int
     */
    public function getBouncedCount(): int
    {
        return $this->repo
            ->asBounced(true)
            ->select()
            ->count();
    }

    /**
     * @return int
     */
    public function getComplainedCount(): int
    {
        return $this->repo
            ->asComplained(true)
            ->select()
            ->count();
    }

}

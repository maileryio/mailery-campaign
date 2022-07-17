<?php

declare(strict_types=1);

namespace Mailery\Campaign\Repository;

use Cycle\ORM\Select\Repository;
use Mailery\Campaign\Entity\Sendout;

class RecipientRepository extends Repository
{

    /**
     * @param Sendout $sendout
     * @return self
     */
    public function withSendout(Sendout $sendout): self
    {
        $repo = clone $this;
        $repo->select
            ->andWhere([
                'sendout_id' => $sendout->getId(),
            ]);

        return $repo;
    }

    /**
     * @param bool $sent
     * @return self
     */
    public function asSent(bool $sent): self
    {
        $repo = clone $this;
        $repo->select
            ->andWhere([
                'sent' => $sent,
            ]);

        return $repo;
    }

    /**
     * @param bool $opened
     * @return self
     */
    public function asOpened(bool $opened): self
    {
        $repo = clone $this;
        $repo->select
            ->andWhere([
                'opened' => $opened,
            ]);

        return $repo;
    }

    /**
     * @param bool $clicked
     * @return self
     */
    public function asClicked(bool $clicked): self
    {
        $repo = clone $this;
        $repo->select
            ->andWhere([
                'clicked' => $clicked,
            ]);

        return $repo;
    }

    /**
     * @param bool $unsubscribed
     * @return self
     */
    public function asUnsubscribed(bool $unsubscribed): self
    {
        $repo = clone $this;
        $repo->select
            ->andWhere([
                'unsubscribed' => $unsubscribed,
            ]);

        return $repo;
    }

    /**
     * @param bool $bounced
     * @return self
     */
    public function asBounced(bool $bounced): self
    {
        $repo = clone $this;
        $repo->select
            ->andWhere([
                'bounced' => $bounced,
            ]);

        return $repo;
    }

    /**
     * @param bool $complained
     * @return self
     */
    public function asComplained(bool $complained): self
    {
        $repo = clone $this;
        $repo->select
            ->andWhere([
                'complained' => $complained,
            ]);

        return $repo;
    }

}

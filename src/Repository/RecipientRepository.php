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

}

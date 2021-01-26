<?php

namespace Mailery\Campaign\Service;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Transaction;
use Mailery\Campaign\Entity\Campaign;

class CampaignCrudService
{
    /**
     * @var ORMInterface
     */
    private ORMInterface $orm;

    /**
     * @param ORMInterface $orm
     */
    public function __construct(ORMInterface $orm)
    {
        $this->orm = $orm;
    }

    /**
     * @param Campaign $campaign
     * @return bool
     */
    public function delete(Campaign $campaign): bool
    {
        $tr = new Transaction($this->orm);
        $tr->delete($campaign);
        $tr->run();

        return true;
    }
}

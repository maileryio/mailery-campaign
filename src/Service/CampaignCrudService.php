<?php

namespace Mailery\Campaign\Service;

use Cycle\ORM\ORMInterface;
use Mailery\Campaign\Entity\Campaign;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

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
        (new EntityWriter($this->orm))->delete([$campaign]);

        return true;
    }
}

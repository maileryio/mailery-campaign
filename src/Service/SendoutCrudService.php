<?php

namespace Mailery\Campaign\Service;

use Cycle\ORM\ORMInterface;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Model\SendoutStatus;
use Mailery\Campaign\ValueObject\SendoutValueObject;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class SendoutCrudService
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
     * @param SendoutValueObject $valueObject
     * @return Sendout
     */
    public function create(SendoutValueObject $valueObject): Sendout
    {
        $sendout = (new Sendout())
            ->setStatus(SendoutStatus::CREATED)
            ->setIsTest($valueObject->getIsTest())
            ->setCampaign($valueObject->getCampagn())
        ;

        (new EntityWriter($this->orm))->write([$sendout]);

        return $sendout;
    }
}

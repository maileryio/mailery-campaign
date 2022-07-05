<?php

namespace Mailery\Campaign\Service;

use Cycle\ORM\ORMInterface;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Field\SendoutStatus;
use Mailery\Campaign\ValueObject\SendoutValueObject;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class SendoutCrudService
{
    /**
     * @param ORMInterface $orm
     */
    public function __construct(
        private ORMInterface $orm
    ) {}

    /**
     * @param SendoutValueObject $valueObject
     * @return Sendout
     */
    public function create(SendoutValueObject $valueObject): Sendout
    {
        $sendout = (new Sendout())
            ->setMode($valueObject->getMode())
            ->setStatus(SendoutStatus::asCreated())
            ->setCampaign($valueObject->getCampagn())
        ;

        (new EntityWriter($this->orm))->write([$sendout]);

        return $sendout;
    }

    /**
     * @param Sendout $sendout
     * @param SendoutValueObject $valueObject
     * @return Sendout
     */
    public function update(Sendout $sendout, SendoutValueObject $valueObject): Sendout
    {
        $sendout = $sendout
            ->setStatus($valueObject->getStatus())
        ;

        (new EntityWriter($this->orm))->write([$sendout]);

        return $sendout;
    }
}

<?php

namespace Mailery\Campaign\Service;

use Cycle\ORM\EntityManagerInterface;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Field\SendoutStatus;
use Mailery\Campaign\ValueObject\SendoutValueObject;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class SendoutCrudService
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
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

        (new EntityWriter($this->entityManager))->write([$sendout]);

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

        (new EntityWriter($this->entityManager))->write([$sendout]);

        return $sendout;
    }
}

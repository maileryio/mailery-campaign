<?php

namespace Mailery\Campaign\Service;

use Cycle\ORM\ORMInterface;
use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Schedule;
use Mailery\Campaign\ValueObject\ScheduleValueObject;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class ScheduleCrudService
{

    /**
     * @param ORMInterface $orm
     */
    public function __construct(
        private ORMInterface $orm
    ) {}


    /**
     * @param Campaign $campaign
     * @param ScheduleValueObject $valueObject
     * @return Campaign
     */
    public function update(Campaign $campaign, ScheduleValueObject $valueObject): Campaign
    {
        if (($schedule = $campaign->getSchedule()) === null) {
            $schedule = (new Schedule())
                ->setCampaign($campaign)
            ;
        }

        $campaign->setSendingType($valueObject->getSendingType());

        $schedule = $schedule
            ->setDatetime($valueObject->getDatetime())
            ->setTimezone($valueObject->getTimezone())
        ;

        (new EntityWriter($this->orm))->write([$campaign, $schedule]);

        return $campaign;
    }

}

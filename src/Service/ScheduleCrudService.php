<?php

namespace Mailery\Campaign\Service;

use Cycle\ORM\ORMInterface;
use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Schedule;
use Mailery\Campaign\Field\CampaignStatus;
use Mailery\Campaign\Field\SendingType;
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
        $campaign->setSendingType($valueObject->getSendingType());

        if ($campaign->getSendingType()->isScheduled()) {
            if (($schedule = $campaign->getSchedule()) === null) {
                $schedule = (new Schedule())
                    ->setCampaign($campaign)
                ;
            }

            $schedule = $schedule
                ->setDatetime($valueObject->getDatetime())
                ->setTimezone($valueObject->getTimezone())
            ;

            $campaign->setStatus(CampaignStatus::asScheduled());
            $campaign->setSchedule($schedule);

            (new EntityWriter($this->orm))->write([$campaign, $schedule]);
        } else if(($schedule = $campaign->getSchedule()) !== null) {
            $campaign->setSchedule(null);
            (new EntityWriter($this->orm))->write([$campaign]);
            (new EntityWriter($this->orm))->delete([$schedule]);
        }

        return $campaign;
    }

    public function delete(Campaign $campaign): bool
    {
        $schedule = $campaign->getSchedule();

        $campaign->setSchedule(null);
        $campaign->setStatus(CampaignStatus::asDraft());
        $campaign->setSendingType(SendingType::asInstant());

        (new EntityWriter($this->orm))->write([$campaign]);

        if ($schedule !== null) {
            (new EntityWriter($this->orm))->delete([$schedule]);
        }

        return true;
    }

}

<?php

namespace Mailery\Campaign\Service;

use Cycle\ORM\EntityManagerInterface;
use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Schedule;
use Mailery\Campaign\ValueObject\ScheduleValueObject;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class ScheduleCrudService
{

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
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

            $campaign->setStatus($campaign->getStatus()->asScheduled());
            $campaign->setSchedule($schedule);

            (new EntityWriter($this->entityManager))->write([$campaign, $schedule]);
        } else if(($schedule = $campaign->getSchedule()) !== null) {
            $campaign->setSchedule(null);
            $this->entityManager->persist($campaign);
            $this->entityManager->delete($schedule);
            $this->entityManager->run();
        }

        return $campaign;
    }

    public function delete(Campaign $campaign): bool
    {
        $schedule = $campaign->getSchedule();

        $campaign->setSchedule(null);
        $campaign->setStatus($campaign->getStatus()->asDraft());
        $campaign->setSendingType($campaign->getSendingType()->asInstant());

        (new EntityWriter($this->entityManager))->write([$campaign]);

        if ($schedule !== null) {
            (new EntityWriter($this->entityManager))->delete([$schedule]);
        }

        return true;
    }

}

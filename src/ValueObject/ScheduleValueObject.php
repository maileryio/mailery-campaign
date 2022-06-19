<?php

namespace Mailery\Campaign\ValueObject;

use Mailery\Common\Field\Timezone;
use Mailery\Campaign\Form\ScheduleForm;
use Mailery\Campaign\Field\SendingType;

class ScheduleValueObject
{

    /**
     * @var \DateTimeImmutable
     */
    private \DateTimeImmutable $datetime;

    /**
     * @var Timezone
     */
    private Timezone $timezone;

     /**
     * @var SendingType
     */
    private SendingType $sendingType;

    /**
     * @param ScheduleForm $form
     * @return self
     */
    public static function fromForm(ScheduleForm $form): self
    {
        $new = new self();
        $new->sendingType = $form->getSendingType();
        $new->datetime = $form->getDatetime();
        $new->timezone = $form->getTimezone();

        return $new;
    }

    /**
     * @return SendingType
     */
    public function getSendingType(): SendingType
    {
        return $this->sendingType;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDatetime(): ?\DateTimeImmutable
    {
        return $this->datetime;
    }

    /**
     * @return Timezone
     */
    public function getTimezone(): Timezone
    {
        return $this->timezone;
    }

}

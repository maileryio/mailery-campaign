<?php

namespace Mailery\Campaign\ValueObject;

use Mailery\Campaign\Form\ScheduleForm;

class ScheduleValueObject
{

    /**
     * @param ScheduleForm $form
     * @return self
     */
    public static function fromForm(ScheduleForm $form): self
    {
        $new = new self();

        return $new;
    }

}

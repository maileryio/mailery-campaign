<?php

namespace Mailery\Campaign\ValueObject;

use Mailery\Campaign\Field\UtmTags;
use Mailery\Campaign\Form\TrackingForm;

class TrackingValueObject
{

    /**
     * @var bool
     */
    private bool $trackClicks;

    /**
     * @var bool
     */
    private bool $trackOpens;

    /**
     * @var bool
     */
    private bool $enableUtmTags;

    /**
     * @var UtmTags|null
     */
    private ?UtmTags $utmTags;

    /**
     * @param TrackingForm $form
     * @return self
     */
    public static function fromForm(TrackingForm $form): self
    {
        $new = new self();
        $new->trackClicks = $form->getTrackClicks();
        $new->trackOpens = $form->getTrackOpens();
        $new->enableUtmTags = $form->getEnableUtmTags();
        $new->utmTags = $form->getUtmTags();

        return $new;
    }

    /**
     * @return bool
     */
    public function getTrackClicks(): bool
    {
        return $this->trackClicks;
    }

    /**
     * @return bool
     */
    public function getTrackOpens(): bool
    {
        return $this->trackOpens;
    }

    /**
     * @return bool
     */
    public function getEnableUtmTags(): bool
    {
        return $this->enableUtmTags;
    }

    /**
     * @return UtmTags|null
     */
    public function getUtmTags(): ?UtmTags
    {
        return $this->utmTags;
    }

}

<?php

namespace Mailery\Campaign\Form;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Field\SendingType;
use Mailery\Common\Model\Timezones;
use Mailery\User\Service\CurrentUserService;
use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\InRange;

class ScheduleForm extends FormModel
{

    /**
     * @var \DateTimeImmutable
     */
    private string $datetime;

    /**
     * @var string
     */
    private string $timezone;

     /**
     * @var SendingType
     */
    private SendingType $sendingType;

    /**
     * @var Campaign|null
     */
    private ?Campaign $entity = null;

    /**
     * @param CurrentUserService $currentUser
     */
    public function __construct(CurrentUserService $currentUser)
    {
        $this->timezone = $currentUser->getUser()->getTimezone();
        $this->sendingType = SendingType::asInstant();

        parent::__construct();
    }

    /**
     * @param Campaign $entity
     * @return self
     */
    public function withEntity(Campaign $entity): self
    {
        $schedule = $entity->getSchedule();

        $new = clone $this;
        $new->entity = $entity;
        $new->datetime = $schedule?->getDatetime();
        $new->timezone = $schedule?->getTimezone();
        $new->sendingType = $entity->getSendingType();

        return $new;
    }

    /**
     * @return bool
     */
    public function hasEntity(): bool
    {
        return $this->entity !== null;
    }

    /**
     * @inheritdoc
     */
    public function load(array $data, ?string $formName = null): bool
    {
        $scope = $formName ?? $this->getFormName();

        if (isset($data[$scope]['sendingType'])) {
            $data[$scope]['sendingType'] = SendingType::typecast($data[$scope]['sendingType']);
        }

        return parent::load($data, $formName);
    }

    /**
     * @return SendingType
     */
    public function getSendingType(): SendingType
    {
        return $this->sendingType;
    }

    /**
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * @return array
     */
    public function getAttributeLabels(): array
    {
        return [
            'sendingType' => 'Sending type',
            'datetime' => 'Datetime',
            'timezone' => 'Timezone',
        ];
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            'sendingType' => [
                Required::rule(),
                InRange::rule(array_keys($this->getSendingTypeListOptions())),
            ],
            'datetime' => [
                Required::rule(),
            ],
            'timezone' => [
                Required::rule(),
                InRange::rule(array_keys($this->getTimezoneListOptions())),
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSendingTypeListOptions(): array
    {
        $instant = SendingType::asInstant();
        $scheduled = SendingType::asScheduled();

        return [
            $instant->getValue() => $instant->getLabel(),
            $scheduled->getValue() => $scheduled->getLabel(),
        ];
    }

    /**
     * @return array
     */
    public function getTimezoneListOptions(): array
    {
        return (new Timezones())->getAll();
    }

}

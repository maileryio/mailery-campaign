<?php

namespace Mailery\Campaign\Form;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Field\SendingType;
use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;

class ScheduleForm extends FormModel
{

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
     * @inheritdoc
     */
    public function __construct()
    {
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
        $new->timezone = $schedule?->getTimezone() ?? $new->timezone;
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
            'timezone' => 'Timezone',
        ];
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            'timezone' => [
                Required::rule(),
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

}

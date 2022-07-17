<?php declare(strict_types=1);

namespace Mailery\Campaign\Form;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Field\SendingType;
use Mailery\Common\Field\Timezone;
use Mailery\Common\Model\Timezones;
use Mailery\User\Service\CurrentUserService;
use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Callback;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\InRange;

class ScheduleForm extends FormModel
{

    private const DATE_FORMAT = 'Y-m-d';
    private const TIME_FORMAT = 'H:i';

    /**
     * @var string|null
     */
    private ?string $date = null;

    /**
     * @var string|null
     */
    private ?string $time = null;

    /**
     * @var string
     */
    private string $country;

    /**
     * @var string
     */
    private string $timezone;

     /**
     * @var string
     */
    private string $sendingType;

    /**
     * @var Campaign|null
     */
    private ?Campaign $entity = null;

    /**
     * @param CurrentUserService $currentUser
     */
    public function __construct(CurrentUserService $currentUser)
    {
        $this->country = $currentUser->getUser()->getCountry()->getValue();
        $this->timezone = $currentUser->getUser()->getTimezone()->getValue();
        $this->sendingType = SendingType::asInstant()->getValue();

        $datetime = (new \DateTimeImmutable('now'))
            ->setTimezone($this->getDateTimeZone())
            ->modify('+1 hour');

        $this->date = $datetime->format(self::DATE_FORMAT);
        $this->time = $datetime->format(self::TIME_FORMAT);

        parent::__construct();
    }

    /**
     * @param Campaign $entity
     * @return self
     */
    public function withEntity(Campaign $entity): self
    {
        $new = clone $this;
        $new->entity = $entity;
        $new->sendingType = $entity->getSendingType()->getValue();

        if (($schedule = $entity->getSchedule()) !== null) {
            $new->timezone = $schedule->getTimezone()->getValue();

            $datetime = $schedule->getDatetime()->setTimezone($new->getDateTimeZone());

            $new->date = $datetime->format(self::DATE_FORMAT);
            $new->time = $datetime->format(self::TIME_FORMAT);

        }

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
    public function load(array|object|null $data, ?string $formName = null): bool
    {
        $scope = $formName ?? $this->getFormName();

        if (isset($data[$scope]['sendingType'])) {
            $data[$scope]['sendingType'] = SendingType::typecast($data[$scope]['sendingType'])->getValue();
        }

        return parent::load($data, $formName);
    }

    /**
     * @return SendingType
     */
    public function getSendingType(): SendingType
    {
        return SendingType::typecast($this->sendingType);
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDatetime(): ?\DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat(
            implode(' ', [self::DATE_FORMAT, self::TIME_FORMAT]),
            implode(' ', [$this->date, $this->time]),
            $this->getDateTimeZone()
        );
    }

    /**
     * @return \DateTimeZone
     */
    public function getDateTimeZone(): \DateTimeZone
    {
        return new \DateTimeZone($this->getTimezone()->getValue());
    }

    /**
     * @return Timezone
     */
    public function getTimezone(): Timezone
    {
        return Timezone::typecast($this->timezone);
    }

    /**
     * @return array
     */
    public function getAttributeLabels(): array
    {
        return [
            'sendingType' => 'Sending type',
            'date' => 'Date',
            'time' => 'Send time',
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
            'date' => [
                Required::rule(),
                Callback::rule(function (string $value) {
                    $result = new Result();
                    $date = \DateTime::createFromFormat(self::DATE_FORMAT, $value, $this->getDateTimeZone());

                    if (!$date || $date->format(self::DATE_FORMAT) !== $value) {
                        $result->addError('Invalid date value.');
                    }
                    return $result;
                }),
                Callback::rule(function () {
                    $result = new Result();
                    $date = $this->getDatetime();
                    $now = (new \DateTimeImmutable())->setTimezone($this->getDateTimeZone());

                    if ($date < $now) {
                        $result->addError('Date cannot be in the past.', ['date']);
                    }

                    return $result;
                }),
            ],
            'time' => [
                Required::rule(),
                Callback::rule(function (string $value) {
                    $result = new Result();
                    $date = \DateTime::createFromFormat(self::TIME_FORMAT, $value, $this->getDateTimeZone());

                    if (!$date || $date->format(self::TIME_FORMAT) !== $value) {
                        $result->addError('Invalid time value.');
                    }
                    return $result;
                }),
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
        return (new Timezones())
            ->withOffset(true)
            ->withNearestBy($this->country)
            ->getAll();
    }

}

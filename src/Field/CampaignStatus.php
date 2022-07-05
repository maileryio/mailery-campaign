<?php

namespace Mailery\Campaign\Field;

use Yiisoft\Translator\TranslatorInterface;

class CampaignStatus
{
    private const DRAFT = 'draft';
    private const SCHEDULED = 'scheduled';
    private const QUEUED = 'queued';
    private const SENDING = 'sending';
    private const SENT = 'sent';
    private const ERRORED = 'errored';

    /**
     * @var TranslatorInterface|null
     */
    private ?TranslatorInterface $translator = null;

    /**
     * @param string $value
     */
    private function __construct(
        private string $value
    ) {
        if (!in_array($value, $this->getValues())) {
            throw new \InvalidArgumentException('Invalid passed value: ' . $value);
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return static
     */
    public static function typecast(string $value): static
    {
        return new static($value);
    }

    /**
     * @return self
     */
    public static function asDraft(): self
    {
        return new self(self::DRAFT);
    }

    /**
     * @return self
     */
    public static function asScheduled(): self
    {
        return new self(self::SCHEDULED);
    }

    /**
     * @return self
     */
    public static function asQueued(): self
    {
        return new self(self::QUEUED);
    }

    /**
     * @return self
     */
    public static function asSending(): self
    {
        return new self(self::SENDING);
    }

    /**
     * @return self
     */
    public static function asSent(): self
    {
        return new self(self::SENT);
    }

    /**
     * @return self
     */
    public static function asErrored(): self
    {
        return new self(self::ERRORED);
    }

    /**
     * @param TranslatorInterface $translator
     * @return self
     */
    public function withTranslator(TranslatorInterface $translator): self
    {
        $new = clone $this;
        $new->translator = $translator;

        return $new;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return [
            self::DRAFT,
            self::SCHEDULED,
            self::QUEUED,
            self::SENDING,
            self::SENT,
            self::ERRORED,
        ];
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        $fnTranslate = function (string $message) {
            if ($this->translator !== null) {
                return $this->translator->translate($message);
            }
            return $message;
        };

        return [
            self::DRAFT => $fnTranslate('Draft'),
            self::SCHEDULED => $fnTranslate('Scheduled'),
            self::QUEUED => $fnTranslate('Queued'),
            self::SENDING => $fnTranslate('Sending'),
            self::SENT => $fnTranslate('Sent'),
            self::ERRORED => $fnTranslate('Errored'),
        ][$this->value] ?? 'Unknown';
    }

    /**
     * @return string
     */
    public function getCssClass(): string
    {
        return [
            self::DRAFT => 'badge-secondary',
            self::SCHEDULED => 'badge-warning',
            self::QUEUED => 'badge-info',
            self::SENDING => 'badge-info',
            self::SENT => 'badge-success',
            self::ERRORED => 'badge-danger',
        ][$this->value] ?? 'badge-secondary';
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->getValue() === self::DRAFT;
    }

    /**
     * @return bool
     */
    public function isScheduled(): bool
    {
        return $this->getValue() === self::SCHEDULED;
    }

    /**
     * @return bool
     */
    public function isQueued(): bool
    {
        return $this->getValue() === self::QUEUED;
    }

    /**
     * @return bool
     */
    public function isSending(): bool
    {
        return $this->getValue() === self::SENDING;
    }

    /**
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->getValue() === self::SENT;
    }

    /**
     * @return bool
     */
    public function isErrored(): bool
    {
        return $this->getValue() === self::ERRORED;
    }
}

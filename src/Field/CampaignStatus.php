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

    /**
     * @var TranslatorInterface|null
     */
    private ?TranslatorInterface $translator = null;

    /**
     * @param string $value
     */
    public function __construct(
        private string $value
    ) {}

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
}

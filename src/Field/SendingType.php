<?php

namespace Mailery\Campaign\Field;

use Yiisoft\Translator\TranslatorInterface;

class SendingType
{

    private const INSTANT = 'instant';
    private const SCHEDULED = 'scheduled';

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
        if (!in_array($value, self::getKeys())) {
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
     * @return array
     */
    public static function getKeys(): array
    {
        return [
            self::INSTANT,
            self::SCHEDULED,
        ];
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
    public static function asInstant(): self
    {
        return new self(self::INSTANT);
    }

    /**
     * @return self
     */
    public static function asScheduled(): self
    {
        return new self(self::SCHEDULED);
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
            self::INSTANT => $fnTranslate('Send immediately'),
            self::SCHEDULED => $fnTranslate('Send later'),
        ][$this->value] ?? 'Unknown';
    }

    /**
     * @return string
     */
    public function getCssClass(): string
    {
        return [
            self::INSTANT => 'badge-secondary',
            self::SCHEDULED => 'badge-warning',
        ][$this->value] ?? 'badge-secondary';
    }

    /**
     * @return bool
     */
    public function isInstant(): bool
    {
        return $this->getValue() === self::INSTANT;
    }

    /**
     * @return bool
     */
    public function isScheduled(): bool
    {
        return $this->getValue() === self::SCHEDULED;
    }

}

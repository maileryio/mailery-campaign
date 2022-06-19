<?php

namespace Mailery\Campaign\Field;

use Yiisoft\Translator\TranslatorInterface;

class SendoutStatus
{
    private const CREATED = 'created';
    private const PENDING = 'pending';
    private const FINISHED = 'finished';

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
    public static function asCreated(): self
    {
        return new self(self::CREATED);
    }

    /**
     * @return self
     */
    public static function asPending(): self
    {
        return new self(self::PENDING);
    }

    /**
     * @return self
     */
    public static function asFinished(): self
    {
        return new self(self::FINISHED);
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
            self::CREATED,
            self::PENDING,
            self::FINISHED,
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
            self::CREATED => $fnTranslate('Created'),
            self::PENDING => $fnTranslate('Pending'),
            self::FINISHED => $fnTranslate('Finished'),
        ][$this->value] ?? 'Unknown';
    }

    /**
     * @return string
     */
    public function getCssClass(): string
    {
        return [
            self::CREATED => 'badge-warning',
            self::PENDING => 'badge-info',
            self::FINISHED => 'badge-success',
        ][$this->value] ?? 'badge-secondary';
    }

    /**
     * @return bool
     */
    public function isCreated(): bool
    {
        return $this->getValue() === self::CREATED;
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->getValue() === self::PENDING;
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->getValue() === self::FINISHED;
    }
}

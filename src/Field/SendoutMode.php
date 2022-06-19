<?php

namespace Mailery\Campaign\Field;

use Yiisoft\Translator\TranslatorInterface;

class SendoutMode
{
    private const NORMAL = 'normal';
    private const TEST = 'test';

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
    public static function asNormal(): self
    {
        return new self(self::NORMAL);
    }

    /**
     * @return self
     */
    public static function asTest(): self
    {
        return new self(self::TEST);
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
            self::NORMAL,
            self::TEST,
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
            self::NORMAL => $fnTranslate('Normal'),
            self::TEST => $fnTranslate('Test'),
        ][$this->value] ?? 'Unknown';
    }

    /**
     * @return bool
     */
    public function isNormal(): bool
    {
        return $this->getValue() === self::NORMAL;
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return $this->getValue() === self::TEST;
    }
}

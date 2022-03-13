<?php

namespace Mailery\Campaign\Model;

use InvalidArgumentException;
use Mailery\Campaign\Entity\Sendout;
use Yiisoft\Translator\TranslatorInterface;

class SendoutStatus
{
    public const CREATED = 'created';
    public const PENDING = 'pending';
    public const FINISHED = 'finished';

    /**
     * @var TranslatorInterface|null
     */
    private ?TranslatorInterface $translator = null;

    /**
     * @param string $value
     */
    public function __construct(
        private string $value
    ) {
        if (!isset($this->getLabels()[$value])) {
            throw new InvalidArgumentException();
        }

        $this->value = $value;
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
     * @param Sendout $entity
     * @return self
     */
    public static function fromEntity(Sendout $entity): self
    {
        return new self($entity->getStatus());
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
        return $this->getLabels()[$this->value] ?? '';
    }

    /**
     * @return array
     */
    public function getLabels(): array
    {
        return [
            self::CREATED => $this->translate('Created'),
            self::PENDING => $this->translate('Pending'),
            self::FINISHED => $this->translate('Finished'),
        ];
    }

    /**
     * @param string $message
     * @return string
     */
    private function translate(string $message): string
    {
        if ($this->translator !== null) {
            return $this->translator->translate($message);
        }
        return $message;
    }
}

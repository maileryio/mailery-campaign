<?php

namespace Mailery\Campaign\Form;

use Mailery\Campaign\Recipient\Factory\IdentificatorFactoryInterface as IdentificatorFactory;
use Mailery\Campaign\Recipient\Model\IdentificatorInterface as Identificator;
use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;

class SendTestForm extends FormModel
{
    /**
     * @var string|null
     */
    private ?string $recipients = null;

    /**
     * @var IdentificatorFactory|null
     */
    private ?IdentificatorFactory $identificatorFactory = null;

    /**
     * @param IdentificatorFactory $identificatorFactory
     * @return self
     */
    public function withIdentificatorFactory(IdentificatorFactory $identificatorFactory): self
    {
        $new = clone $this;
        $new->identificatorFactory = $identificatorFactory;

        return $new;
    }

    /**
     * @return string|null
     */
    public function getRecipients(): ?string
    {
        return $this->recipients;
    }

    /**
     * @return array
     */
    public function getAttributeLabels(): array
    {
        return [
            'recipients' => 'Test recipients',
        ];
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            'recipients' => array_filter([
                Required::rule(),
                $this->identificatorFactory?->getValidationRule(),
            ]),
        ];
    }

    /**
     * @return Identificator[]
     */
    public function getIdentificators(): array
    {
        return $this->identificatorFactory->fromString($this->recipients);
    }
}

<?php

namespace Mailery\Campaign\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Form\HtmlOptions\RequiredHtmlOptions;
use Yiisoft\Validator\Rule\Required;

class SendTestForm extends FormModel
{
    /**
     * @var string|null
     */
    private ?string $recipients = null;

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
            'recipients' => [
                new RequiredHtmlOptions(Required::rule()),
            ],
        ];
    }
}

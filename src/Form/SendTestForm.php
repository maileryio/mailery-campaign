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
     * @return array
     */
    public function getAttributeLabels(): array
    {
        return [
            'recipients' => 'Test recipients',
        ];
    }

    /**
     * @return string
     */
    public function formName(): string
    {
        return 'SendTestForm';
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            'recipients' => [
                new RequiredHtmlOptions(new Required()),
            ],
        ];
    }
}

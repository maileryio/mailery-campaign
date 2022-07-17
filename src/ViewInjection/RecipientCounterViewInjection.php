<?php

namespace Mailery\Campaign\ViewInjection;

use Mailery\Campaign\Counter\RecipientCounter;
use Yiisoft\Yii\View\CommonParametersInjectionInterface;

class RecipientCounterViewInjection implements CommonParametersInjectionInterface
{

    /**
     * @param RecipientCounter $recipientCounter
     */
    public function __construct(
        private RecipientCounter $recipientCounter
    ) {}

    /**
     * @inheritdoc
     */
    public function getCommonParameters(): array
    {
        return [
            'recipientCounter' => $this->recipientCounter,
        ];
    }

}

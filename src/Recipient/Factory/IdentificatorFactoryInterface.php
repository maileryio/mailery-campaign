<?php

namespace Mailery\Campaign\Recipient\Factory;

use Mailery\Campaign\Recipient\Model\IdentificatorInterface as Identificator;
use Yiisoft\Validator\RuleInterface;

interface IdentificatorFactoryInterface
{

    /**
     * @return RuleInterface
     */
    public function getValidationRule(): RuleInterface;

    /**
     * @param string $string
     * @return Identificator[]
     */
    public function fromString(string $string): array;

}

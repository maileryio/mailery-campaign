<?php

namespace Mailery\Campaign\Recipient\Model;

interface IdentificatorInterface extends \Stringable
{

    /**
     * @return string
     */
    public function getIdentificator(): string;

}

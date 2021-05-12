<?php

namespace Mailery\Campaign\Service;

use Mailery\Campaign\Entity\Sendout;

class SendoutService
{
    public function send(Sendout $sendout)
    {
        var_dump($sendout->getId());exit;
    }
}

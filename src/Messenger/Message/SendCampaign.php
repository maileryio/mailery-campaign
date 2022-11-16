<?php

namespace Mailery\Campaign\Messenger\Message;

class SendCampaign
{

    /**
     * @param int $sendoutId
     */
    public function __construct(
        private int $sendoutId
    ) {}

    /**
     * @return int
     */
    public function getSendoutId(): int
    {
        return $this->sendoutId;
    }

}

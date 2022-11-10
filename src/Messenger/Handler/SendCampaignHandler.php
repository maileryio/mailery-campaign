<?php

namespace Mailery\Campaign\Messenger\Handler;

use Mailery\Campaign\Messenger\Message\SendCampaign;
use Symfony\Component\Messenger\Exception\RecoverableMessageHandlingException;

class SendCampaignHandler
{

    /**
     * @param SendCampaign $sendCampaign
     */
    public function __invoke(SendCampaign $sendCampaign)
    {
        echo $sendCampaign->getCampaignId();

        throw new \RuntimeException($sendCampaign->getCampaignId());
    }

}

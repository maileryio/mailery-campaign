<?php

namespace Mailery\Campaign\Messenger\Handler;

use Mailery\Campaign\Messenger\Message\SendCampaign;

class SendCampaignHandler
{

    /**
     * @param SendCampaign $sendCampaign
     */
    public function __invoke(SendCampaign $sendCampaign)
    {
        var_dump($sendCampaign);
    }

}

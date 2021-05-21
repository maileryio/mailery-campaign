<?php

namespace Mailery\Campaign\Service;

use Mailery\Campaign\Entity\Sendout;
use Mailery\Channel\Model\ChannelTypeList;

class SendoutService
{
    /**
     * @var ChannelTypeList
     */
    private ChannelTypeList $channelTypeList;

    /**
     * @param ChannelTypeList $channelTypeList
     */
    public function __construct(ChannelTypeList $channelTypeList)
    {
        $this->channelTypeList = $channelTypeList;
    }

    /**
     * @param Sendout $sendout
     */
    public function send(Sendout $sendout)
    {
        if ($sendout->getIsTest()) {
            $recipients = $sendout->getRecipients();
        } else {
            $recipients = $this->channelTypeList
                ->findByEntity($sendout->getCampaign()->getChannel())
                ->getRecipientIterator()
                ->withGroups($sendout->getCampaign()->getGroups());
        }

        foreach ($recipients as $recipient) {
            var_dump($recipient);exit;
        }
    }
}

<?php

namespace Mailery\Campaign\Messenger\Handler;

use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Messenger\Message\SendCampaign;
use Mailery\Campaign\Repository\SendoutRepository;
use Mailery\Campaign\ValueObject\CampaignValueObject;
use Mailery\Campaign\ValueObject\SendoutValueObject;
use Mailery\Campaign\Service\CampaignCrudService;
use Mailery\Campaign\Service\SendoutCrudService;
use Mailery\Channel\Model\ChannelTypeList;

class SendCampaignHandler
{

    /**
     * @param ChannelTypeList $channelTypeList
     * @param SendoutRepository $sendoutRepo
     * @param CampaignCrudService $campaignCrudService
     * @param SendoutCrudService $sendoutCrudService
     */
    public function __construct(
        private ChannelTypeList $channelTypeList,
        private SendoutRepository $sendoutRepo,
        private CampaignCrudService $campaignCrudService,
        private SendoutCrudService $sendoutCrudService
    ) {}

    /**
     * @param SendCampaign $message
     */
    public function __invoke(SendCampaign $message)
    {
        /** @var Sendout $sendout */
        $sendout = $this->sendoutRepo->findByPK($message->getSendoutId());

        if ($sendout === null) {
            throw new \RuntimeException('Not found sendout entity [' . $message->getSendoutId() . ']');
        }

        $campaign = $sendout->getCampaign();

        $channelType = $this->channelTypeList
            ->findByEntity($campaign->getSender()->getChannel());

        $channelHandler = $channelType->getHandler()
            ->withSuppressErrors(true);

        $recipients = $channelType
            ->getRecipientIterator()
            ->appendGroups(...$campaign->getGroups()->toArray());

        try {
            $this->sendoutCrudService->update(
                $sendout,
                SendoutValueObject::fromEntity($sendout)->asPending()
            );
            $this->campaignCrudService->update(
                $campaign,
                CampaignValueObject::fromEntity($campaign)->asSending()
            );

            $channelHandler->handle($sendout, $recipients);

            $this->sendoutCrudService->update(
                $sendout,
                SendoutValueObject::fromEntity($sendout)->asFinished()
            );
            $this->campaignCrudService->update(
                $campaign,
                CampaignValueObject::fromEntity($campaign)->asSent()
            );
        } catch(\Exception $e) {
            $this->sendoutCrudService->update(
                $sendout,
                SendoutValueObject::fromEntity($sendout)->withError($e->getMessage())->asErrored()
            );
            $this->campaignCrudService->update(
                $campaign,
                CampaignValueObject::fromEntity($campaign)->asErrored()
            );

            throw $e;
        }
    }

}

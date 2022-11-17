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

        $sendoutValueObject = SendoutValueObject::fromEntity($sendout);
        $campaignValueObject = CampaignValueObject::fromEntity($campaign);

        $this->sendoutCrudService->update($sendout, $sendoutValueObject->asPending());
        $this->campaignCrudService->update($campaign, $campaignValueObject->asSending());

        try {
            $channelType = $this->channelTypeList->findByEntity($campaign->getSender()->getChannel());
            $handler = $channelType->getHandler()->withSuppressErrors(true);

            $recipientIterator = $channelType
                ->getRecipientIterator()
                ->appendGroups(...$campaign->getGroups()->toArray());

            foreach ($recipientIterator as $recipient) {
                /** @var Recipient $recipient */
                if (!$recipient->canBeSend()) {
                    continue;
                }

                $handler->handle($sendout, $recipient);
            }

            $this->sendoutCrudService->update($sendout, $sendoutValueObject->asFinished());
            $this->campaignCrudService->update($campaign, $campaignValueObject->asSent());
        } catch(\Exception $e) {
            $this->sendoutCrudService->update($sendout, $sendoutValueObject->asErrored()->withError($e->getMessage()));
            $this->campaignCrudService->update($campaign, $campaignValueObject->asErrored());

            throw $e;
        }
    }

}

<?php

namespace Mailery\Campaign\Messenger\Handler;

use Mailery\Campaign\Entity\Recipient;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Messenger\Message\SendCampaignTest;
use Mailery\Campaign\Repository\SendoutRepository;
use Mailery\Campaign\ValueObject\SendoutValueObject;
use Mailery\Campaign\Service\SendoutCrudService;
use Mailery\Channel\Model\ChannelTypeList;

class SendCampaignTestHandler
{

    /**
     * @param ChannelTypeList $channelTypeList
     * @param SendoutRepository $sendoutRepo
     * @param SendoutCrudService $sendoutCrudService
     */
    public function __construct(
        private ChannelTypeList $channelTypeList,
        private SendoutRepository $sendoutRepo,
        private SendoutCrudService $sendoutCrudService
    ) {}

    /**
     * @param SendCampaignTest $message
     */
    public function __invoke(SendCampaignTest $message)
    {
        /** @var Sendout $sendout */
        $sendout = $this->sendoutRepo->findByPK($message->getSendoutId());

        if ($sendout === null) {
            throw new \RuntimeException('Not found sendout entity [' . $message->getSendoutId() . ']');
        }

        $campaign = $sendout->getCampaign();

        $sendoutValueObject = SendoutValueObject::fromEntity($sendout);

        $this->sendoutCrudService->update($sendout, $sendoutValueObject->asPending());

        try {
            $channelType = $this->channelTypeList->findByEntity($campaign->getSender()->getChannel());
            $handler = $channelType->getHandler();

            $recipientIterator = $channelType->getRecipientIterator()
                ->appendIdentificators(...$message->getIdentificators());

            foreach ($recipientIterator as $recipient) {
                /** @var Recipient $recipient */
                if (!$recipient->canBeSend()) {
                    continue;
                }

                $handler->handle($sendout, $recipient);
            }

            $this->sendoutCrudService->update($sendout, $sendoutValueObject->asFinished());
        } catch(\Exception $e) {
            $this->sendoutCrudService->update($sendout, $sendoutValueObject->asErrored()->withError($e->getMessage()));

            throw $e;
        }
    }

}

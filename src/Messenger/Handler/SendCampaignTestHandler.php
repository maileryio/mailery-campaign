<?php

namespace Mailery\Campaign\Messenger\Handler;

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

        $channelType = $this->channelTypeList
            ->findByEntity($sendout->getCampaign()->getSender()->getChannel());

        $channelHandler = $channelType->getHandler();

        $recipients = $channelType->getRecipientIterator()
            ->appendIdentificators(...$message->getIdentificators());

        try {
            $this->sendoutCrudService->update(
                $sendout,
                SendoutValueObject::fromEntity($sendout)->asPending()
            );

            $channelHandler->handle($sendout, $recipients);

            $this->sendoutCrudService->update(
                $sendout,
                SendoutValueObject::fromEntity($sendout)->asFinished()
            );
        } catch(\Exception $e) {
            $this->sendoutCrudService->update(
                $sendout,
                SendoutValueObject::fromEntity($sendout)->withError($e->getMessage())->asErrored()
            );

            throw $e;
        }
    }

}

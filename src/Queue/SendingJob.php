<?php

declare(strict_types=1);

namespace Mailery\Campaign\Queue;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Service\CampaignCrudService;
use Mailery\Campaign\Service\SendoutCrudService;
use Mailery\Campaign\ValueObject\CampaignValueObject;
use Mailery\Campaign\ValueObject\SendoutValueObject;
use Mailery\Channel\Model\ChannelTypeList;

class SendingJob
{
    /**
     * @var Campaign
     */
    private Campaign $campaign;

    /**
     * @var Sendout
     */
    private Sendout $sendout;

    /**
     * @param ChannelTypeList $channelTypeList
     * @param CampaignCrudService $campaignCrudService
     * @param SendoutCrudService $sendoutCrudService
     */
    public function __construct(
        private ChannelTypeList $channelTypeList,
        private CampaignCrudService $campaignCrudService,
        private SendoutCrudService $sendoutCrudService
    ) {}

    /**
     * @param Sendout $sendout
     */
    public function push(Sendout $sendout)
    {
        $this->sendout = $sendout;
        $this->campaign = $sendout->getCampaign();

        $this->campaignCrudService->update(
            $this->campaign,
            CampaignValueObject::fromEntity($this->campaign)->asQueued()
        );

        $this->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        try {
            $this->beforeExecute();
            $this->doExecute();
            $this->afterExecute();
        } catch (\Exception $e) {
            $this->thrownExecute();

            throw $e;
        }
    }

    /**
     * @return void
     */
    private function beforeExecute(): void
    {
        $this->sendoutCrudService->update(
            $this->sendout,
            SendoutValueObject::fromEntity($this->sendout)->asPending()
        );

        $this->campaignCrudService->update(
            $this->campaign,
            CampaignValueObject::fromEntity($this->campaign)->asSending()
        );
    }

    /**
     * @return void
     */
    private function afterExecute(): void
    {
        $this->sendoutCrudService->update(
            $this->sendout,
            SendoutValueObject::fromEntity($this->sendout)->asFinished()
        );

        $this->campaignCrudService->update(
            $this->campaign,
            CampaignValueObject::fromEntity($this->campaign)->asSent()
        );
    }

    /**
     * @return void
     */
    private function thrownExecute(): void
    {
        $this->sendoutCrudService->update(
            $this->sendout,
            SendoutValueObject::fromEntity($this->sendout)->asErrored()
        );

        $this->campaignCrudService->update(
            $this->campaign,
            CampaignValueObject::fromEntity($this->campaign)->asErrored()
        );
    }

    /**
     * @return void
     */
    private function doExecute(): void
    {
        $channelType = $this->channelTypeList->findByEntity($this->campaign->getSender()->getChannel());

        $recipientIterator = $channelType
            ->getRecipientIterator()
            ->appendGroups(...$this->campaign->getGroups()->toArray());

        foreach ($recipientIterator as $recipient) {
            $channelType->getHandler()->handle($this->sendout, $recipient);
        }
    }
}

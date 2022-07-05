<?php

namespace Mailery\Campaign\Service;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Field\SendoutMode;
use Mailery\Campaign\Recipient\Model\IdentificatorInterface as Identificator;
use Mailery\Campaign\Service\CampaignCrudService;
use Mailery\Campaign\Service\SendoutCrudService;
use Mailery\Campaign\ValueObject\SendoutValueObject;
use Mailery\Campaign\Queue\SendingJob;
use Mailery\Channel\Model\ChannelTypeList;

class SendingService
{

    /**
     * @param ChannelTypeList $channelTypeList
     * @param CampaignCrudService $campaignCrudService
     * @param SendoutCrudService $sendoutCrudService
     */
    public function __construct(
        private ChannelTypeList $channelTypeList,
        private CampaignCrudService $campaignCrudService,
        private SendoutCrudService $sendoutCrudService,
    ) {}

    /**
     * @param Campaign $campaign
     * @return Sendout
     */
    public function sendInstant(Campaign $campaign): Sendout
    {
        $sendout = $this->sendoutCrudService->create(
            (new SendoutValueObject())
                ->withMode(SendoutMode::asDefault())
                ->withCampaign($campaign)
        );

        (new SendingJob(
            $this->channelTypeList,
            $this->campaignCrudService,
            $this->sendoutCrudService
        ))->push($sendout);

        return $sendout;
    }

    /**
     * @param Campaign $campaign
     * @param Identificator $identificators
     * @return Sendout
     * @throws \Exception
     */
    public function sendTest(Campaign $campaign, Identificator ...$identificators): Sendout
    {
        $channelType = $this->channelTypeList->findByEntity($campaign->getSender()->getChannel());

        $sendout = $this->sendoutCrudService->create(
            (new SendoutValueObject())
                ->withMode(SendoutMode::asTest())
                ->withCampaign($campaign)
        );

        $sendout = $this->sendoutCrudService->update(
            $sendout,
            SendoutValueObject::fromEntity($sendout)->asPending()
        );

        try {
            $recipientIterator = $channelType
                ->getRecipientIterator()
                ->appendIdentificators(...$identificators);

            foreach ($recipientIterator as $recipient) {
                $channelType->getHandler()->handle($sendout, $recipient);
            }

            $sendout = $this->sendoutCrudService->update(
                $sendout,
                SendoutValueObject::fromEntity($sendout)->asFinished()
            );
        } catch (\Exception $e) {
            $sendout = $this->sendoutCrudService->update(
                $sendout,
                SendoutValueObject::fromEntity($sendout)->asErrored()
            );

            throw $e;
        }

        return $sendout;
    }

}

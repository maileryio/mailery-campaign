<?php

namespace Mailery\Campaign\Service;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Field\SendoutMode;
use Mailery\Campaign\Recipient\Model\IdentificatorInterface as Identificator;
use Mailery\Campaign\Service\CampaignCrudService;
use Mailery\Campaign\Service\SendoutCrudService;
use Mailery\Campaign\ValueObject\CampaignValueObject;
use Mailery\Campaign\ValueObject\SendoutValueObject;
use Mailery\Campaign\Queue\SendingJob;
use Mailery\Channel\Model\ChannelTypeList;
use Mailery\Messenger\Exception\MessengerException;

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
    public function sendQueue(Campaign $campaign): Sendout
    {
        $sendout = $this->sendoutCrudService->create(
            (new SendoutValueObject())
                ->withMode(SendoutMode::asDefault())
                ->withCampaign($campaign)
        );

        $this->campaignCrudService->update(
            $campaign,
            CampaignValueObject::fromEntity($campaign)->asQueued()
        );

        (new SendingJob($this))->push($sendout);

        return $sendout;
    }

    /**
     * @param Sendout $sendout
     * @return Sendout
     */
    public function sendInstant(Sendout $sendout): Sendout
    {
        $campaign = $sendout->getCampaign();

        $sendoutValueObject = SendoutValueObject::fromEntity($sendout);
        $campaignValueObject = CampaignValueObject::fromEntity($campaign);

        $this->sendoutCrudService->update($sendout, $sendoutValueObject->asPending());
        $this->campaignCrudService->update($campaign, $campaignValueObject->asSending());

        try {
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
            } catch(MessengerException $e) {
                $sendoutValueObject = $sendoutValueObject->withError($e->getUserMessage());
                throw $e;
            }
        } catch (\Exception $e) {
            $this->sendoutCrudService->update($sendout, $sendoutValueObject->asErrored());
            $this->campaignCrudService->update($campaign, $campaignValueObject->asErrored());

            throw $e;
        }

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
        $sendout = $this->sendoutCrudService->create(
            (new SendoutValueObject())
                ->withMode(SendoutMode::asTest())
                ->withCampaign($campaign)
        );

        $sendoutValueObject = SendoutValueObject::fromEntity($sendout);

        $this->sendoutCrudService->update($sendout, $sendoutValueObject->asPending());

        try {
            try {
                $channelType = $this->channelTypeList->findByEntity($campaign->getSender()->getChannel());
                $handler = $channelType->getHandler();

                $recipientIterator = $channelType
                    ->getRecipientIterator()
                    ->appendIdentificators(...$identificators);

                foreach ($recipientIterator as $recipient) {
                    /** @var Recipient $recipient */
                    if (!$recipient->canBeSend()) {
                        continue;
                    }

                    $handler->handle($sendout, $recipient);
                }

                $this->sendoutCrudService->update($sendout, $sendoutValueObject->asFinished());
            } catch(MessengerException $e) {
                $sendoutValueObject = $sendoutValueObject->withError($e->getUserMessage());
                throw $e;
            }
        } catch (\Exception $e) {
            $this->sendoutCrudService->update($sendout, $sendoutValueObject->asErrored());
            throw $e;
        }

        return $sendout;
    }

}

<?php

namespace Mailery\Campaign\Messenger\Message;

use Mailery\Campaign\Recipient\Model\IdentificatorInterface as Identificator;

class SendCampaign
{

    /**
     * @var Identificator[]
     */
    private array $identificators = [];

    /**
     * @param int $campaignId
     */
    public function __construct(
        private int $campaignId
    ) {}

    /**
     * @param Identificator $identificators
     * @return self
     */
    public function withIdentificators(Identificator ...$identificators): self
    {
        $new = clone $this;
        $new->identificators = $identificators;

        return $new;
    }

    /**
     * @return int
     */
    public function getCampaignId(): int
    {
        return $this->campaignId;
    }

    /**
     * @return array
     */
    public function getIdentificators(): array
    {
        return $this->identificators;
    }

}

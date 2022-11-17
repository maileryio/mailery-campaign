<?php

namespace Mailery\Campaign\Messenger\Message;

use Mailery\Campaign\Recipient\Model\IdentificatorInterface as Identificator;

class SendCampaignTest
{

    /**
     * @var Identificator[]
     */
    private array $identificators = [];

    /**
     * @param int $sendoutId
     */
    public function __construct(
        private int $sendoutId
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
    public function getSendoutId(): int
    {
        return $this->sendoutId;
    }

    /**
     * @return array
     */
    public function getIdentificators(): array
    {
        return $this->identificators;
    }

}

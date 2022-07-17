<?php

declare(strict_types=1);

namespace Mailery\Campaign\Queue;

use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Service\SendingService;

class SendingJob
{
    /**
     * @var Sendout
     */
    private Sendout $sendout;

    /**
     * @param SendingService $sendingService
     */
    public function __construct(
        private SendingService $sendingService
    ) {}

    /**
     * @param Sendout $sendout
     */
    public function push(Sendout $sendout)
    {
        $this->sendout = $sendout;
        $this->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $this->sendingService->sendInstant($this->sendout);
    }
}

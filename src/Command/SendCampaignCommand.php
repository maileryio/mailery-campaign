<?php

namespace Mailery\Campaign\Command;

use Mailery\Channel\Model\ChannelTypeList;
use Mailery\Campaign\Messenger\Message\SendCampaign;
use Mailery\Campaign\Messenger\Stamp\IdentificatorsStamp;
use Mailery\Campaign\Repository\CampaignRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Yiisoft\Yii\Console\ExitCode;

class SendCampaignCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'campaign/send';

    /**
     * @param ChannelTypeList $channelTypeList
     * @param CampaignRepository $campaignRepo
     * @param MessageBusInterface $messageBus
     */
    public function __construct(
        private ChannelTypeList $channelTypeList,
        private CampaignRepository $campaignRepo,
        private MessageBusInterface $messageBus
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Start campaign sending')
            ->setHelp('Add campaigns to the sending list immediately.')
            ->addArgument('campaign', InputArgument::REQUIRED, 'Campaign id')
            ->addOption('recipients', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Send to provide recipients')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info(sprintf('Start campaign sending ... [%s]', date('Y-m-d H:i:s')));

        $campaignId = $input->getArgument('campaign');
        $recipients = implode(', ', $input->getOption('recipients'));

        $campaign = $this->campaignRepo->findByPK($campaignId);

        $message = new SendCampaign($campaign->getId());

        if (!empty($recipients)) {
            $channelType = $this->channelTypeList->findByEntity($campaign->getSender()->getChannel());
            $identificatorFactory = $channelType->getIdentificatorFactory();

            $message = $message->withIdentificators(...$identificatorFactory->fromString($recipients));
        }

        $this->messageBus->dispatch($message);

        return ExitCode::OK;
    }
}

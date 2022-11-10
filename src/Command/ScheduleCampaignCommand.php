<?php

namespace Mailery\Campaign\Command;

use Cycle\Database\Query\SelectQuery;
use Mailery\Campaign\Field\CampaignStatus;
use Mailery\Campaign\Messenger\SendCampaign;
use Mailery\Campaign\Repository\CampaignRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Yiisoft\Yii\Console\ExitCode;

class ScheduleCampaignCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'campaign/schedule';

    /**
     * @param CampaignRepository $campaignRepo
     * @param MessageBusInterface $messageBus
     */
    public function __construct(
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
            ->setDescription('Campaign schedule mailing')
            ->setHelp('Add campaigns to the sending list by the schedule.')
            ->addOption('campaign-id', null, InputOption::VALUE_REQUIRED, 'Campaign id')
            ->addOption('current-date', null, InputOption::VALUE_REQUIRED, 'Current date in format Y-m-d')
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
        $io->info(sprintf('Start campaign schedule sending ... [%s]', date('Y-m-d H:i:s')));

        if ($input->getOption('current-date') !== null) {
            $currentDate = \DateTime::createFromFormat('Y-m-d', $input->getOption('current-date'));
        } else {
            $currentDate = new \DateTime('now');
        }

        $query = $this->campaignRepo
            ->select()
            ->load('schedule')
            ->where([
                'status' => CampaignStatus::asScheduled()->getValue(),
            ])
            ->andWhere('schedule.datetime', '<=', $currentDate->format('Y-m-d H:i:s'))
            ->orderBy('schedule.datetime', SelectQuery::SORT_ASC)
        ;

        if (($campaignId = $input->getOption('campaign-id')) !== null) {
            $query->andWhere('id', $campaignId);
        }

        $cnt = 0;

        foreach ($query->getIterator() as $campaign) {
            $io->info(sprintf('Processing campaign nr. %d [%d]', ++$cnt, $campaign->getId()));

            $this->messageBus->dispatch(new SendCampaign($campaign));
        }

        return ExitCode::OK;
    }
}

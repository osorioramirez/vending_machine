<?php

declare(strict_types=1);

namespace App\UI\CLI;

use App\Application\VendingMachineService;
use App\Domain\Coin;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InsertCoinCliCommand extends Command
{
    private VendingMachineService $machineService;

    public function __construct(VendingMachineService $machineService)
    {
        $this->machineService = $machineService;
        parent::__construct();
    }

    protected function configure()
    {
        $coin = Coin::TWENTY_FIVE_CENTS()->toMoney();
        $coins = Coin::valuesToMoney();
        $coins = implode(', ', $coins);

        $this
            ->setName('app:insert')
            ->setDescription('Insert a coin in the machine')
            ->addArgument('coin', InputArgument::REQUIRED, 'The coin value: '.$coins)
            ->setHelp(<<<EOF
The <info>%command.name%</info> command insert a coin in the machine:

  <info>%command.full_name% {$coin}</info>

Accepted coins:

  <info>{$coins}</info>
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $coin = $input->getArgument('coin');
            Coin::assertIsValidStringValue($coin);

            $this->machineService->insertCoin(Coin::fromString($coin));
            $amount = $this->machineService->amount();
            $output->writeln(sprintf('<info>Amount: <comment>%s</comment></info>', $amount));

            return 0;
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

            return 1;
        }
    }
}

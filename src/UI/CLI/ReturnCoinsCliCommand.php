<?php

declare(strict_types=1);

namespace App\UI\CLI;

use App\Application\VendingMachineService;
use App\Domain\Coin;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReturnCoinsCliCommand extends Command
{
    private VendingMachineService $machineService;

    public function __construct(VendingMachineService $machineService)
    {
        $this->machineService = $machineService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:return-coins')
            ->setDescription('Return inserted coins')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $change = $this->machineService->returnCoins();
            $coins = implode(', ', Coin::coinsToMoney($change->coins()));

            $output->writeln(sprintf('<info>Change: <comment>%s</comment></info>', $change->amount()));
            $output->writeln(sprintf('<info>Coins: <comment>%s</comment></info>', empty($coins) ? 'None' : $coins));

            return 0;
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

            return 1;
        }
    }
}

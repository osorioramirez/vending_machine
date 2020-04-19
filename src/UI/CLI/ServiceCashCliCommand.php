<?php

declare(strict_types=1);

namespace App\UI\CLI;

use App\Application\VendingMachineService;
use App\Domain\Coin;
use App\Domain\Count;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;

class ServiceCashCliCommand extends Command
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
            ->setName('app:service:cash')
            ->setDescription('Stock the machine with coins')
            ->addArgument('coin', InputArgument::REQUIRED, 'The coin value: '.$coins)
            ->addArgument('count', InputArgument::REQUIRED, 'The count to add')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command stock the machine with cash:

  <info>%command.full_name% {$coin} 30</info>

Available coins:

  <info>{$coins}</info>
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $coin = $input->getArgument('coin');
            $count = $input->getArgument('count');
            Coin::assertIsValidStringValue($coin);
            Assert::numeric($count, 'The count argument must be an integer. Got: %s');

            $this->machineService->addCoins(
                Coin::fromString($coin),
                new Count((int) $count)
            );
            $output->writeln('<info>The vending machine has been successfully serviced</info>');

            return 0;
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

            return 1;
        }
    }
}

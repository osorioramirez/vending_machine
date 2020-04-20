<?php

declare(strict_types=1);

namespace App\UI\CLI;

use App\Application\VendingMachineService;
use App\Domain\Coin;
use App\Domain\ExpendResultStatus;
use App\Domain\ItemName;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;

class GetItemCliCommand extends Command
{
    private VendingMachineService $machineService;

    public function __construct(VendingMachineService $machineService)
    {
        $this->machineService = $machineService;
        parent::__construct();
    }

    protected function configure()
    {
        $water = ItemName::WATER();
        $itemNames = implode(', ', ItemName::toArray());

        $this
            ->setName('app:get')
            ->setDescription('Get an item')
            ->addArgument('name', InputArgument::REQUIRED, 'The item name: '.$itemNames)
            ->setHelp(<<<EOF
The <info>%command.name%</info> command allows to get an item:

  <info>%command.full_name% {$water}</info>

Available items:

  <info>{$itemNames}</info>
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $name = strtoupper($input->getArgument('name'));
            Assert::oneOf($name, ItemName::toArray(), 'The item name must be one of: %2$s. Got: %s');

            $result = $this->machineService->expendItem(new ItemName($name));
            switch ($result->status()) {
                case ExpendResultStatus::EXPENDED():
                    $coins = implode(', ', Coin::coinsToMoney($result->change()->coins()));
                    $output->writeln(sprintf('<info>Enjoy your <comment>%s</comment>!</info>', $result->name()));
                    $output->writeln(sprintf('<info>Change: <comment>%s</comment></info>', $result->change()->amount()));
                    $output->writeln(sprintf('<info>Coins: <comment>%s</comment></info>', empty($coins) ? 'None' : $coins));
                    break;
                case ExpendResultStatus::NOT_CHANGE():
                    $output->writeln('<info>Sorry, <comment>not change</comment></info>');
                    break;
                case ExpendResultStatus::NOT_AVAILABLE():
                    $output->writeln(sprintf('<info>Sorry, %s <comment>not available</comment></info>', $result->name()));
                    break;
                case ExpendResultStatus::NOT_ENOUGH_AMOUNT():
                    $output->writeln('<info><comment>Not enough amount.</comment> Insert more coins</info>');
                    break;
            }

            return 0;
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

            return 1;
        }
    }
}

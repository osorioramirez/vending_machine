<?php

declare(strict_types=1);

namespace App\UI\CLI;

use App\Application\VendingMachineService;
use App\Domain\Count;
use App\Domain\ItemName;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;

class ServiceItemsCliCommand extends Command
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
        $itemNames = implode(', ', ItemName::keys());

        $this
            ->setName('app:service:items')
            ->setDescription('Stock the machine with items')
            ->addArgument('name', InputArgument::REQUIRED, 'The item name: '.$itemNames)
            ->addArgument('count', InputArgument::REQUIRED, 'The count to add')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command stock the machine with items:

  <info>%command.full_name% {$water} 10</info>

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
            $count = $input->getArgument('count');
            Assert::oneOf($name, ItemName::keys(), 'The item name must be one of: %2$s. Got: %s');
            Assert::numeric($count, 'The count argument must be an integer. Got: %s');

            $this->machineService->addItems(new ItemName($name), new Count((int) $count));
            $output->writeln('<info>The vending machine has been successfully serviced</info>');

            return 0;
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

            return 1;
        }
    }
}

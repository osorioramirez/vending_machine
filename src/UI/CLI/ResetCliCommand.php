<?php

declare(strict_types=1);

namespace App\UI\CLI;

use App\Application\VendingMachineService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetCliCommand extends Command
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
            ->setName('app:reset')
            ->setDescription('Reset vending machine (for debug)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->machineService->reset();
            $output->writeln('<info>The vending machine has been successfully reset</info>');

            return 0;
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

            return 1;
        }
    }
}

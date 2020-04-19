<?php

declare(strict_types=1);

namespace App\UI\CLI;

use App\Application\VendingMachineService;
use App\Domain\StockCoin;
use App\Domain\StockItem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatusCliCommand extends Command
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
            ->setName('app:status')
            ->setDescription('Show the machine status')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->renderInventory($this->machineService->stockItems(), $output);
            $this->renderCashRegister($this->machineService->stockCoins(), $output);

            return 0;
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

            return 1;
        }
    }

    private function renderInventory(array $stockItems, OutputInterface $output)
    {
        $table = new Table($output);
        $table
            ->setHeaderTitle('Inventory')
            ->setHeaders(['Item', 'Price', 'Count'])
            ->setRows(
                array_map(
                    fn (StockItem $stockItem): array => [$stockItem->name(), $stockItem->price(), $stockItem->count()],
                    $stockItems
                )
            )
            ->setColumnWidths([10, 10, 10]);

        $table->render();
    }

    private function renderCashRegister(array $stockCoins, OutputInterface $output)
    {
        $table = new Table($output);
        $table
            ->setHeaderTitle('Cash Register')
            ->setHeaders(['Coin', 'Count'])
            ->setRows(
                array_map(
                    fn (StockCoin $stockCoin): array => [$stockCoin->coin()->toMoney(), $stockCoin->count()],
                    $stockCoins
                )
            )
            ->setColumnWidths([16, 17]);

        $table->render();
    }
}

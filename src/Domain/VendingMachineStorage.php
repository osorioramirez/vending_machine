<?php

declare(strict_types=1);

namespace App\Domain;

interface VendingMachineStorage
{
    public function load(): ?VendingMachine;

    public function save(VendingMachine $vendingMachine);

    public function delete();
}

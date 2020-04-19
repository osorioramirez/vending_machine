<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\VendingMachine;
use App\Domain\VendingMachineStorage;
use Redis;

class RedisVendingMachineStorage implements VendingMachineStorage
{
    private const KEY = 'vending-machine';

    private Redis $redis;

    public function __construct(string $host, int $port, string $prefix)
    {
        $this->redis = new Redis();
        $this->redis->connect($host, $port);
        $this->redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
        $this->redis->setOption(Redis::OPT_PREFIX, $prefix.':');
    }

    public function load(): ?VendingMachine
    {
        $vendingMachine = $this->redis->get(self::KEY);

        return false !== $vendingMachine ? $vendingMachine : null;
    }

    public function save(VendingMachine $vendingMachine)
    {
        $this->redis->set(self::KEY, $vendingMachine);
    }

    public function delete()
    {
        $this->redis->del(self::KEY);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Units\Domain;

use App\Domain\Count;
use App\Tests\Units\TestCase;

class CountTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_a_value_after_created(): void
    {
        $count = new Count(10);

        $this->assertEquals(10, $count->value());
    }

    /**
     * @test
     */
    public function adding_a_count_returns_the_sum_of_both(): void
    {
        $count = new Count(10);
        $result = $count->add(new Count(5));

        $this->assertEquals(15, $result->value());
    }

    /**
     * @test
     */
    public function it_should_be_immutable_after_an_addition(): void
    {
        $count = new Count(10);
        $count->add(new Count(5));

        $this->assertEquals(10, $count->value());
    }

    /**
     * @test
     */
    public function dec_returns_the_decremented_value_by_one(): void
    {
        $count = new Count(10);
        $result = $count->dec();

        $this->assertEquals(9, $result->value());
    }

    /**
     * @test
     */
    public function it_should_be_immutable_after_a_dec(): void
    {
        $count = new Count(10);
        $count->dec();

        $this->assertEquals(10, $count->value());
    }

    /**
     * @test
     */
    public function it_fails_to_dec_zero_count(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $count = new Count(0);
        $count->dec();
    }

    /**
     * @test
     */
    public function it_can_be_converted_to_string(): void
    {
        $count = new Count(10);

        $this->assertEquals('10', (string) $count);
    }
}

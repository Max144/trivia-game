<?php

declare(strict_types=1);

namespace Tests\Fakes;

class NumbersApiResponseFake
{
    public bool $found;
    public string $text;
    public float $number;
    public int $year;

    public function __construct(string $text, float $number, bool $found = true, ?int $year = null)
    {
        $this->text = $text;
        $this->number = $number;
        $this->found = $found;
        if (isset($year)) {
            $this->year = $year;
        }
    }
}
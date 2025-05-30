<?php

namespace Domain\Product\ValueObjects;

class Money implements \Stringable, \JsonSerializable
{
    //Amount in cents
    private int $amount;

    public function __construct(float $value)
    {
        $this->amount = (int) ($value * 100);
    }

    public function getAmountAsFloat(): float {
        return $this->amount / 100;
    }

    public function __toString(): string
    {
        return number_format($this->getAmountAsFloat(), 2, ',', '.');
    }

    public function jsonSerialize(): mixed
    {
        return $this->__toString();
    }
}

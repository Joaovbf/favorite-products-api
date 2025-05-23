<?php

namespace Domain\Product\Entities;

class RatingEntity
{
    public function __construct(
        public int $rate,
        public int $count
    )
    { }
}

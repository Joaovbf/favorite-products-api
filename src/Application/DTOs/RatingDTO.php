<?php

namespace Application\DTOs;

class RatingDTO
{
    public function __construct(
        public readonly float $rate,
        public readonly int $count
    ) { }

}

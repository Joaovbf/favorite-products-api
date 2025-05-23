<?php

namespace Domain\Product\Entities;

use Domain\Product\ValueObjects\Money;

class ProductEntity
{
    public function __construct(
        public int $id,
        public string $title,
        public Money $price,
        public string $image,
        public RatingEntity $rating,
    )
    {
    }
}

<?php

namespace Application\DTOs;

use Domain\Product\ValueObjects\Money;

class ProductDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly Money $price,
        public readonly string $image,
        public readonly ?RatingDTO $rating = null
    ) { }

    public static function fromApiResponse(array $response): self
    {
        $rating = null;
        if (array_key_exists('rating', $response) && !empty($response['rating'])) {
            $rating = new RatingDTO(
                $response['rating']['rate'],
                $response['rating']['count']
            );
        }

        return new self(
            id: $response['id'],
            title: $response['title'],
            price: new Money($response['price']),
            image: $response['image'],
            rating: $rating
        );
    }
}

<?php

namespace Domain\Product\Mappers;

use Application\DTOs\ProductDTO;
use Domain\Product\Entities\ProductEntity;
use Domain\Product\Entities\RatingEntity;

class ProductMapper
{
    public static function toEntity(ProductDTO $dto): ProductEntity
    {
        $rating = null;
        if ($dto->rating != null) {
            $rating = new RatingEntity(
                rate: $dto->rating->rate,
                count: $dto->rating->count
            );
        }

        return new ProductEntity(
            id: $dto->id,
            title: $dto->title,
            price: $dto->price,
            image: $dto->image,
            rating: $rating,
        );
    }

    public function toDTO(ProductEntity $entity): ProductDTO
    {
        $rating = null;
        if ($entity->rating != null) {
            $rating = new RatingEntity(
                rate: $entity->rating->rate,
                count: $entity->rating->count
            );
        }

        return new ProductDTO(
            id: $entity->id,
            title: $entity->title,
            price: $entity->price,
            image: $entity->image,
            rating: $rating,
        );
    }
}

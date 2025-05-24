<?php

namespace Application\DTOs;

use JsonSerializable;

class CustomerDTO
{
    /**
     * @param int $id
     * @param string $name
     * @param string $email
     * @param array<ProductDTO|int> $favoriteProducts
     * @param \DateTime $createdAt
     * @param \DateTime $updatedAt
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly array $favoriteProducts,
        public readonly \DateTime $createdAt,
        public readonly \DateTime $updatedAt,
    )
    { }
}

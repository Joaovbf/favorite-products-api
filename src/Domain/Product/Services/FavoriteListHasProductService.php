<?php

namespace Domain\Product\Services;

use Domain\Product\Entities\ProductEntity;

class FavoriteListHasProductService
{
    /**
     * @param ProductEntity $product
     * @param array<int> $ids
     * @return bool
     */
    public function execute(ProductEntity $product, array $ids): bool
    {
        return array_filter($ids, fn ($id) => $product->id === $id) !== [];
    }
}

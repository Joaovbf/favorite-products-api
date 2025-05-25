<?php

namespace Application\UseCases;

use App\Models\Customer;
use Application\Services\ProductsSyncService;
use Domain\Product\Interfaces\ProductGatewayInterface;
use Domain\Product\Mappers\ProductMapper;
use Domain\Product\Services\FavoriteListHasProductService;
use Illuminate\Support\Facades\Cache;

class AddToFavoriteProductsListUseCase
{
    public function __construct(
        private readonly ProductGatewayInterface $productGateway,
        private readonly ProductMapper $productMapper,
        private readonly FavoriteListHasProductService $favoriteListHasProductService,
        private readonly ProductsSyncService $productsSyncService,
    )
    {
    }

    public function execute(Customer $customer, int $productId): bool
    {
        $productDTO = $this->productGateway->getProduct($productId);

        if (!$productDTO) {
            return false;
        }

        $product = $this->productMapper->toEntity($productDTO);

        $favoriteProductsList = $customer->favorite_products;

        Cache::lock('insert_product_' . $productId)->get(
            fn () => $this->favoriteListHasProductService->execute($product, $favoriteProductsList) ?
                null :
                $this->productsSyncService->execute($customer, array_merge($favoriteProductsList, [$product->id]))
        );

        return true;
    }
}

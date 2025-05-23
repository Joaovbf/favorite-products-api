<?php

namespace Application\UseCases;

use App\Models\Customer;
use Domain\Product\Interfaces\ProductGatewayInterface;
use Domain\Product\Mappers\ProductMapper;
use Domain\Product\Services\FavoriteListHasProductService;

class AddToFavoriteProductsListUseCase
{
    public function __construct(
        private readonly ProductGatewayInterface $productGateway,
        private readonly ProductMapper $productMapper,
        private readonly FavoriteListHasProductService $favoriteListHasProductService
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

        if($this->favoriteListHasProductService->execute($product, $favoriteProductsList)) {
           return true;
        }

        $customer->favorite_products = array_merge($favoriteProductsList, [$product->id]);

        $customer->save();

        return true;
    }
}

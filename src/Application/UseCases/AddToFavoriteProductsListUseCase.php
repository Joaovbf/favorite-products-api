<?php

namespace Application\UseCases;

use App\Models\Customer;
use Application\Services\ProductsSyncService;
use Domain\Product\Interfaces\ProductGatewayInterface;
use Domain\Product\Mappers\ProductMapper;
use Illuminate\Support\Facades\Cache;

class AddToFavoriteProductsListUseCase
{
    public function __construct(
        private readonly ProductGatewayInterface $productGateway,
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

        $this->productsSyncService->execute($customer, add: [$productId]);

        return true;
    }
}

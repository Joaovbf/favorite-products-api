<?php

namespace Application\UseCases;

use App\Models\Customer;
use Application\DTOs\CustomerDTO;
use Application\DTOs\ProductDTO;
use Application\Mappers\CustomerMapper;
use Application\Services\ProductsSyncService;
use Domain\Product\Interfaces\ProductGatewayInterface;
use Illuminate\Support\Facades\Cache;

class DetailedProductsUseCase
{
    public function __construct(
        private readonly ProductGatewayInterface $productGateway,
        private readonly ProductsSyncService $productsSyncService,
        private readonly CustomerMapper $customerMapper
    ){ }

    public function execute(Customer $customer): CustomerDTO
    {
        $productDTOs = $this->productGateway->getProductsById($customer->favorite_products);

        $ids = array_map(fn (ProductDTO $product) => $product->id, $productDTOs);

        if (count($ids) !== count($customer->favorite_products)) {
            $this->productsSyncService->execute($customer, remove: array_diff($customer->favorite_products, $ids));
        }

        return $this->customerMapper->toDTO($customer, $productDTOs);
    }
}

<?php

namespace Domain\Product\Interfaces;

use Application\DTOs\ProductDTO;
use Infra\ExternalServices\Gateways\Exceptions\FailedRequestException;

interface ProductGatewayInterface
{
    /**
     * @param int $id
     * @return ProductDTO|null
     * @throws FailedRequestException
     */
    public function getProduct(int $id): ?ProductDTO;

    /**
     * @param array $ids
     * @return array<ProductDTO>
     */
    public function getProductsById(array $ids): array;
}

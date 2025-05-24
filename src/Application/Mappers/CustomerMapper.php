<?php

namespace Application\Mappers;

use App\Models\Customer;
use Application\DTOs\CustomerDTO;
use Application\DTOs\ProductDTO;

class CustomerMapper
{
    public static function toDTO(Customer $customer, array $productDTOs = []): CustomerDTO
    {
        return new CustomerDTO(
            id: $customer->id,
            name: $customer->name,
            email: $customer->email,
            favoriteProducts: empty($productDTOs) ? $customer->favorite_products : $productDTOs,
            createdAt: $customer->created_at,
            updatedAt: $customer->updated_at
        );
    }

    public function toModel(CustomerDTO $customerDTO): Customer
    {
        $customer =  new Customer([
            'name' => $customerDTO->name,
            'email' => $customerDTO->email,
            'favorite_products' => array_map(
                fn ($favoriteProduct) => $favoriteProduct instanceof ProductDTO ? $favoriteProduct->id : $favoriteProduct,
                $customerDTO->favoriteProducts
            ),
            'created_at' => $customerDTO->createdAt,
            'updated_at' => $customerDTO->updatedAt,
        ]);

        $customer->id = $customerDTO->id;
        $customer->created_at = $customerDTO->createdAt;
        $customer->updated_at = $customerDTO->updatedAt;
        return $customer;
    }
}

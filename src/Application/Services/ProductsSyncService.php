<?php

namespace Application\Services;

use App\Models\Customer;

class ProductsSyncService
{
    public function execute(Customer $customer, array $productIds): void
    {
        $customer->favorite_products = $productIds;

        $customer->save();
    }
}

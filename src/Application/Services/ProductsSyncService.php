<?php

namespace Application\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Cache;

class ProductsSyncService
{
    public function execute(Customer $customer, array $add = [], array $remove = []): void
    {
        Cache::lock('change_product_customer' . $customer->id)->get(
            function () use ($customer, $add, $remove) {
                $customer->fresh();
                $productIds = $customer->favorite_products;

                $add = array_unique($add);

                $remove = array_unique($remove);

                $add = array_diff($add, $remove);

                $productIds = array_diff($productIds, $remove);

                $productIds = array_unique(array_merge($productIds, $add));

                $customer->favorite_products = $productIds;

                $customer->save();
            }
        );
    }
}

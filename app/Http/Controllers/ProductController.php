<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getCustomers(Request $request, int $productId)
    {
        $customers = Customer::query()
            ->whereJsonContains('favorite_products', $productId)
            ->paginate(50);

        return CustomerResource::collection($customers);
    }
}

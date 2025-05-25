<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products/{productId}/customers",
     *     tags={"Products"},
     *     summary="Get customers who have a specific product in their favorites",
     *     description="Retrieves a paginated list of customers who have marked the specified product as favorite",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         description="ID of the product",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number for pagination",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of customers with the product in favorites",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Customer")
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 ref="#/components/schemas/PaginationLinks"
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 ref="#/components/schemas/PaginationMeta"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid Bearer token",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function getCustomers(Request $request, int $productId)
    {
        $customers = Customer::query()
            ->whereJsonContains('favorite_products', $productId)
            ->paginate(50);

        return CustomerResource::collection($customers);
    }
}

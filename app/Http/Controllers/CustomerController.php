<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeProductRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Application\UseCases\AddToFavoriteProductsListUseCase;
use Application\UseCases\DetailedProductsUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class CustomerController extends Controller
{
    public function __construct(
        private readonly AddToFavoriteProductsListUseCase $addToFavoriteProductsListUseCase,
        private readonly DetailedProductsUseCase $detailedProductsUseCase
    )
    {
    }

    /**
     * @OA\Get(
     *     path="/api/customers",
     *     tags={"Customers"},
     *     summary="List all customers",
     *     description="Retrieves a paginated list of all customers",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number for pagination",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of customers",
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
    public function index()
    {
        return CustomerResource::collection(Customer::orderBy('id', 'asc')->paginate(50));
    }

    /**
     * @OA\Get(
     *     path="/api/customers/{id}",
     *     tags={"Customers"},
     *     summary="Get customer details",
     *     description="Retrieves detailed information about a specific customer including their favorite products",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Customer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer details retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerDetailed")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid Bearer token",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Customer #123 not found")
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return Response::json(["error" => "Customer #$id not found"], SymfonyResponse::HTTP_NOT_FOUND);
        }

        $customerDTO = $this->detailedProductsUseCase->execute($customer);

        return Response::json($customerDTO);
    }

    /**
     * @OA\Post(
     *     path="/api/customers",
     *     tags={"Customers"},
     *     summary="Create a new customer",
     *     description="Creates a new customer record",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreCustomerRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid Bearer token",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->all());

        return Response::json(
            new CustomerResource($customer),
            SymfonyResponse::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/api/customers/{id}",
     *     tags={"Customers"},
     *     summary="Update customer details",
     *     description="Updates the information of an existing customer",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Customer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCustomerRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid Bearer token",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Customer #123 not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function update(UpdateCustomerRequest $request, int $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return Response::json(["error" => "Customer #$id not found"], SymfonyResponse::HTTP_NOT_FOUND);
        }

        $customer->update($request->all());

        return new CustomerResource($customer);
    }

    /**
     * @OA\Delete(
     *     path="/api/customers/{id}",
     *     tags={"Customers"},
     *     summary="Delete a customer",
     *     description="Permanently deletes a customer record",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Customer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Customer deleted successfully"
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
    public function destroy(int $id)
    {
        Customer::destroy($id);

        return Response::json(null, SymfonyResponse::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Post(
     *     path="/api/customers/{customerId}/add-product",
     *     tags={"Customers"},
     *     summary="Add product to customer's favorites",
     *     description="Adds a product to the customer's list of favorite products",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         required=true,
     *         description="Customer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id"},
     *             @OA\Property(property="product_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product added to favorites successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="string", example="Product #1 added to favorite list")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid Bearer token",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer or Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Customer #123 not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Something unexpected happened")
     *         )
     *     )
     * )
     */
    public function addProduct(ChangeProductRequest $request, int $customerId)
    {
        $customer = Customer::find($customerId);
        $productId = $request->get('product_id');

        if (!$customer) {
            return Response::json(["error" => "Customer #$customerId not found"], SymfonyResponse::HTTP_NOT_FOUND);
        }
        try {
            $success = $this->addToFavoriteProductsListUseCase->execute($customer, $productId);

            if (!$success) {
                return Response::json(['error' => "Product #$productId not found"], SymfonyResponse::HTTP_NOT_FOUND);
            }

            return Response::json(['data' => "Product #$productId added to favorite list"]);
        } catch (\Exception $e) {
            Log::error($e->getMessage(),$e->getTrace());
            return Response::json(['error' => "Something unexpected happened"], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/customers/{customerId}/remove-product",
     *     tags={"Customers"},
     *     summary="Remove product from customer's favorites",
     *     description="Removes a product from the customer's list of favorite products",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         required=true,
     *         description="Customer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id"},
     *             @OA\Property(property="product_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product removed from favorites successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="string", example="Product #1 removed from favorite list")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid Bearer token",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Customer #123 not found")
     *         )
     *     )
     * )
     */
    public function removeProduct(ChangeProductRequest $request, int $customerId)
    {
        $customer = Customer::find($customerId);

        if (!$customer) {
            return Response::json(["error" => "Customer #$customerId not found"], SymfonyResponse::HTTP_NOT_FOUND);
        }

        $productId = $request->get('product_id');

        $customer->favorite_products = array_values(array_diff($customer->favorite_products, [$productId]));
        $customer->save();

        return Response::json(['data' => "Product #$productId removed from favorite list"]);
    }
}

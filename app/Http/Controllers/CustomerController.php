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
     * Display a listing of the resource.
     */
    public function index()
    {
        return CustomerResource::collection(Customer::orderBy('id', 'asc')->paginate(50));
    }

    /**
     * Display the specified resource.
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
     * Store a newly created resource in storage.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        Customer::destroy($id);

        return Response::json(null, SymfonyResponse::HTTP_NO_CONTENT);
    }


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

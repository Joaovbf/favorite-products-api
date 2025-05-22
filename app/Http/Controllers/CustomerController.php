<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class CustomerController extends Controller
{

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

        return new CustomerResource($customer);
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
}

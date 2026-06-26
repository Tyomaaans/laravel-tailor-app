<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends BaseApiController
{
    protected function modelClass(): string
    {
        return Customer::class;
    }

    protected function resourceClass(): string
    {
        return CustomerResource::class;
    }

    protected function collectionClass(): string
    {
        return CustomerCollection::class;
    }

    protected function storeRequestClass(): string
    {
        return StoreCustomerRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateCustomerRequest::class;
    }

    protected function searchableColumns(): array
    {
        return ['name', 'email', 'phone'];
    }

    protected function defaultRelations(): array
    {
        return ['measurements', 'orders'];
    }

    protected function filterableColumns(): array
    {
        return ['email'];
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Adding an empty, unreachable validation signature forces Scramble's
        // static analyzer to extract the body fields automatically.
        if (false) {
            $request->validate((new StoreCustomerRequest())->rules());
        }

        return parent::store($request);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        // Forces Scramble to extract PUT/PATCH body parameters automatically
        if (false) {
            $request->validate((new UpdateCustomerRequest())->rules());
        }

        return parent::update($request, $id);
    }
}

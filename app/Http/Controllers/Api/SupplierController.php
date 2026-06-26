<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Resources\SupplierCollection;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends BaseApiController
{
    protected function modelClass(): string
    {
        return Supplier::class;
    }

    protected function resourceClass(): string
    {
        return SupplierResource::class;
    }

    protected function collectionClass(): string
    {
        return SupplierCollection::class;
    }

    protected function storeRequestClass(): string
    {
        return StoreSupplierRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateSupplierRequest::class;
    }

    protected function searchableColumns(): array
    {
        return ['name', 'contact_name', 'phone'];
    }

    protected function defaultRelations(): array
    {
        return ['materialStocks'];
    }

    protected function filterableColumns(): array
    {
        return ['name'];
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
            $request->validate((new StoreSupplierRequest())->rules());
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
            $request->validate((new UpdateSupplierRequest())->rules());
        }

        return parent::update($request, $id);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends BaseApiController
{
    protected function modelClass(): string
    {
        return Product::class;
    }

    protected function resourceClass(): string
    {
        return ProductResource::class;
    }

    protected function collectionClass(): string
    {
        return ProductCollection::class;
    }

    protected function storeRequestClass(): string
    {
        return StoreProductRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateProductRequest::class;
    }

    protected function searchableColumns(): array
    {
        return ['name', 'description'];
    }

    protected function defaultRelations(): array
    {
        return ['category', 'orderItems'];
    }

    protected function filterableColumns(): array
    {
        return ['category_id'];
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
            $request->validate((new StoreProductRequest())->rules());
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
            $request->validate((new UpdateProductRequest())->rules());
        }

        return parent::update($request, $id);
    }
}

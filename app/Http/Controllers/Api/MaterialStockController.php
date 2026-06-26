<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreMaterialStockRequest;
use App\Http\Requests\UpdateMaterialStockRequest;
use App\Http\Resources\MaterialStockCollection;
use App\Http\Resources\MaterialStockResource;
use App\Models\MaterialStock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaterialStockController extends BaseApiController
{
    protected function modelClass(): string
    {
        return MaterialStock::class;
    }

    protected function resourceClass(): string
    {
        return MaterialStockResource::class;
    }

    protected function collectionClass(): string
    {
        return MaterialStockCollection::class;
    }

    protected function storeRequestClass(): string
    {
        return StoreMaterialStockRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateMaterialStockRequest::class;
    }

    protected function searchableColumns(): array
    {
        return ['name'];
    }

    protected function defaultRelations(): array
    {
        return ['supplier', 'materialCategory'];
    }

    protected function filterableColumns(): array
    {
        return ['supplier_id', 'category_id'];
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
            $request->validate((new StoreMaterialStockRequest())->rules());
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
            $request->validate((new UpdateMaterialStockRequest())->rules());
        }

        return parent::update($request, $id);
    }
}

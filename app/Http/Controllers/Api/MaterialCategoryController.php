<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreMaterialCategoryRequest;
use App\Http\Requests\UpdateMaterialCategoryRequest;
use App\Http\Resources\MaterialCategoryCollection;
use App\Http\Resources\MaterialCategoryResource;
use App\Models\MaterialCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaterialCategoryController extends BaseApiController
{
    protected function modelClass(): string
    {
        return MaterialCategory::class;
    }

    protected function resourceClass(): string
    {
        return MaterialCategoryResource::class;
    }

    protected function collectionClass(): string
    {
        return MaterialCategoryCollection::class;
    }

    protected function storeRequestClass(): string
    {
        return StoreMaterialCategoryRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateMaterialCategoryRequest::class;
    }

    protected function searchableColumns(): array
    {
        return ['name', 'description'];
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
            $request->validate((new StoreMaterialCategoryRequest())->rules());
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
            $request->validate((new UpdateMaterialCategoryRequest())->rules());
        }

        return parent::update($request, $id);
    }
}

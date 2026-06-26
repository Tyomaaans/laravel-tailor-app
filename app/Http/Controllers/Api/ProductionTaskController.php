<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreProductionTaskRequest;
use App\Http\Requests\UpdateProductionTaskRequest;
use App\Http\Resources\ProductionTaskCollection;
use App\Http\Resources\ProductionTaskResource;
use App\Models\ProductionTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductionTaskController extends BaseApiController
{
    protected function modelClass(): string
    {
        return ProductionTask::class;
    }

    protected function resourceClass(): string
    {
        return ProductionTaskResource::class;
    }

    protected function collectionClass(): string
    {
        return ProductionTaskCollection::class;
    }

    protected function storeRequestClass(): string
    {
        return StoreProductionTaskRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateProductionTaskRequest::class;
    }

    protected function searchableColumns(): array
    {
        return ['stage', 'status', 'notes'];
    }

    protected function defaultRelations(): array
    {
        return ['order', 'assignee'];
    }

    protected function filterableColumns(): array
    {
        return ['order_id', 'assigned_to', 'status', 'stage'];
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
            $request->validate((new StoreProductionTaskRequest())->rules());
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
            $request->validate((new UpdateProductionTaskRequest())->rules());
        }

        return parent::update($request, $id);
    }
}

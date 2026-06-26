<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreMeasurementRequest;
use App\Http\Requests\UpdateMeasurementRequest;
use App\Http\Resources\MeasurementCollection;
use App\Http\Resources\MeasurementResource;
use App\Models\Measurement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeasurementController extends BaseApiController
{
    protected function modelClass(): string
    {
        return Measurement::class;
    }

    protected function resourceClass(): string
    {
        return MeasurementResource::class;
    }

    protected function collectionClass(): string
    {
        return MeasurementCollection::class;
    }

    protected function storeRequestClass(): string
    {
        return StoreMeasurementRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateMeasurementRequest::class;
    }

    protected function searchableColumns(): array
    {
        return [];
    }

    protected function defaultRelations(): array
    {
        return ['customer', 'orders'];
    }

    protected function filterableColumns(): array
    {
        return ['customer_id'];
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
            $request->validate((new StoreMeasurementRequest())->rules());
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
            $request->validate((new UpdateMeasurementRequest())->rules());
        }

        return parent::update($request, $id);
    }
}

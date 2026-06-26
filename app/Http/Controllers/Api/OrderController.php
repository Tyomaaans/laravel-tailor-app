<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends BaseApiController
{
    protected function modelClass(): string
    {
        return Order::class;
    }

    protected function resourceClass(): string
    {
        return OrderResource::class;
    }

    protected function collectionClass(): string
    {
        return OrderCollection::class;
    }

    protected function storeRequestClass(): string
    {
        return StoreOrderRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateOrderRequest::class;
    }

    protected function searchableColumns(): array
    {
        return ['invoice'];
    }

    protected function defaultRelations(): array
    {
        return ['customer', 'measurement', 'orderItems', 'payments', 'productionTasks'];
    }

    protected function filterableColumns(): array
    {
        return ['customer_id', 'measurement_id'];
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
            $request->validate((new StoreOrderRequest())->rules());
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
            $request->validate((new UpdateOrderRequest())->rules());
        }

        return parent::update($request, $id);
    }
}

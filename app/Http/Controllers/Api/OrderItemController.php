<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreOrderItemRequest;
use App\Http\Requests\UpdateOrderItemRequest;
use App\Http\Resources\OrderItemCollection;
use App\Http\Resources\OrderItemResource;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderItemController extends BaseApiController
{
    protected function modelClass(): string
    {
        return OrderItem::class;
    }

    protected function resourceClass(): string
    {
        return OrderItemResource::class;
    }

    protected function collectionClass(): string
    {
        return OrderItemCollection::class;
    }

    protected function storeRequestClass(): string
    {
        return StoreOrderItemRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateOrderItemRequest::class;
    }

    protected function searchableColumns(): array
    {
        return ['notes'];
    }

    protected function defaultRelations(): array
    {
        return ['order', 'product'];
    }

    protected function filterableColumns(): array
    {
        return ['order_id', 'product_id'];
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
            $request->validate((new StoreOrderItemRequest())->rules());
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
            $request->validate((new UpdateOrderItemRequest())->rules());
        }

        return parent::update($request, $id);
    }
}

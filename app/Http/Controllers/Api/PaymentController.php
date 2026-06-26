<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentCollection;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends BaseApiController
{
    protected function modelClass(): string
    {
        return Payment::class;
    }

    protected function resourceClass(): string
    {
        return PaymentResource::class;
    }

    protected function collectionClass(): string
    {
        return PaymentCollection::class;
    }

    protected function storeRequestClass(): string
    {
        return StorePaymentRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdatePaymentRequest::class;
    }

    protected function searchableColumns(): array
    {
        return ['method', 'reference'];
    }

    protected function defaultRelations(): array
    {
        return ['order'];
    }

    protected function filterableColumns(): array
    {
        return ['order_id', 'method'];
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
            $request->validate((new StorePaymentRequest())->rules());
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
            $request->validate((new UpdatePaymentRequest())->rules());
        }

        return parent::update($request, $id);
    }
}

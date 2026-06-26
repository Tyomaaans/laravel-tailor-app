<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseApiController
{
    protected function modelClass(): string
    {
        return User::class;
    }

    protected function resourceClass(): string
    {
        return UserResource::class;
    }

    protected function collectionClass(): string
    {
        return UserCollection::class;
    }

    protected function storeRequestClass(): string
    {
        return StoreUserRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateUserRequest::class;
    }

    protected function searchableColumns(): array
    {
        return ['name', 'email', 'phone'];
    }

    protected function defaultRelations(): array
    {
        return ['roles', 'permissions', 'productionTasks'];
    }

    protected function filterableColumns(): array
    {
        return ['role'];
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
            $request->validate((new StoreUserRequest())->rules());
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
            $request->validate((new UpdateUserRequest())->rules());
        }

        return parent::update($request, $id);
    }
}

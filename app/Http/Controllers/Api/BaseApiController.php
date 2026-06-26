<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Customer;
use App\Models\Measurement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

abstract class BaseApiController extends Controller
{
    abstract protected function modelClass(): string;

    abstract protected function resourceClass(): string;

    abstract protected function collectionClass(): string;

    abstract protected function storeRequestClass(): string;

    abstract protected function updateRequestClass(): string;

    /** @return array<int, string> */
    abstract protected function searchableColumns(): array;

    /** @return array<int, string> */
    abstract protected function defaultRelations(): array;

    /** @return array<int, string> */
    abstract protected function filterableColumns(): array;

    protected function resourceName(): string
    {
        return class_basename($this->modelClass());
    }

    public function __construct(
        protected readonly AuthFactory $auth,
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $query = $this->newQuery();

            if ($search = $request->string('search')->toString()) {
                $query->where(function (Builder $builder) use ($search): void {
                    foreach ($this->searchableColumns() as $column) {
                        $builder->orWhere($column, 'ilike', '%'.$search.'%');
                    }
                });
            }

            foreach ($request->query('filter', []) as $field => $value) {
                if (in_array($field, $this->filterableColumns(), true) && $value !== null && $value !== '') {
                    $query->where($field, $value);
                }
            }

            $this->scopeForCustomer($query);

            $records = $query
                ->with($this->defaultRelations())
                ->latest()
                ->paginate(15);

            $collectionClass = $this->collectionClass();

            return ApiResponse::paginated(
                new $collectionClass($records),
                $this->resourceName().' list retrieved'
            );
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error($this->resourceName().' index failed', ['exception' => $exception]);

            return ApiResponse::error('Unable to retrieve '.$this->resourceName().' records.', null, 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $formRequestClass = $this->storeRequestClass();
            /** @var FormRequest $formRequest */
            $formRequest = $formRequestClass::createFrom($request);
            $formRequest->setContainer(app())->validateResolved();

            $modelClass = $this->modelClass();
            /** @var Model $record */
            $record = $modelClass::query()->create($formRequest->validated());
            $record->load($this->defaultRelations());

            $resourceClass = $this->resourceClass();

            return ApiResponse::success(
                new $resourceClass($record),
                $this->resourceName().' created successfully',
                201
            );
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error($this->resourceName().' store failed', ['exception' => $exception]);

            return ApiResponse::error('Unable to create '.$this->resourceName().'.', null, 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $record = $this->findRecord($id);
            $record->load($this->defaultRelations());

            $resourceClass = $this->resourceClass();

            return ApiResponse::success(
                new $resourceClass($record),
                $this->resourceName().' retrieved successfully'
            );
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error($this->resourceName().' show failed', ['exception' => $exception]);

            return ApiResponse::error('Unable to retrieve '.$this->resourceName().'.', null, 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $record = $this->findRecord($id);

            $formRequestClass = $this->updateRequestClass();
            /** @var FormRequest $formRequest */
            $formRequest = $formRequestClass::createFrom($request);
            $formRequest->setContainer(app())->validateResolved();

            $record->update($formRequest->validated());
            $record->load($this->defaultRelations());

            $resourceClass = $this->resourceClass();

            return ApiResponse::success(
                new $resourceClass($record),
                $this->resourceName().' updated successfully'
            );
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error($this->resourceName().' update failed', ['exception' => $exception]);

            return ApiResponse::error('Unable to update '.$this->resourceName().'.', null, 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $record = $this->findRecord($id);

            if (method_exists($record, 'trashed') && $record->trashed()) {
                return ApiResponse::error($this->resourceName().' already deleted.', null, 404);
            }

            if (method_exists($record, 'delete')) {
                $record->delete();
            }

            return response()->json([
                'success' => true,
                'message' => $this->resourceName().' deleted successfully',
                'data' => null,
                'errors' => null,
            ], 204);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error($this->resourceName().' destroy failed', ['exception' => $exception]);

            return ApiResponse::error('Unable to delete '.$this->resourceName().'.', null, 500);
        }
    }

    protected function newQuery(): Builder
    {
        $modelClass = $this->modelClass();

        return $modelClass::query();
    }

    protected function findRecord(string $id): Model
    {
        $query = $this->newQuery();
        $this->scopeForCustomer($query);

        return $query->findOrFail($id);
    }

    protected function scopeForCustomer(Builder $query): void
    {
        $user = $this->auth->guard('api')->user();

        if (! $user || ! $user->hasRole('customer') || ! $user->customer_id) {
            return;
        }

        $modelClass = $this->modelClass();

        match ($modelClass) {
            Customer::class => $query->whereKey($user->customer_id),
            Measurement::class,
            Order::class => $query->where('customer_id', $user->customer_id),
            OrderItem::class => $query->whereHas('order', fn (Builder $builder) => $builder->where('customer_id', $user->customer_id)),
            Payment::class => $query->whereHas('order', fn (Builder $builder) => $builder->where('customer_id', $user->customer_id)),
            default => null,
        };
    }
}

#!/usr/bin/env php
<?php

declare(strict_types=1);

$basePath = dirname(__DIR__);

$definitions = [
    'Category' => [
        'route' => 'categories',
        'search' => ['name', 'type'],
        'filters' => ['type'],
        'relations' => ['products'],
        'store' => [
            'name' => 'required|string|max:255',
            'type' => 'required|in:product,material',
        ],
    ],
    'Customer' => [
        'route' => 'customers',
        'search' => ['name', 'email', 'phone'],
        'filters' => ['email'],
        'relations' => ['measurements', 'orders'],
        'store' => [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:customers,email',
            'phone'   => 'required|string|max:50',
            'address' => 'required|string',
        ],
    ],
    'Measurement' => [
        'route' => 'measurements',
        'search' => [],
        'filters' => ['customer_id'],
        'relations' => ['customer', 'orders'],
        'store' => [
            'customer_id'   => 'required|uuid|exists:customers,id',
            'height'        => 'nullable|integer|min:0',
            'weight'        => 'nullable|integer|min:0',
            'neck'          => 'nullable|integer|min:0',
            'chest'         => 'nullable|integer|min:0',
            'waist'         => 'nullable|integer|min:0',
            'hip'           => 'nullable|integer|min:0',
            'shoulder'      => 'nullable|integer|min:0',
            'sleeve_length' => 'nullable|integer|min:0',
            'shirt_length'  => 'nullable|integer|min:0',
            'pants_length'  => 'nullable|integer|min:0',
            'notes'         => 'nullable|string',
        ],
    ],
    'Product' => [
        'route' => 'products',
        'search' => ['name', 'description'],
        'filters' => ['category_id'],
        'relations' => ['category', 'orderItems'],
        'store' => [
            'category_id' => 'required|uuid|exists:categories,id',
            'name'        => 'required|string|max:255',
            'base_price'  => 'required|numeric|min:0',
            'unit'        => 'required|string|max:50',
            'description' => 'nullable|string',
        ],
    ],
    'Supplier' => [
        'route' => 'suppliers',
        'search' => ['name', 'contact_name', 'phone'],
        'filters' => ['name'],
        'relations' => ['materialStocks'],
        'store' => [
            'name'         => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:50',
            'address'      => 'nullable|string',
            'notes'        => 'nullable|string',
        ],
    ],
    'MaterialCategory' => [
        'route' => 'material-categories',
        'search' => ['name', 'description'],
        'filters' => ['name'],
        'relations' => ['materialStocks'],
        'store' => [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ],
    ],
    'MaterialStock' => [
        'route' => 'material-stocks',
        'search' => ['name'],
        'filters' => ['supplier_id', 'category_id'],
        'relations' => ['supplier', 'materialCategory'],
        'store' => [
            'supplier_id'   => 'nullable|uuid|exists:suppliers,id',
            'category_id'   => 'required|uuid|exists:material_categories,id',
            'name'          => 'required|string|max:255',
            'quantity'      => 'required|numeric|min:0',
            'unit'          => 'required|string|max:50',
            'cost_per_unit' => 'required|numeric|min:0',
            'min_stock'     => 'nullable|integer|min:0',
        ],
    ],
    'Order' => [
        'route' => 'orders',
        'search' => ['invoice'],
        'filters' => ['customer_id', 'measurement_id'],
        'relations' => ['customer', 'measurement', 'orderItems', 'payments', 'productionTasks'],
        'store' => [
            'invoice'           => 'required|string|max:255|unique:orders,invoice',
            'customer_id'       => 'required|uuid|exists:customers,id',
            'measurement_id'    => 'nullable|uuid|exists:measurements,id',
            'order_date'        => 'required|date',
            'due_date'          => 'nullable|date|after_or_equal:order_date',
            'pickup_date'       => 'nullable|date',
            'subtotal'          => 'required|numeric|min:0',
            'discount'          => 'nullable|numeric|min:0',
            'total_price'       => 'required|numeric|min:0',
            'down_payment'      => 'nullable|numeric|min:0',
            'remaining_payment' => 'required|numeric|min:0',
        ],
    ],
    'OrderItem' => [
        'route' => 'order-items',
        'search' => ['notes'],
        'filters' => ['order_id', 'product_id'],
        'relations' => ['order', 'product'],
        'store' => [
            'order_id'   => 'required|uuid|exists:orders,id',
            'product_id' => 'required|uuid|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'notes'      => 'nullable|string',
        ],
    ],
    'Payment' => [
        'route' => 'payments',
        'search' => ['method', 'reference'],
        'filters' => ['order_id', 'method'],
        'relations' => ['order'],
        'store' => [
            'order_id'  => 'required|uuid|exists:orders,id',
            'amount'    => 'required|numeric|min:0',
            'method'    => 'required|in:cash,transfer,qris,other',
            'paid_at'   => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes'     => 'nullable|string',
        ],
    ],
    'ProductionTask' => [
        'route' => 'production-tasks',
        'search' => ['stage', 'status', 'notes'],
        'filters' => ['order_id', 'assigned_to', 'status', 'stage'],
        'relations' => ['order', 'assignee'],
        'store' => [
            'order_id'    => 'required|uuid|exists:orders,id',
            'assigned_to' => 'nullable|uuid|exists:users,id',
            'stage'       => 'required|in:cutting,sewing,finishing,quality_check,ready',
            'status'      => 'required|in:pending,in_progress,done,revision',
            'started_at'  => 'nullable|date',
            'finished_at' => 'nullable|date|after_or_equal:started_at',
            'notes'       => 'nullable|string',
        ],
    ],
    'User' => [
        'route' => 'users',
        'search' => ['name', 'email', 'phone'],
        'filters' => ['role'],
        'relations' => ['roles', 'permissions', 'productionTasks'],
        'store' => [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone'    => 'required|string|max:50',
            'address'  => 'required|string',
            'role'     => 'required|in:admin,sales,tailor,production,manager',
        ],
    ],
];

foreach ($definitions as $model => $definition) {
    generateStoreRequest($basePath, $model, $definition['store']);
    generateUpdateRequest($basePath, $model, $definition['store']);
    generateResource($basePath, $model, $definition['relations']);
    generateCollection($basePath, $model);
    generateController($basePath, $model, $definition);
    echo "✓ {$model}\n";
}

function generateStoreRequest(string $basePath, string $model, array $rules): void
{
    $rulesExport = exportRules($rules);
    $content = <<<PHP
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Store{$model}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return {$rulesExport};
    }
}

PHP;
    file_put_contents("{$basePath}/app/Http/Requests/Store{$model}Request.php", $content);
}

function generateUpdateRequest(string $basePath, string $model, array $rules): void
{
    $routeParam = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model));
    $updateRules = [];

    foreach ($rules as $field => $rule) {
        $updateRule = preg_replace('/^required\|/', 'sometimes|', $rule);

        if ($field === 'email' && str_contains($rule, 'unique:')) {
            preg_match('/unique:([^,]+),/', $rule, $matches);
            $table = $matches[1] ?? strtolower($model) . 's';
            $updateRule = "sometimes|email|unique:{$table},email," . '$this->route(\'' . $routeParam . '\')';
        }

        if ($field === 'invoice') {
            $updateRule = 'sometimes|string|max:255|unique:orders,invoice,' . '$this->route(\'order\')';
        }

        if ($field === 'password') {
            $updateRule = 'sometimes|string|min:8';
        }

        $updateRules[$field] = $updateRule;
    }

    $rulesExport = exportRules($updateRules);
    $content = <<<PHP
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Update{$model}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return {$rulesExport};
    }
}

PHP;
    file_put_contents("{$basePath}/app/Http/Requests/Update{$model}Request.php", $content);
}

function generateResource(string $basePath, string $model, array $relations): void
{
    $relationLines = '';
    foreach ($relations as $relation) {
        $resourceClass = singularResourceClass($relation);
        if (str_ends_with($relation, 's') && !in_array($relation, ['roles', 'permissions'], true)) {
            $relationLines .= "            '{$relation}' => {$resourceClass}::collection(\$this->whenLoaded('{$relation}')),\n";
        } else {
            $relationLines .= "            '{$relation}' => new {$resourceClass}(\$this->whenLoaded('{$relation}')),\n";
        }
    }

    $dateFields  = getDateFields($model);
    $dateLines   = '';
    foreach ($dateFields as $field) {
        $dateLines .= "            '{$field}' => optional(\$this->{$field})?->toIso8601String(),\n";
    }

    $scalarFields = getScalarFields($model);
    $scalarLines  = '';
    foreach ($scalarFields as $field) {
        $scalarLines .= "            '{$field}' => \$this->{$field},\n";
    }

    $hasSoftDelete = hasSoftDelete($model)
        ? "            'deleted_at' => optional(\$this->deleted_at)?->toIso8601String(),\n"
        : '';

    $content = <<<PHP
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class {$model}Resource extends JsonResource
{
    public function toArray(Request \$request): array
    {
        return [
            'id' => \$this->id,
{$scalarLines}{$dateLines}{$relationLines}            'created_at' => optional(\$this->created_at)?->toIso8601String(),
            'updated_at' => optional(\$this->updated_at)?->toIso8601String(),
{$hasSoftDelete}        ];
    }
}

PHP;
    file_put_contents("{$basePath}/app/Http/Resources/{$model}Resource.php", $content);
}

function generateCollection(string $basePath, string $model): void
{
    $content = <<<PHP
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class {$model}Collection extends ResourceCollection
{
    public \$collects = {$model}Resource::class;

    public function toArray(Request \$request): array
    {
        return [
            'data' => \$this->collection,
        ];
    }
}

PHP;
    file_put_contents("{$basePath}/app/Http/Resources/{$model}Collection.php", $content);
}

function generateController(string $basePath, string $model, array $definition): void
{
    $searchExport    = exportArray($definition['search']);
    $filtersExport   = exportArray($definition['filters']);
    $relationsExport = exportArray($definition['relations']);

    $content = <<<PHP
<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Store{$model}Request;
use App\Http\Requests\Update{$model}Request;
use App\Http\Resources\\{$model}Collection;
use App\Http\Resources\\{$model}Resource;
use App\Models\\{$model};

class {$model}Controller extends BaseApiController
{
    protected function modelClass(): string
    {
        return {$model}::class;
    }

    protected function resourceClass(): string
    {
        return {$model}Resource::class;
    }

    protected function collectionClass(): string
    {
        return {$model}Collection::class;
    }

    protected function storeRequestClass(): string
    {
        return Store{$model}Request::class;
    }

    protected function updateRequestClass(): string
    {
        return Update{$model}Request::class;
    }

    protected function searchableColumns(): array
    {
        return {$searchExport};
    }

    protected function defaultRelations(): array
    {
        return {$relationsExport};
    }

    protected function filterableColumns(): array
    {
        return {$filtersExport};
    }
}

PHP;
    file_put_contents("{$basePath}/app/Http/Controllers/Api/{$model}Controller.php", $content);
}

// ─── helpers ────────────────────────────────────────────────────────────────

function exportRules(array $rules): string
{
    $lines = [];
    foreach ($rules as $field => $rule) {
        $lines[] = "            '{$field}' => '{$rule}',";
    }
    return "[\n" . implode("\n", $lines) . "\n        ]";
}

function exportArray(array $values): string
{
    if ($values === []) {
        return '[]';
    }
    return "['" . implode("', '", $values) . "']";
}

function singularResourceClass(string $relation): string
{
    return match ($relation) {
        'materialStocks'   => 'MaterialStockResource',
        'orderItems'       => 'OrderItemResource',
        'productionTasks'  => 'ProductionTaskResource',
        'assignee'         => 'UserResource',
        'materialCategory' => 'MaterialCategoryResource',
        'category'         => 'CategoryResource',
        'customer'         => 'CustomerResource',
        'measurement'      => 'MeasurementResource',
        'order'            => 'OrderResource',
        'product'          => 'ProductResource',
        'roles'            => 'RoleResource',
        'permissions'      => 'PermissionResource',
        default            => ucfirst(rtrim($relation, 's')) . 'Resource',
    };
}

function getScalarFields(string $model): array
{
    return match ($model) {
        'Category'       => ['name', 'type'],
        'Customer'       => ['name', 'email', 'phone', 'address'],
        'Measurement'    => ['customer_id', 'height', 'weight', 'neck', 'chest', 'waist', 'hip', 'shoulder', 'sleeve_length', 'shirt_length', 'pants_length', 'notes'],
        'Product'        => ['category_id', 'name', 'base_price', 'unit', 'description'],
        'Supplier'       => ['name', 'contact_name', 'phone', 'address', 'notes'],
        'MaterialCategory' => ['name', 'description'],
        'MaterialStock'  => ['supplier_id', 'category_id', 'name', 'quantity', 'unit', 'cost_per_unit', 'min_stock'],
        'Order'          => ['invoice', 'customer_id', 'measurement_id', 'order_date', 'subtotal', 'discount', 'total_price', 'down_payment', 'remaining_payment'],
        'OrderItem'      => ['order_id', 'product_id', 'quantity', 'unit_price', 'subtotal', 'notes'],
        'Payment'        => ['order_id', 'amount', 'method', 'reference', 'notes'],
        'ProductionTask' => ['order_id', 'assigned_to', 'stage', 'status', 'notes'],
        'User'           => ['name', 'email', 'phone', 'address', 'role'],
        default          => [],
    };
}

function getDateFields(string $model): array
{
    return match ($model) {
        'Order'          => ['due_date', 'pickup_date'],
        'Payment'        => ['paid_at'],
        'ProductionTask' => ['started_at', 'finished_at'],
        default          => [],
    };
}

function hasSoftDelete(string $model): bool
{
    return in_array($model, [
        'User', 'Customer', 'Product', 'Supplier', 'Order',
    ], true);
}

echo "\nDone! Generated CRUD files for " . count($definitions) . " models.\n";

# FILE: ./StoreCategoryRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:product,material',
        ];
    }
}




# FILE: ./StoreCustomerRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:50',
            'address' => 'required|string',
        ];
    }
}




# FILE: ./StoreMaterialCategoryRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}




# FILE: ./StoreMaterialStockRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => 'nullable|uuid|exists:suppliers,id',
            'category_id' => 'required|uuid|exists:material_categories,id',
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'cost_per_unit' => 'required|numeric|min:0',
            'min_stock' => 'nullable|integer|min:0',
        ];
    }
}




# FILE: ./StoreMeasurementRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeasurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|uuid|exists:customers,id',
            'height' => 'nullable|integer|min:0',
            'weight' => 'nullable|integer|min:0',
            'neck' => 'nullable|integer|min:0',
            'chest' => 'nullable|integer|min:0',
            'waist' => 'nullable|integer|min:0',
            'hip' => 'nullable|integer|min:0',
            'shoulder' => 'nullable|integer|min:0',
            'sleeve_length' => 'nullable|integer|min:0',
            'shirt_length' => 'nullable|integer|min:0',
            'pants_length' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ];
    }
}




# FILE: ./StoreOrderItemRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|uuid|exists:orders,id',
            'product_id' => 'required|uuid|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }
}




# FILE: ./StoreOrderRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice' => 'required|string|max:255|unique:orders,invoice',
            'customer_id' => 'required|uuid|exists:customers,id',
            'measurement_id' => 'nullable|uuid|exists:measurements,id',
            'order_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:order_date',
            'pickup_date' => 'nullable|date',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'down_payment' => 'nullable|numeric|min:0',
            'remaining_payment' => 'required|numeric|min:0',
        ];
    }
}




# FILE: ./StorePaymentRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|uuid|exists:orders,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|in:cash,transfer,qris,other',
            'paid_at' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }
}




# FILE: ./StoreProductionTaskRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductionTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|uuid|exists:orders,id',
            'assigned_to' => 'nullable|uuid|exists:users,id',
            'stage' => 'required|in:cutting,sewing,finishing,quality_check,ready',
            'status' => 'required|in:pending,in_progress,done,revision',
            'started_at' => 'nullable|date',
            'finished_at' => 'nullable|date|after_or_equal:started_at',
            'notes' => 'nullable|string',
        ];
    }
}




# FILE: ./StoreProductRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|uuid|exists:categories,id',
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
        ];
    }
}




# FILE: ./StoreSupplierRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }
}




# FILE: ./StoreUserRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:50',
            'address' => 'required|string',
            'role' => 'required|in:admin,sales,tailor,production,manager',
        ];
    }
}




# FILE: ./UpdateCategoryRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:product,material',
        ];
    }
}




# FILE: ./UpdateCustomerRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:customers,email,$this->route('customer')',
            'phone' => 'sometimes|string|max:50',
            'address' => 'sometimes|string',
        ];
    }
}




# FILE: ./UpdateMaterialCategoryRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}




# FILE: ./UpdateMaterialStockRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => 'nullable|uuid|exists:suppliers,id',
            'category_id' => 'sometimes|uuid|exists:material_categories,id',
            'name' => 'sometimes|string|max:255',
            'quantity' => 'sometimes|numeric|min:0',
            'unit' => 'sometimes|string|max:50',
            'cost_per_unit' => 'sometimes|numeric|min:0',
            'min_stock' => 'nullable|integer|min:0',
        ];
    }
}




# FILE: ./UpdateMeasurementRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeasurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'sometimes|uuid|exists:customers,id',
            'height' => 'nullable|integer|min:0',
            'weight' => 'nullable|integer|min:0',
            'neck' => 'nullable|integer|min:0',
            'chest' => 'nullable|integer|min:0',
            'waist' => 'nullable|integer|min:0',
            'hip' => 'nullable|integer|min:0',
            'shoulder' => 'nullable|integer|min:0',
            'sleeve_length' => 'nullable|integer|min:0',
            'shirt_length' => 'nullable|integer|min:0',
            'pants_length' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ];
    }
}




# FILE: ./UpdateOrderItemRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'sometimes|uuid|exists:orders,id',
            'product_id' => 'sometimes|uuid|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
            'unit_price' => 'sometimes|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }
}




# FILE: ./UpdateOrderRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice' => 'sometimes|string|max:255|unique:orders,invoice,$this->route('order')',
            'customer_id' => 'sometimes|uuid|exists:customers,id',
            'measurement_id' => 'nullable|uuid|exists:measurements,id',
            'order_date' => 'sometimes|date',
            'due_date' => 'nullable|date|after_or_equal:order_date',
            'pickup_date' => 'nullable|date',
            'subtotal' => 'sometimes|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_price' => 'sometimes|numeric|min:0',
            'down_payment' => 'nullable|numeric|min:0',
            'remaining_payment' => 'sometimes|numeric|min:0',
        ];
    }
}




# FILE: ./UpdatePaymentRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'sometimes|uuid|exists:orders,id',
            'amount' => 'sometimes|numeric|min:0',
            'method' => 'sometimes|in:cash,transfer,qris,other',
            'paid_at' => 'sometimes|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }
}




# FILE: ./UpdateProductionTaskRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductionTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'sometimes|uuid|exists:orders,id',
            'assigned_to' => 'nullable|uuid|exists:users,id',
            'stage' => 'sometimes|in:cutting,sewing,finishing,quality_check,ready',
            'status' => 'sometimes|in:pending,in_progress,done,revision',
            'started_at' => 'nullable|date',
            'finished_at' => 'nullable|date|after_or_equal:started_at',
            'notes' => 'nullable|string',
        ];
    }
}




# FILE: ./UpdateProductRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'sometimes|uuid|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'base_price' => 'sometimes|numeric|min:0',
            'unit' => 'sometimes|string|max:50',
            'description' => 'nullable|string',
        ];
    }
}




# FILE: ./UpdateSupplierRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }
}




# FILE: ./UpdateUserRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,$this->route('user')',
            'password' => 'sometimes|string|min:8',
            'phone' => 'sometimes|string|max:50',
            'address' => 'sometimes|string',
            'role' => 'sometimes|in:admin,sales,tailor,production,manager',
        ];
    }
}




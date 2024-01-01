<?php

namespace App\Http\Requests\v1\ProductWarehouse;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductWarehouseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'stock' => 'required|integer',
            'min_stock_notify' => 'required|integer'
        ];
    }
}

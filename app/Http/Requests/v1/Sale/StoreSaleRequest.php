<?php

namespace App\Http\Requests\v1\Sale;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            'client_id' => 'required|exists:clients,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'data' => 'required|array',
            'data.*.id' => 'required|exists:products,id',
            'data.*.quantity' => 'required|min:1|numeric',
            'data.*.selling_price' => 'required|min:0.01|numeric',
            'payment_id' => 'required|exists:payments,id'
        ];
    }
}

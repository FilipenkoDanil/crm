<?php

namespace App\Http\Requests\v1\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'title' => 'string|min:1|max:256',
            'barcode' => 'nullable|string|max:20',
            'code' => 'nullable|string|max:20',
            'image' => 'nullable|file|max:4098',
            'category_id' => 'exists:categories,id',
            'purchase_price' => 'nullable|numeric',
            'selling_price' => 'nullable|numeric',
            'deleteImage' => 'nullable|boolean'
        ];
    }
}

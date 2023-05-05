<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'product_id' => 'required|exists:products,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'vendor_id' => 'nullable|exists:users,id',
            'product_name' => 'required|string|max:255',
            'product_qty' => 'required|integer|min:1',
            'product_tags' => 'nullable|string|max:255',
            'product_size' => 'nullable|string|max:50',
            'product_color' => 'nullable|string|max:50',
            'selling_price' => 'required|numeric',
            'product_code' => 'required|numeric',
            'discount_price' => 'nullable|numeric|lt:selling_price',
            'short_desc' => 'required|string',
            'long_desc' => 'required|string',
            // 'product_thambnail' => 'required|image|max:2048',
            // 'multi_img' => 'required|array',
            // 'multi_img.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'hot_deals' => 'nullable|boolean',
            'featured' => 'nullable|boolean',
            'special_offer' => 'nullable|boolean',
            'special_deals' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ];
    }
    public function messages()
    {
        return [
            'discount_price.lt' => 'The discount price must be less than the product price',
        ];
    }
}

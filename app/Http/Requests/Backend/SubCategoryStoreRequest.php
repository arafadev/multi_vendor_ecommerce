<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryStoreRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'subcategory_name' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'The category field is required.',
            'category_id.exists' => 'You must select category.',
            'subcategory_name.required' => 'The subcategory name field is required.',
            'subcategory_name.string' => 'The subcategory name field must be a string.',
        ];
    }
}

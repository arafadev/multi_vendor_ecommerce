<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
            'user_id' => 'integer|exists:users,id',
            'division_id' => 'integer|exists:ship_divisions,id',
            'district_id' => 'integer|exists:ship_districts,id',
            'state_id' => 'integer|exists:ship_states,id',
            'name' => 'string|nullable',
            'email' => 'string|nullable',
            'phone' => 'nullable|integer|digits:11',
            'adderss' => 'string|nullable|max:150',
            'post_code' =>'integer|max:6',
            'notes' => 'nullable|string|max:250',
        ];
    }
}

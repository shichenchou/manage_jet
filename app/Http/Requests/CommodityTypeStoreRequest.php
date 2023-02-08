<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommodityTypeStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => '類型名稱為必填',
        ];
    }
    public function filters()
    {
        return [
            'name' => 'trim|escape',
            'content' => 'trim|escape',
            'remark' => 'trim|escape',
        ];
    }
}

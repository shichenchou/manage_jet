<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommodityItemStoreRequest extends FormRequest
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
            'type_id' => 'required|int|min:0|not_in:0',
            //'photo' => 'image|max:1024',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => '商品名稱為必填',
            'type_id.required' => '請輸入商品類型',
            'type_id.not_in' => '請輸入商品類型',
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

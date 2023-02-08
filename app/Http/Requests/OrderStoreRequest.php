<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest {
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'name' => 'required|string|max:255',
            'ig' => 'required|string|max:255',
            'adress' => 'required|string',
            'phone' => 'required|string',
            'basic_price' => 'int|min:0',
            'item_price' => 'int|min:0',
		];
	}
	public function messages() {
		return [
			'name.required' => '姓名為必填',
            'ig.required' => 'IG為必填',
            'adress.required' => '地址為必填',
            'phone.required' => '電話為必填',
            'basic_price.min' => '請檢查設計費欄位',
            'item_price.min' => '請確認加購商品價錢',
		];
	}
	public function filters() {
		return [
			'name' => 'trim|escape',
			'ig' => 'trim|escape',
			'adress' => 'trim|escape',
            'phone' => 'trim|escape',
		];
	}
}

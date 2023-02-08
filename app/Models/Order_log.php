<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_log extends Model {
	use HasFactory;

    /**
     * 白名單，可以被批量賦值的欄位
     * @var array
     */
    protected $fillable = [
        'order_id',
        'status'
    ];

	/**
	 * 將資料屬性做轉換
	 * @var array
	 */
	protected $casts = [
		'created_at' => 'datetime:Y-m-d H:i:s',
		'updated_at' => 'datetime:Y-m-d H:i:s',
	];

    //多對一
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}

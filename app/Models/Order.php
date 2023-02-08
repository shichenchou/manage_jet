<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * 白名單，可以被批量賦值的欄位
     * @var array
     */
    protected $fillable = [
        'name',
        'ig',
        'basic_price',
        'item_price',
        'photo',
        'phone',
        'adress',
        'status',
        'item_data',
        'buy_data',
        'remark'
    ];

     /**
     * 一對多
     */
    public function logs()
    {
        return $this->hasMany(Order_log::class, 'order_id', 'id');
    }


    /**
     * 將資料屬性做轉換
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

}

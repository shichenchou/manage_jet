<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commodity_item extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type_id',
        'price',
        'photo',
        'remark',
        'sort',
        'display'
    ];

    /**
     * 將資料屬性做轉換
     *
     * @var array
     */
    protected $casts = [
        //'display' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];

    //多對一
    public function type()
    {
        return $this->belongsTo(Commodity_type::class)->withTrashed();
    }
}

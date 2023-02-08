<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commodity_type extends Model
{
    use SoftDeletes;
    use HasFactory;

    /**
     * 白名單，可以被批量賦值的欄位
     * @var array
     */
    protected $fillable = [
        'name',
        'content',
        'remark',
        'sort',
        'display'
    ];

    /**
     * 將資料屬性做轉換
     * @var array
     */
    protected $casts = [
        'display' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * 一對多
     */
    public function items()
    {
        return $this->hasMany(Commodity_item::class, 'type_id', 'id');
    }

    /**
     * 取完整文字
     */
    public function getContentRemarkAttribute()
    {
        $txt = '';
        if ($this->content != '') {
            $txt .= $this->content;
        }
        if ($this->remark != '') {
            $txt .= '(' . $this->content . ')';
        }
        return $txt;
    }

    public function getCheckedAttribute()
    {
        $txt = '';
        if ($this->display) {
            $txt = 'checked';
        }
        return $txt;
    }
}

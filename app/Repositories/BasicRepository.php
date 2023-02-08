<?php

/**
 * 常用
 */

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class BasicRepository
{
    //列表排序
    public function OrderList($query, $order_by)
    {
        if (is_string($order_by) && in_array($order_by, ['asc', 'desc'])) {
            $query->orderBy('sort', $order_by);
        } else if (is_array($order_by)) {
            foreach ($order_by as $col => $val) {
                $query = $query->orderBy($col, $val);
            }
        }
        return $query;
    }
    //搜尋要顯示的
    public function IsDisplay($query, $display = null)
    {
        if (is_int($display)) {
            return $query->where('display', '=', $display);
        }
    }
    //是否包含已刪除
    public function isDelete($query, $is_delete = 0)
    {
        if ($is_delete == 1) {
            return $query->withTrashed();
        }
        return $query;
    }
    //搜尋ID
    public function ById($query, $id)
    {
        if (is_array($id)) {
            return $query->whereIn('id', $id);
        } else {
            return $query->where('id', '=', $id);
        }

    }
    //關鍵字
    public function ByKeyword($query, $keyword, $cols = ['name'])
    {
        foreach ($cols as $col) {
            $query = $query->orWhere($col, 'like', '%' . $keyword . '%');
        }
        return $query;
    }
    //檢查物件存在並建立物件
    public function getModel($id)
    {
        return $this->ById($this->query, $id)->get($this->field['id'])->first();
    }
    /**
     * 刪除資料
     * $id
     */
    public function deleteById($id)
    {
        $model = $this->getModel((int) $id);

        if (empty($model)) {
            return false;
        }
        $model->delete();

        if (!$model->trashed()) {
            return false;
        }
        return true;
    }
    /**
     * 更新資料
     * $id
     */
    public function updateById($id, $data)
    {
        $model = $this->getModel((int) $id);

        if (empty($model)) {
            return false;
        }
        return $model->update($data);
    }

    public function getOrder($order_str = 'sort|asc')
    {
        $order_str = $order_str == '' ? 'sort|asc' : $order_str;
        $return = [];
        $order_str = strtolower($order_str);
        $orders = explode(',', $order_str);
        foreach ($orders as $order) {
            $order_tmp = explode('|', $order);
            $col = isset($order_tmp[0]) ? $order_tmp[0] : 'sort';
            $order = isset($order_tmp[1]) && in_array($order_tmp[1], ['asc', 'desc']) ? $order_tmp[1] : 'asc';
            if (!isset($return[$col])) {
                $return[$col] = $order;
            }
        }
        return $return;
    }
    //測試SQL
    public function debugTest($query)
    {
        DB::listen(function ($query) {
            var_dump($query->sql);
        });
    }

    //批量更新
    public function updateBatch($multipleData = [], $table = '')
    {
        try {
            if (empty($multipleData)) {
                throw new \Exception("數據不能爲空");
            }
            $tableName = DB::getTablePrefix() . $table; // 表名
            $firstRow = current($multipleData);

            $updateColumn = array_keys($firstRow);
            // 默認以id爲條件更新，如果沒有ID則以第一個字段爲條件
            $referenceColumn = isset($firstRow['id']) ? 'id' : current($updateColumn);
            unset($updateColumn[0]);
            // 拼接sql語句
            $updateSql = "UPDATE " . $tableName . " SET ";
            $sets = [];
            $bindings = [];
            foreach ($updateColumn as $uColumn) {
                $setSql = "`" . $uColumn . "` = CASE ";
                foreach ($multipleData as $data) {
                    $setSql .= "WHEN `" . $referenceColumn . "` = ? THEN ? ";
                    $bindings[] = $data[$referenceColumn];
                    $bindings[] = $data[$uColumn];
                }
                $setSql .= "ELSE `" . $uColumn . "` END ";
                $sets[] = $setSql;
            }
            $updateSql .= implode(', ', $sets);
            $whereIn = collect($multipleData)->pluck($referenceColumn)->values()->all();
            $bindings = array_merge($bindings, $whereIn);
            $whereIn = rtrim(str_repeat('?,', count($whereIn)), ',');
            $updateSql = rtrim($updateSql, ", ") . " WHERE `" . $referenceColumn . "` IN (" . $whereIn . ")";
            // 傳入預處理sql語句和對應綁定數據
            return DB::update($updateSql, $bindings);
        } catch (\Exception $e) {

            return false;
        }
    }
}

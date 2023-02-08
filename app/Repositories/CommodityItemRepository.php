<?php

namespace App\Repositories;

use App\Models\Commodity_item;

class CommodityItemRepository extends BasicRepository implements CommodityItemInterface
{
    public $query;
    public $field;

    public function __construct()
    {
        $this->query = Commodity_item::query();
        //欄位
        $this->field = array(
            'list' => ['id', 'type_id', 'name', 'photo', 'price', 'remark', 'sort', 'display'],
            'price' => ['id', 'type_id', 'name', 'price'],
            'name' => ['id', 'name', 'photo'],
            'row' => ['id', 'type_id', 'name', 'photo', 'price', 'remark'],
            'id' => ['id'],
            '*' => ['*'],
        );
    }

    /**
     * 取得商品價錢
     *
     * @param [array] $search
     * @return Object
     */
    public function getPrice($search = [])
    {
        $search['order_by'] = 'sort|ASC,id|DESC';
        return $this->getData($search, 'price', false);
    }

    /**
     * 取得商品列表
     *
     * @param [array] $search
     * @param boolean $with 是否取得商品類別
     * @return Object
     */
    public function getItemList($search, $with = true)
    {
        return $this->getData($search, 'list', $with);
    }
    /**
     * 取商品名稱
     *
     * @param [int] $id
     * @return array
     */
    public function getItemNameById($id)
    {
        $get = $this->getData(['id' => (int) $id, 'display' => 1], 'name');
        $result = array();
        $result[$id] = isset($get['name']) ? $get['name'] : '';
        return $result;
    }
    /**
     * 取單筆商品
     *
     * @param [int] $id
     * @return Object
     */
    public function getItemById($id)
    {
        return $this->getData(['id' => (int) $id, 'display' => 1], 'row', false);
    }
    /**
     * 新增商品
     *
     * @param [array] $data
     */
    public function create($data)
    {
        return Commodity_item::create($data);
    }

    /**
     * 取得資料
     * @search array 篩選欄位條件
     * @type string 類型 list列表,row單筆,id
     * 取類型：true 取得 false 不取得
     */

    /**
     * 取得商品資料
     *
     * @param array $search
     * @param string $type
     * @param boolean $withType 是否取得商品類別資料
     */
    private function getData($search = [], $type = 'list', $withType = false)
    {

        $query = $this->query;

        //欄位
        $field = isset($this->field[$type]) ? $this->field[$type] : $this->field['id'];

        foreach ($search as $key => $val) {
            if (is_string($val) && trim($val) === '') {
                continue;
            }
            if (is_array($val) && empty($val)) {
                continue;
            }
            if (is_null($val)) {
                continue;
            }
            switch ($key) {
                    //顯示
                case 'display':
                    $query = $this->Isdisplay($query, (int) $val);
                    break;
                    //包含已刪除
                case 'is_delete':
                    $query = $this->isDelete($query, (int) $val);
                    break;
                case 'id':
                    $query = $this->ById($query, $val);
                    break;
                    //排序
                case 'order_by':
                    $sort = $this->getOrder($val);
                    $query = $this->OrderList($query, $sort);
                    break;
                    //關鍵字
                case 'keyword':
                    $query = $this->ByKeyword($query, $val, ['name', 'remark']);
                    break;
                    //類型
                case 'type_id':
                    $query = $query->where($key, '=', $val);
                    break;
                    //取得已刪除的商品
                case 'isDelete':
                    $query = $this->isDelete($query, $val);
                    break;
                default:

                    break;
            }
        }

        //類型一起取
        if ($withType) {
            $query = $this->WithType($query);
        }

        $data = array();

        if ($type == 'list' || $type = 'price') {
            //分頁模式
            if (isset($fitter['limit'])) {
                //筆數
                $limit = isset($fitter['limit']) ? (int) $fitter['limit'] : 10;
                $data = $query->paginate($limit, $field);
            } else {
                $data = $query->get($field);
            }
        } else {
            $data = $query->get($field)->first();
        }

        return $data;
    }

    //取出分類
    private function WithType($query)
    {
        return $query->with('type:id,name,content,remark');
    }
}

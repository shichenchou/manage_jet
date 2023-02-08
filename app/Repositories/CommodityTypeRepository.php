<?php

namespace App\Repositories;

use App\Models\Commodity_type;


class CommodityTypeRepository extends BasicRepository  implements CommodityTypeInterface
{
    public $query;
    public $field;

    public function __construct()
    {
        $this->query = Commodity_type::query();
        //欄位
        $this->field = array(
            'list' => ['id', 'name', 'content', 'remark', 'sort', 'display'],
            'name' => ['id', 'name'],
            'row' => ['id', 'name', 'content', 'remark'],
            'id' => ['id'],
            '*' => ['*']
        );
    }

    /**
     * 取得商品列表
     *
     * @param [array] $search 搜尋條件
     * @return Object
     */
    public function getTypeList($search)
    {
        return $this->getData($search, 'list');
    }
    /**
     * 取得類別名稱
     *
     * @param [int] $id
     * @return array
     */
    public function getTypeNameById($id)
    {
        $get = $this->getData(['id' => (int)$id, 'display' => 1], 'name');
        $result = array();
        $result[$id] = isset($get['name']) ? $get['name'] : '';
        return $result;
    }

    /**
     * 取得單筆商品類別
     *
     * @param [int] $id
     * @return Object
     */

    public function getTypeById($id)
    {
        return $this->getData(['id' => (int)$id, 'display' => 1], 'row');
    }

    /**
     * 新增商品類別
     *
     * @param [array] $data
     */
    public function create($data=[])
    {
        return Commodity_type::create($data);
    }

    /**
     * 取得商品類別底層
     *
     * @param array $search
     * @param string $type
     */
    private function getData($search = [], $type = 'list')
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
            switch ($key) {
                    //顯示
                case 'display':
                    $query = $this->Isdisplay($query, (int)$val);
                    break;
                    //包含已刪除
                case 'is_delete':
                    $query = $this->isDelete($query, (int)$val);
                    break;
                case 'id':
                    $query = $this->ById($query, (int)$val);
                    break;
                    //排序
                case 'order_by':
                    $sort = $this->getOrder($val);
                    $query = $this->OrderList($query, $sort);
                    break;
                    //關鍵字
                case 'keyword':
                    $query = $this->ByKeyword($query, $val, ['name', 'content', 'remark']);
                    break;
                default:

                    break;
            }
        }

        $data = array();

        if ($type == 'list') {
            //分頁模式
            if (isset($search['limit'])) {
                //筆數
                $limit = isset($search['limit']) ? (int)$search['limit'] : 10;
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
    private function WithItem($query)
    {
        return $query->with('items:id,name,content,remark');
    }
}

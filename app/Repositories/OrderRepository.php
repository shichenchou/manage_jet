<?php

/**
 * 訂單CRUD
 */

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BasicRepository implements OrderInterface
{

    public $query;
    public $field;

    public function __construct()
    {

        $this->query = Order::query();

        //欄位
        $this->field = array(
            'id'        =>     ['id'],
            'row'        =>     ['id','basic_price','item_price','status'],
            'status'    =>     ['id', 'status'],
            'list'      =>     ['*'],
        );
    }

    /**
     * 取多筆訂單
     *
     * @param [array] $search 搜尋條件
     * @return Object
     */
    public function getOrderList($search = [])
    {
        return $this->getData($search, 'list');
    }

    /**
     * 取單筆訂單
     *
     * @param [int] $id
     * @return Object
     */
    public function getOrderById($id = 0)
    {
        return $this->getData(['id' => (int) $id], 'row');
    }
    /**
     * 取訂單狀態
     *
     * @param [int] $id
     * @return Object
     */
    public function getStatusById($id)
    {
        return $this->getData(['id' => (int) $id], 'status');
    }
    /**
     * 新增訂單資料
     *
     * @param [array] $data
     */
    public function create($data)
    {
        return Order::create($data);
    }

    /**
     * 取得資料底層函示
     *
     * @param array  $search
     * @param string $type : 欄位類型
     * @return Object
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
            if(is_null($val)){
                continue;
            }
            switch ($key) {
                case 'id':
                    $query = $this->ById($query, $val);
                    break;
                    //排序
                case 'order_by':
                    $sort = $this->getOrder($val);
                    $query = $this->OrderList($query, $sort);
                    break;
                case 'status':
                    $query->where('status', '=', $val);
                    break;
                    //關鍵字
                case 'keyword':
                    if ($val == '' || is_null($val)) {
                        break;
                    }
                    $query = $this->ByKeyword($query, $val, ['ig', 'name', 'phone']);

                    break;
                case 'user_id':
                    $query->where('user_id', '=', $val);
                    break;
                default:

                    break;
            }
        }

        $query = $this->WithLog($query);

        $data = array();

        if ($type == 'list') {
            //分頁模式
            if (isset($search['limit'])) {
                //筆數
                $limit = isset($search['limit']) ? (int) $search['limit'] : 10;
                $data = $query->paginate($limit, $field);
            } else {
                $data = $query->get($field);
            }
        } else {
            $data = $query->get($field)->first();
        }

        return $data;
    }

    //取出紀錄
    private function WithLog($query)
    {
        return $query->with('logs');
    }
}

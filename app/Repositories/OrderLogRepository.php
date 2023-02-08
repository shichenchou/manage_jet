<?php
/**
 * 訂單記錄
 */
namespace App\Repositories;

use App\Models\Order_log;

class OrderLogRepository extends BasicRepository implements OrderLogInterface
{
    public $query;
    public $field;

    public function __construct()
    {
        $this->query = Order_log::query();
        //欄位
        $this->field = array(
            'id' => ['id'],
            'list' => ['*'],
        );
    }
    /**
     * 取得訂單記錄
     *
     * @param [array] $search
     * @return Object
     */
    public function getOrderLogList($search)
    {
        return $this->getData($search, 'list');
    }

    /**
     * 新增訂單記錄
     *
     * @param [array] $data
     */
    public function create($data)
    {
        return Order_log::create($data);
    }

    /**
     * 取得訂單記錄 底層
     *
     * @param array $search
     * @param string $type
     * @return void
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
                    //狀態
                    //case 'status':
                    //   $query = $this->ByStatus($query,$val);
                    //  break;
                case 'id':
                    $query = $this->ById($query, (int) $val);
                    break;
                    //排序
                case 'order_by':
                    $sort = $this->getOrder($val);
                    $query = $this->OrderList($query, $sort);
                    break;
                default:

                    break;
            }
        }

        $data = array();

        if ($type == 'list') {
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
}

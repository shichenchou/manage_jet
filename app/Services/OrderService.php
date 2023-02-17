<?php

/**
 * 訂單處理
 */

namespace App\Services;

use App\Http\Requests\OrderStoreRequest;
use App\Repositories\CommodityItemRepository;
use App\Repositories\OrderLogRepository;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class OrderService extends BasicService
{

    protected $OrderRepository;
    protected $OrderLogRepository;
    protected $CommodityItemRepository;

    public function __construct(OrderRepository $OrderRepository, OrderLogRepository $OrderLogRepository, CommodityItemRepository $CommodityItemRepository)
    {
        $this->OrderRepository = $OrderRepository;
        $this->OrderLogRepository = $OrderLogRepository;
        $this->CommodityItemRepository = $CommodityItemRepository;
    }

    /**
     * 刪除訂單By id
     *
     * @param [int] $id Order.id
     * @return bool
     */
    public function destroyDataForView($id)
    {

        return $this->OrderRepository->deleteById($id);
    }

    /**
     * 建立訂單前 資料前處理
     *
     * @param [array] $data
     * @return array
     */
    private function setData($data)
    {
        $keys = [
            'ig' => 'str',
            'name' => 'str',
            'phone' => 'str',
            'basic_price' => 'int',
            'item_price' => 'int',
            'adress' => 'str',
            'item' => 'array',
            'buy' => 'array',
            'photo' => 'str',
        ];

        $result = [];

        //檢查
        if (isset($data['buy']) && !empty($data['buy'])) {
            $item_keys = [];
            foreach (array_values($data['buy']) as $value) {
                $item_keys = array_merge($item_keys, array_keys($value));
            }
            $search = [
                'id' => $item_keys,
            ];
            //取得商品
            $data['item'] = $this->getPrice($search);

            //計算價錢
            $data['item_price'] = 0;
            foreach ($data['buy'] as $type_id => $item_data) {
                foreach ($item_data as $item_id => $item_count) {
                    if (isset($data['item'][$type_id][$item_id])) {
                        $data['item_price'] += $data['item'][$type_id][$item_id] * $item_count;
                    }
                }
            }
        }

        foreach ($keys as $keyName => $keyType) {
            switch ($keyType) {
                case 'str':
                    $result[$keyName] = isset($data[$keyName]) ? (string) $data[$keyName] : '';
                    break;
                case 'int':
                    $result[$keyName] = isset($data[$keyName]) ? (int) $data[$keyName] : 0;
                    break;
                case 'array':
                    $result[$keyName . '_data'] = isset($data[$keyName]) ? json_encode($data[$keyName]) : '';
                    break;
                default:
                    break;
            }
        }

        return $result;
    }

    /**
     * 取得商品價錢
     *
     * @param [array] $search 搜尋條件
     * @return array
     */
    private function getPrice($search = [])
    {
        $result = $this->CommodityItemRepository->getPrice($search, true)->toArray();

        $data = array();

        foreach ($result as $row) {
            $data[$row['type_id']][$row['id']] = $row['price'];
        }

        return $data;
    }

    /**
     * 取得所有商品.類別(包含已刪除)
     * with
     *
     * @return array
     */
    public function getItem()
    {
        $search = array(
            'isDelete' => 1,
        );
        $result = $this->CommodityItemRepository->getItemList($search, true)->toArray();

        $data = array();

        foreach ($result as $row) {
            $data['item'][$row['id']] = $row;
            if (!isset($data['type'][$row['type_id']])) {
                $data['type'][$row['type_id']] = $row['type'];
            }
        }

        return $data;
    }

    /**
     * 新增或更新訂單資料
     *
     * @param [int] $id 0:新增 $id > 0 更新
     * @param [array] $data 資料
     * @return array
     */
    public function setDataForView($id, $data)
    {
        $return = array(
            'error' => true,
            'msg' => []
        );
        $request = new OrderStoreRequest();

        $data = $this->setData($data);

        //基本資料檢查
        $validator = Validator::make($data, $request->rules(), $request->messages());
        if ($validator->fails()) {
            $msg_arr = $validator->errors()->toArray();
            $tmp = [];
            foreach ($msg_arr as $key => $msgs) {
                foreach ($msgs as $msg) {
                    $tmp[] = $msg;
                }
            }
            $return['msg'] = implode('<br>', $tmp);
            return $return;
        }

        if ($id == 0) {

            $status = 'draft';
            $data['status'] = $status;
            $data['user_id'] = Auth::id();

            $set = $this->OrderRepository->create($data)->toArray();

            $new_id = isset($set['id']) ? (int) $set['id'] : 0;

            if ($new_id > 0) {
                $log = [
                    'order_id' => $new_id,
                    'status' => $status,
                ];

                $set_log = $this->OrderLogRepository->create($log);
                if (!$set_log) {
                    $return['msg'][] = '新增失敗';
                    return $return;
                }
            } else {
                $return['msg'][] = '新增失敗';
                return $return;
            }
            $return['error'] = false;
            $return['msg'][] = '新增成功';
            return $return;
        } else {
            $set = $this->OrderRepository->updateById($id, $data);
            if ($set) {
                $return['error'] = false;
                $return['msg'][] = '更新成功';
                return $return;
            } else {
                $return['msg'][] = '更新失敗';
                return $return;
            }
        }
    }

    /**
     * 狀態變更
     *
     * @param [int] $id 訂單ID
     * @return bool
     */
    public function changeStatus($id)
    {
        $get_status = $this->OrderRepository->getStatusById($id)->toArray();
        $status = isset($get_status['status']) ? $get_status['status'] : '';
        if ($status == '') {
            return false;
        }

        $get_new_status = [
            'draft' => 'submitted',
            'submitted' => 'finished',
            'succeed' => 'finished',
        ];

        $new_status = isset($get_new_status[$status]) ? $get_new_status[$status] : '';

        if ($new_status == '') {
            return false;
        }
        $data = ['status' => $new_status];
        $set = $this->OrderRepository->updateById($id, $data);

        if ($set) {
            $data['order_id'] = $id;
            return $this->OrderLogRepository->create($data);
        } else {
            return false;
        }
    }
    public function changeStatusToSuccess($id)
    {
        $get_status = $this->OrderRepository->getStatusById($id)->toArray();
        $status = isset($get_status['status']) ? $get_status['status'] : '';
        if ($status == '') {
            return false;
        }

        $data = ['status' => 'succeed'];
        $set = $this->OrderRepository->updateById($id, $data);

        if ($set) {
            $data['order_id'] = $id;
            return $this->OrderLogRepository->create($data);
        } else {
            return false;
        }
    }
    /**
     * 取消申請單
     *
     * @param [int] $id 訂單ID
     * @return bool
     */
    public function cancelStatus($id)
    {
        $get_status = $this->OrderRepository->getStatusById($id)->toArray();
        $status = isset($get_status['status']) ? $get_status['status'] : '';
        if ($status == '') {
            return false;
        }

        $status_check = [
            'draft' => 'canceled',
            'submitted' => 'draft',
        ];

        $new_status = isset($status_check[$status]) ? $status_check[$status] : '';

        if ($new_status == '') {
            return false;
        }
        $data = ['status' => $new_status];
        $set = $this->OrderRepository->updateById($id, $data);

        if ($set) {
            $data['order_id'] = $id;
            return $this->OrderLogRepository->create($data);
        } else {
            return false;
        }
    }
    /*
    public function updateStatus($id, $status)
    {
    $status_arr = $this->getStatusTxt();
    if (!isset($status_arr[$status])) {
    return false;
    }
    $data = ['status' => $status];
    return $this->OrderRepository->updateById($id, $data);
    }*/

    /**
     * 取單筆訂單
     *
     * @param [int] $id
     * @return array
     */
    public function getRowForView($id)
    {
        return $this->OrderRepository->getOrderById($id)->toArray();
    }

    /**
     * 訂單列表(分頁)
     *
     * @param [array] $search 搜尋條件
     * @return array
     */
    public function getListForView($search)
    {
        $search['order_by'] = 'created_at|DESC,updated_at|Desc,id|DESC';
        $search['limit'] = 10;
        $result = $this->OrderRepository->getOrderList($search)->toArray();

        $data = array();
        foreach ($result['data'] as $key => $val) {
            $val['total_price'] = $val['basic_price'] + $val['item_price'];
            $val['item'] = isset($val['item_data']) && $val['item_data'] != '' ? json_decode($val['item_data'], true) : [];
            $val['buy'] = isset($val['buy_data']) && $val['buy_data'] != '' ? json_decode($val['buy_data'], true) : [];
            $data[$val['id']] = $val;
        }
        $result['data'] = $data;
        return $result;
    }

    /**
     * 匯出訂單列表
     *
     * @param [array] $search 搜尋條件
     * @return array
     */
    public function getListForExport($search)
    {
        $search['order_by'] = 'created_at|DESC,updated_at|Desc,id|DESC';
        $result = $this->OrderRepository->getOrderList($search)->toArray();

        $get_item = $this->getItem();
        $types = isset($get_item['type']) ? $get_item['type'] : [];
        $items = isset($get_item['item']) ? $get_item['item'] : [];

        $status_txt = $this->getStatusTxt();

        $data = array();
        foreach ($result as $key => $val) {
            $val['total_price'] = $val['basic_price'] + $val['item_price'];
            $val['item'] = isset($val['item_data']) && $val['item_data'] != '' ? json_decode($val['item_data'], true) : [];
            $val['buy'] = isset($val['buy_data']) && $val['buy_data'] != '' ? json_decode($val['buy_data'], true) : [];
            $item_txt = '';
            foreach ($val['item'] as $type_key => $item_data) {
                $item_txt .= $types[$type_key]['name'] . "\n";
                foreach ($item_data as $item_id => $price) {
                    $count = $val['buy'][$type_key][$item_id];
                    $item_txt .= $items[$item_id]['name'] . ':' . $count . '個;' . '單價 $' . $price . "\n";
                }
            }
            $data[$key] = [
                $val['ig'],
                $val['name'],
                $val['adress'],
                $val['phone'],
                $val['basic_price'],
                $val['item_price'],
                $val['total_price'],
                $item_txt,
                $val['created_at'],
                $status_txt[$val['status']],
            ];
        }
        $result['data'] = $data;
        return $result;
    }

    /**
     * 訂單狀態名稱
     *
     * @return array
     */
    public function getStatusTxt()
    {
        return [
            'draft' => '草稿',
            'submitted' => '待付款',
            'succeed' => '付款完成',
            'finished' => '訂單完成',
            'canceled' => '取消',
        ];
    }
}

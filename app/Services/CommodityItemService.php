<?php

/**
 * 商品處理
 */

namespace App\Services;

use App\Http\Resources\CommodityItemResource;
use App\Repositories\CommodityItemRepository;

class CommodityItemService extends BasicService
{
    protected $CommodityItemRepository;

    public function __construct(CommodityItemRepository $CommodityItemRepository)
    {
        $this->CommodityItemRepository = $CommodityItemRepository;
    }

    /**
     * API新增商品
     *
     * @param [array] $data
     * @return API回傳格式
     */
    public function storeData($data)
    {
        $commodity_item = $this->CommodityItemRepository->create($data);
        if (!$commodity_item) {
            $this->return['msg'] = '新增失敗';
            return $this->returnData();
        }
        $this->status = 201;
        $this->return['error'] = false;
        $this->return['msg'] = '新增成功';
        $this->return['data'] = $commodity_item;
        return $this->returnData();
    }

    /**
     * API刪除商品
     *
     * @param [int] $id
     * @return API回傳格式
     */
    public function destroyData($id)
    {

        $set = $this->CommodityItemRepository->deleteById($id);

        if (!$set) {
            $this->return['error'] = true;
            $this->return['msg'] = '刪除失敗';
            $this->return['data'] = $id;
        } else {
            $this->status = 204;
            $this->return['error'] = false;
            $this->return['msg'] = '刪除成功';
            $this->return['data'] = $id;
        }

        return $this->returnData();
    }
    /**
     * API更新商品資料
     *
     * @param [int] $id
     * @param [array] $data
     * @return API回傳格式
     */
    public function updateData($id, $data = [])
    {

        $set = $this->CommodityItemRepository->updateById($id, $data);

        if (!$set) {
            $this->return['msg'] = '更新失敗';
            return $this->returnData();
        }

        $this->return['error'] = false;
        $this->return['msg'] = '更新成功';
        $this->return['data'] = $data;

        return $this->returnData();
    }

    /**
     * API取單筆商品
     *
     * @param [type] $id
     * @return API回傳格式
     */
    public function getRow($id)
    {
        $data = $this->CommodityItemRepository->getItemById($id);

        if (empty($data)) {
            $this->return['msg'] = '取得失敗';
            return $this->returnData();
        }

        $this->status = 200;
        $this->return['error'] = false;
        $this->return['msg'] = '取得成功';
        $this->return['data'] = !is_null($data) ? $data : [];

        return $this->returnData();
    }

    /**
     * API取商品名稱
     *
     * @param [int] $id
     * @return API回傳格式
     */
    public function getName($id)
    {
        $data = $this->CommodityItemRepository->getItemNameById($id);

        if (empty($data)) {
            $this->return['msg'] = '取得失敗';
            return $this->returnData();
        }

        $this->status = 200;
        $this->return['error'] = false;
        $this->return['msg'] = '取得成功';
        $this->return['data'] = !is_null($data) ? $data : [];

        return $this->returnData();
    }

    /**
     * API取商品列表
     *
     * @param [type] $fitter 搜尋條件
     * @return void
     */
    public function getList($fitter)
    {
        $search = $this->getSearch($fitter, ['display', 'is_delete', 'order_by', 'keyword', 'type_id']);
        $search['limit'] = $this->limit;

        $data = $this->CommodityItemRepository->getItemList($search);

        $this->status = 200;
        $this->return['error'] = false;
        $this->return['msg'] = '取得成功';
        $this->return['data'] = new CommodityItemResource($data);
        return $this->returnData();
    }
    public function storeDataForView($data)
    {
        return $this->CommodityItemRepository->create($data);
    }
    /**
     * 取得商品列表
     *
     * @param [array] $search
     * @return array
     */
    public function getListForView($search)
    {
        //$search['limit'] = $this->limit;

        $search['order_by'] = 'display|DESC,sort|ASC,id|DESC';

        $result = $this->CommodityItemRepository->getItemList($search)->toArray();
        $data = array();
        $num = count($result);

        foreach ($result as $row) {
            $row['checked'] = $row['display'] == 1 ? 'checked' : '';
            $row['sort_display'] = $row['display'] == 0 ? 'none' : '';
            $row['num'] = $num;
            $data[$row['id']] = $row;
            $num--;
        }

        return $data;
    }
    /**
     * 取得商品列表(訂單用)
     *
     * @param [array] $search
     * @return array
     */
    public function getListForShop($search)
    {
        //$search['limit'] = $this->limit;

        $search['order_by'] = 'display|DESC,sort|ASC,id|DESC';

        $result = $this->CommodityItemRepository->getItemList($search, false)->toArray();
        $data = array();

        foreach ($result as $row) {
            $data[$row['type_id']][$row['id']] = $row;
        }

        return $data;
    }

    /**
     * 新增或更新商品資料
     *
     * @param [int] $id
     * @param [array] $data
     * @return bool
     */
    public function setDataForView($id, $data = [])
    {
        if ($id == 0) {
            return $this->CommodityItemRepository->create($data);
        } else {
            return $this->CommodityItemRepository->updateById($id, $data);
        }
    }
    /**
     * 更新多筆商品排序
     *
     * @param [array] $data
     * @return bool
     */
    public function updateSort($data)
    {
        return $this->CommodityItemRepository->updateBatch($data, 'commodity_items');
    }
    /**
     * 刪除商品資料
     *
     * @param [int] $id
     * @return bool
     */
    public function destroyDataForView($id)
    {

        return $this->CommodityItemRepository->deleteById($id);
    }
}

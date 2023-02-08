<?php

/**
 * 類別處理
 */

namespace App\Services;

use App\Http\Resources\CommodityTypeResource;
use App\Repositories\CommodityTypeRepository;

class CommodityTypeService extends BasicService
{
    protected $CommodityTypeRepository;

    public function __construct(CommodityTypeRepository $CommodityTypeRepository)
    {
        $this->CommodityTypeRepository = $CommodityTypeRepository;
    }

    /**
     * API新增類別資料
     *
     * @param [array] $data
     * @return API回傳格式
     */
    public function storeData($data)
    {
        $commodity_type = $this->CommodityTypeRepository->create($data);
        if (!$commodity_type) {
            $this->return['msg'] = '新增失敗';
            return $this->returnData();
        }
        $this->status = 201;
        $this->return['error'] = false;
        $this->return['msg'] = '新增成功';
        $this->return['data'] = $commodity_type;
        return $this->returnData();
    }

    /**
     * API刪除類別資料
     *
     * @param [int] $id
     * @return API回傳格式
     */
    public function destroyData($id)
    {

        $set = $this->CommodityTypeRepository->deleteById($id);

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
     * API更新類別資料
     *
     * @param [int] $id
     * @param [array] $data
     * @return API回傳格式
     */
    public function updateData($id, $data = [])
    {

        $set = $this->CommodityTypeRepository->updateById($id, $data);

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
     * API取單筆類別
     *
     * @param [int] $id
     * @return API回傳格式
     */
    public function getRow($id)
    {
        $data = $this->CommodityTypeRepository->getTypeById($id);

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
     * API取單筆類別名稱
     *
     * @param [int] $id
     * @return API回傳格式
     */
    public function getName($id)
    {
        $data = $this->CommodityTypeRepository->getTypeNameById($id);

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
     * API取類別列表(分頁模式)
     *
     * @param [type] $fitter 搜尋條件
     * @return API回傳格式
     */
    public function getList($fitter)
    {
        $search = array();
        $search = $this->getSearch($fitter, ['display', 'is_delete', 'order_by', 'keyword']);

        $search['limit'] = $this->limit;

        $data = $this->CommodityTypeRepository->getTypeList($search);

        $this->status = 200;
        $this->return['error'] = false;
        $this->return['msg'] = '取得成功';
        $this->return['data'] = new CommodityTypeResource($data);

        return $this->returnData();
    }

    /**
     * 刪除資料(前端)
     *
     * @param [int] $id
     * @return bool
     */
    public function destroyDataForView($id)
    {

        return $this->CommodityTypeRepository->deleteById($id);
    }

    /**
     * 新增/更新資料
     *
     * @param [int] $id
     * @param [array] $data
     * @return bool
     */
    public function setDataForView($id, $data)
    {
        if ($id == 0) {
            return $this->CommodityTypeRepository->create($data);
        } else {
            return $this->CommodityTypeRepository->updateById($id, $data);
        }
    }

    /**
     * 更新排序(多筆資料)
     *
     * @param [type] $data
     * @return bool
     */
    public function updateSort($data)
    {
        return $this->CommodityTypeRepository->updateBatch($data, 'commodity_types');
    }

    /**
     * 取類別單筆資料
     *
     * @param [int] $id
     * @return array
     */
    public function getRowForView($id)
    {
        return $this->CommodityTypeRepository->getTypeById($id)->toArray();
    }


    /**
     * 取類別
     *
     * @param array $search
     * @return array
     */
    public function getListForView($search = [])
    {

        //$search = $this->getSearch($fitter,['display','is_delete','order_by','keyword']);
        $search['order_by'] = 'display|DESC,sort|ASC,id|DESC';
        $result = $this->CommodityTypeRepository->getTypeList($search)->toArray();

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
}

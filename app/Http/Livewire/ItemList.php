<?php

namespace App\Http\Livewire;

use App\Http\Requests\CommodityItemStoreRequest;
use App\Services\CommodityItemService;
use App\Services\CommodityTypeService;
use Livewire\Component;
use Livewire\WithFileUploads;

class ItemList extends Component
{

    use WithFileUploads;

    private $CommodityItemService;
    private $CommodityTypeService;

    public $type_list;
    public $item_list;

    public $type_id;
    public $name = 0;
    public $price = 0;
    public $remark = '';
    public $modal_id = 0;
    public $photo;
    public $photo_old;

    protected $queryString = ['type_id'];

    public $assign = [
        'type_name' => '全部',
        'send_btn_name' => '新增',
    ];

    //顯示排序
    public $showSort = false;
    //Modal
    public $showingModal = false;
    public $showingDeleteModal = false;
    public $hasDisplay = true;

    protected $listeners = [
        'pageInit' => ['showSort'],
        'showModal' => ['showingModal', 'hasDisplay'],
        'hideModal' => ['showingModal', 'hasDisplay'],
        'showDeleteModal' => 'showingDeleteModal',
        'hideDeleteModal' => 'showingDeleteModal',
    ];

    public function booted(CommodityItemService $CommodityItemService, CommodityTypeService $CommodityTypeService)
    {
        $this->CommodityItemService = $CommodityItemService;
        $this->CommodityTypeService = $CommodityTypeService;

        $this->pageInit();
    }

    public function render()
    {

        return view('livewire.item-list', [
            'items' => $this->item_list,
            'types' => $this->type_list,
            'assign' => $this->assign,
        ]);
    }

    //畫面初始化
    public function pageInit()
    {

        $this->type_list = $this->CommodityTypeService->getListForView();

        //整理搜尋條件
        $search = $this->getSearch();

        $this->item_list = $this->CommodityItemService->getListForView($search);

        $this->showSort = is_null($this->type_id) ? false : true;
        $this->new_btn = is_null($this->type_id) ? false : true;

        $this->assign['type_name'] = isset($this->type_list[$this->type_id]['name']) ? $this->type_list[$this->type_id]['name'] : '全部';
    }

    //取得搜尋條件
    private function getSearch()
    {

        $this->type_id = isset($this->type_list[$this->type_id]) ? $this->type_id : null;

        return [
            'type_id' => $this->type_id, //類型
        ];
    }
    //返回
    public function backPage()
    {
        return redirect()->to('/type');
    }
    //開啟Modal
    public function showModal($id)
    {

        $this->resetFields();
        $this->showingModal = true;
        $this->hasDisplay = true;

        if ($id > 0) {

            $this->modal_id = $id;
            $this->loadModal();
            $this->assign['send_btn_name'] = '更新';
        }
    }

    //modal載入更新資料
    public function loadModal()
    {
        $result = $this->item_list[$this->modal_id];
        $this->name = $result['name'];
        $this->price = $result['price'];
        $this->remark = $result['remark'];
        $this->display = $result['display'];
        $this->photo_old = $result['photo'];
        $this->hasDisplay = (bool) $this->display;
    }

    //關閉Modal
    public function hideModal()
    {

        $this->showingModal = false;
        $this->resetFields();
    }
    //清除modal資料
    public function resetFields()
    {
        $this->modal_id = 0;
        $this->name = '';
        $this->price = 0;
        $this->remark = '';
        $this->photo = null;
        $this->assign['send_btn_name'] = '新增';
    }

    //新增/更新資料
    public function setData()
    {

        $request = new CommodityItemStoreRequest();
        $return_type = true;
        if($this->modal_id > 0 && is_null($this->type_id))
        {
            $old_data = $this->item_list[$this->modal_id];
            $this->type_id = $old_data['type_id'];
            $return_type = false;
        }

        $this->validate($request->rules());

        $data = $this->getData();
        $set = $this->CommodityItemService->setDataForView($this->modal_id, $data);

        $this->hideModal();

        if($return_type == false){
            return redirect()->to('/item' );
        }else{
            return redirect()->to('/item?type_id=' . $this->type_id);
        }

    }
    //取Modal資料
    public function getData()
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'remark' => $this->remark,
            'type_id' => $this->type_id,
            'photo' => is_string($this->photo_old) ? $this->photo_old : $this->photo->store('ItemImage', 'public'),
            'display' => (int) $this->hasDisplay,
        ];
    }
    //排序往上
    public function upSort($id, $num)
    {
        $list = $this->item_list;
        $count = count($list);
        if ($num == $count) {
            return;
        }

        $tmp = [];
        $i = 0;
        foreach ($list as $id => $val) {

            if ($num == $val['num']) {
                unset($val['num']);
                $tmp[$i] = $tmp[$i - 1];
                $tmp[$i - 1] = $val['id'];
            } else {
                unset($val['num']);
                $tmp[$i] = $val['id'];
            }

            $i++;
        }

        $update = [];
        foreach ($tmp as $new_num => $id) {
            $update[] = array(
                'id' => $id,
                'sort' => $new_num + 1,
            );
        }

        $set = $this->CommodityItemService->updateSort($update);

        $this->pageInit();
    }
    //排序往下
    public function downSort($id, $num)
    {
        $list = $this->item_list;
        $count = count($list);
        if ($num == 1) {
            return;
        }
        $tmp = [];
        $i = 0;
        $list = array_reverse($list);
        foreach ($list as $id => $val) {

            if ($num == $val['num']) {
                unset($val['num']);
                $tmp[$i] = $tmp[$i - 1];
                $tmp[$i - 1] = $val['id'];
            } else {
                unset($val['num']);
                $tmp[$i] = $val['id'];
            }

            $i++;
        }

        $update = [];
        $tmp = array_reverse($tmp);
        foreach ($tmp as $new_num => $id) {
            $update[] = array(
                'id' => $id,
                'sort' => $new_num + 1,
            );
        }

        $set = $this->CommodityItemService->updateSort($update);

        $this->pageInit();
    }
    public function photoCallBack()
    {
        $this->photo_old = null;
    }

    //顯示刪除Modal
    public function showDeleteModal($id)
    {
        $this->showingDeleteModal = true;
        $this->modal_id = $id;
        $this->loadModal();
        $this->assign['send_btn_name'] = '刪除';
    }
    //關閉刪除Modal
    public function hideDeleteModal()
    {

        $this->showingDeleteModal = false;
        $this->resetFields();
    }

    //刪除商品
    public function delete()
    {

        $set = $this->CommodityItemService->destroyDataForView($this->modal_id);

        $this->hideDeleteModal();
        return redirect()->to('/item?type_id=' . $this->type_id);
    }

    //篩選
    public function searchPage()
    {
        return redirect()->to('/item?type_id=' . $this->type_id);
    }
}

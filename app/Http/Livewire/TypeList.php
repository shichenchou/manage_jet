<?php

namespace App\Http\Livewire;

use App\Http\Requests\CommodityTypeStoreRequest;
use App\Services\CommodityTypeService;
//use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class TypeList extends Component
{

    //use LivewireAlert;

    private $CommodityTypeService;
    private $request;
    //新增modal開啟關閉
    public $showingModal = false;
    public $showingDeleteModal = false;
    public $hasDisplay = true;
    protected $listeners = [
        'showModal' => ['showingModal', 'hasDisplay'],
        'hideModal' => ['showingModal', 'hasDisplay'],
        'showDeleteModal' => 'showingDeleteModal',
        'hideDeleteModal' => 'showingDeleteModal',
    ];

    //modal data
    public $modal_id = 0;
    public $name = '';
    public $content = '';
    public $remark = '';
    public $display = 0;

    public $list = [];

    public $send_btn_name = '新增';


    public function booted(CommodityTypeService $CommodityTypeService)
    {
        $this->CommodityTypeService = $CommodityTypeService;
        $this->resetList();
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
            $this->send_btn_name = '更新';
        }
    }

    //關閉Modal
    public function hideModal()
    {

        $this->showingModal = false;
        $this->resetFields();
    }

    //開啟刪除Modal
    public function showDeleteModal($id)
    {
        $this->showingDeleteModal = true;
        $this->modal_id = $id;
        $this->loadModal();
        $this->send_btn_name = '刪除';
    }

    //關閉刪除Modal
    public function hideDeleteModal()
    {

        $this->showingDeleteModal = false;
        $this->resetFields();
    }

    //modal載入更新資料
    public function loadModal()
    {
        $result = $this->list[$this->modal_id];
        $this->name = $result['name'];
        $this->content = $result['content'];
        $this->remark = $result['remark'];
        $this->display = $result['display'];
        $this->hasDisplay = (bool) $this->display;
    }
    //清除modal資料
    public function resetFields()
    {
        $this->modal_id = 0;
        $this->name = '';
        $this->content = '';
        $this->remark = '';
        $this->send_btn_name = '新增';
    }
    //顯示列表
    public function render()
    {

        return view('livewire.type-list', [
            'types' => $this->list,
        ]);
    }

    //新增/更新資料
    public function setData()
    {
        $request = new CommodityTypeStoreRequest();

        $this->validate($request->rules());

        $data = $this->getData();

        $set = $this->CommodityTypeService->setDataForView($this->modal_id, $data);

        $this->hideModal();
        return redirect()->to('/type');
    }

    //刪除動作
    public function delete()
    {

        $set = $this->CommodityTypeService->destroyDataForView($this->modal_id);

        $this->hideDeleteModal();
        return redirect()->to('/type');
    }

    //取Modal資料
    public function getData()
    {
        return [
            'name' => $this->name,
            'content' => $this->content,
            'remark' => $this->remark,
            'display' => (int) $this->hasDisplay,
        ];
    }

    //排序往上
    public function upSort($id, $num)
    {
        $list = $this->list;
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

        $set = $this->CommodityTypeService->updateSort($update);

        $this->resetList();
    }

    //排序往下
    public function downSort($id, $num)
    {
        $list = $this->list;
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

        $set = $this->CommodityTypeService->updateSort($update);
        $this->resetList();
    }

    //重整列表
    public function resetList()
    {
        $this->list = $this->CommodityTypeService->getListForView();
    }

    //前往商品列表
    public function goToItem($id)
    {
        return redirect()->to('/item?type_id=' . $id);
    }
}

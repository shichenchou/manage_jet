<?php

namespace App\Http\Livewire;

use App\Services\OrderService;
use Livewire\Component;
use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderList extends Component
{
    private $OrderService;

    public $list;

    public $status = '';
    public $keyword = '';

    public $showingStatusModal = false;

    public $modal_id = 0;
    public $modal_ig = '';
    public $modal_name = '';
    public $modal_adress = '';
    public $modal_phone = '';
    public $modal_status = '';

    public $last_page = 0;
    public $current_page = 1;

    public $logs = [];

    public $items = [];
    public $types = [];

    public $actBtnName = '提交';

    protected $listeners = [
        'showingStatusModal' => 'showingStatusModal',
    ];

    protected $queryString = ['status', 'keyword'];

    public function booted(OrderService $OrderService)
    {
        $this->OrderService = $OrderService;
        $this->status_txt = $this->OrderService->getStatusTxt();
        $this->resetList();
    }

    //重整列表
    public function resetList()
    {

        //整理搜尋條件
        $search = $this->getSearch();
        $get = $this->OrderService->getListForView($search);
        $this->last_page = isset($get['last_page']) ? (int)$get['last_page'] : 0;
        $this->current_page = isset($get['current_page']) ? (int)$get['current_page'] : 1;
        $this->list = isset($get['data']) ? $get['data'] : [];

        $get_items = $this->OrderService->getItem();
        $this->items = isset($get_items['item']) ? $get_items['item'] : [];
        $this->types = isset($get_items['type']) ? $get_items['type'] : [];
    }

    public function render()
    {
        return view('livewire.order-list', ['Orders' => $this->list]);
    }
    //提交
    public function changeStatus()
    {
        $set = $this->OrderService->changeStatus($this->modal_id);
        $this->showingStatusModal = false;
        //$this->resetList();
        return redirect()->to('/Order');
    }
    //取消
    public function cancelStatus()
    {
        $set = $this->OrderService->cancelStatus($this->modal_id);
        $this->showingStatusModal = false;
        //$this->resetList();
        return redirect()->to('/Order');
    }

    //顯示訂單狀態列表
    public function showStatus($id)
    {
        $this->modal_id = $id;
        $list = $this->list;
        $row = isset($list[$id]) ? $list[$id] : [];

        /*if (!in_array($row['status'], ['draft', 'submitted'])) {
			return;
		}*/
        $this->modal_ig = $row['ig'];
        $this->modal_name = $row['name'];
        $this->modal_adress = $row['adress'];
        $this->modal_phone = $row['phone'];
        $this->modal_status = $row['status'];
        $this->logs = $row['logs'];

        $btnName = [
            'draft' => '提交',
            'submitted' => '完成訂單',
            'succeed' => '完成訂單',
        ];

        $this->actBtnName = isset($btnName[$row['status']]) ? $btnName[$row['status']] : '';

        $this->showingStatusModal = true;
    }
    public function hideStatus()
    {

        $this->showingStatusModal = false;
        $this->resetList();
    }

    //取得搜尋條件
    private function getSearch()
    {
        $search = [];
        $this->status = isset($this->status_txt[$this->status]) ? $this->status : null;

        if (!is_null($this->status)) {
            $search['status'] = $this->status;
        }
        if (!is_null($this->keyword) || $this->keyword != '') {
            $search['keyword'] = $this->keyword;
        }

        return $search;
    }

    //搜尋狀態
    public function searchPage()
    {

        return redirect()->to('/Order?status=' . $this->status);
    }

    //關鍵字搜尋
    public function searchKeywordPage()
    {

        return redirect()->to('/Order?&keyword=' . $this->keyword);
    }

    //跳頁
    public function changePage()
    {

        if ($this->current_page <= $this->last_page && $this->current_page >= 1) {
            $getStr = '';
            if (!is_null($this->keyword) || $this->keyword != '') {
                $getStr .= '&keyword=' . $this->keyword;
            } else {
                $getStr .= '&status=' . $this->status;
            }
            return redirect()->to('/Order?page=' . $this->current_page . $getStr);
        }
    }

    //下一頁
    public function nextPage()
    {

        if ($this->current_page < $this->last_page) {
            $this->current_page++;
        }

        $this->changePage();
    }

    //上一頁
    public function prePage()
    {

        if ($this->current_page != 1) {
            $this->current_page--;
        }

        $this->changePage();
    }

    //前往新增畫面
    public function goShop()
    {

        return redirect()->to('/shop');
    }

    //匯出
    public function downloadFile()
    {
        $search = $this->getSearch();
        $get = $this->OrderService->getListForExport($search);
        return Excel::download(new OrderExport($get), 'orderExport.xlsx');
    }
}

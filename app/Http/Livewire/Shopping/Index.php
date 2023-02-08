<?php

namespace App\Http\Livewire\Shopping;

use App\Services\CommodityItemService;
use App\Services\CommodityTypeService;
use App\Services\OrderService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use FaceDetection;

class Index extends Component
{

    use WithFileUploads;
    use LivewireAlert;

    private $CommodityItemService;
    private $CommodityTypeService;
    private $OrderService;

    public $type_list;
    public $item_list;

    public $data = [];
    public $order_id = 0;
    public $photo;

    public function booted(CommodityItemService $CommodityItemService, CommodityTypeService $CommodityTypeService, OrderService $OrderService)
    {
        $this->CommodityItemService = $CommodityItemService;
        $this->CommodityTypeService = $CommodityTypeService;
        $this->OrderService = $OrderService;

        $this->pageInit();
    }
    //畫面初始化
    public function pageInit()
    {

        $search = [
            'display' => 1,
        ];
        $this->type_list = $this->CommodityTypeService->getListForView();
        $this->item_list = $this->CommodityItemService->getListForShop($search);
    }

    //提交
    public function submit()
    {

        if (is_null($this->photo)) {
            $this->alert('warning', '請上傳照片', [
                'position' => 'center'
            ]);
            return;
        }
        $photo = $this->photo->store('faceImage', 'public');
        if (is_file('storage/' . $photo)) {

            $face = FaceDetection::extract('storage/' . $photo);

            if (!$face->found) {
                //dd('照片格式不符合');
                unlink('storage/' . $photo);
                $this->alert('warning', '照片格式不符合', [
                    'position' => 'center'
                ]);
                return;
            }
            $this->data['photo'] = $photo;
        }

        $result = $this->OrderService->setDataForView($this->order_id, $this->data);

        if ($result['error']) {
            $this->alert('warning', $result['msg'], [
                'position' => 'center'
            ]);
            return;
        }
        return redirect()->to('/Order');
    }

    public function render()
    {
        return view(
            'livewire.shopping.index',
            [
                'items' => $this->item_list,
                'types' => $this->type_list,
            ]
        );
    }

    public function photoCallBack()
    {

        //$this->photo＿old_display = false;
        $this->photo = null;
    }

    //返回
    public function backPage()
    {
        return redirect()->to('/Order');
    }
}

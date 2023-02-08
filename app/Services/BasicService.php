<?php

namespace App\Services;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class BasicService
{
    //每頁筆數預設
    protected $limit = 10;

    //回傳預設
    public $return = [
        'error' => true,
        'msg' => 'error.',
        'data' => null,
    ];
    //狀態
    public $status = 500;

    /**
     * API回傳格式
     *
     * @return json
     */
    public function returnData()
    {
        return response()->json($this->return, $this->status);
    }

    /**
     * 取得搜尋條件
     *
     * @param [$_GET] $fitter
     * @param array $col: 驗證欄位 空則全帶入
     * @return array
     */
    public function getSearch($fitter, $col = [])
    {
        $search = array();
        $get = $fitter->all();
        $check_keys = !empty($col) ? $col : array_keys($get);
        foreach ($check_keys as $key) {
            if (isset($get[$key]) && $get[$key] != '') {
                $search[$key] = isset($get[$key]) ? $get[$key] : '';
            }
        }


        return $search;
    }
}

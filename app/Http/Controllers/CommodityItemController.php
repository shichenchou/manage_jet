<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Commodity_item;
use Illuminate\Http\Request;
use App\Services\CommodityItemService;
use App\Http\Requests\CommodityItemStoreRequest;

class CommodityItemController extends Controller
{
    private $CommodityItemService;

    public function __construct(CommodityItemService $CommodityItemService)
    {
        $this->CommodityItemService = $CommodityItemService;
    }
    /**
     * id取類別名稱
     */
    public function getCommodityItemNameById($id)
    {
        return $this->CommodityItemService->getName($id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->CommodityItemService->getList($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CommodityItemStoreRequest $request)
    {
        return $this->CommodityItemService->storeData($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->CommodityItemService->getRow($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Commodity_item  $Commodity_item
     * @return \Illuminate\Http\Response
     */
    public function edit(Commodity_item $Commodity_item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id,Request $request)
    {
        return $this->CommodityItemService->updateData($id,  $request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->CommodityItemService->destroyData($id);
    }
}

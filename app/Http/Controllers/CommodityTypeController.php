<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Commodity_type;
use Illuminate\Http\Request;
use App\Services\CommodityTypeService;
use App\Http\Requests\CommodityTypeStoreRequest;

class CommodityTypeController extends Controller
{
    private $CommodityTypeService;

    public function __construct(CommodityTypeService $CommodityTypeService)
    {
        $this->CommodityTypeService = $CommodityTypeService;
    }
    /**
     * id取類別名稱
     */
    public function getCommodityTypeNameById($id)
    {
        return $this->CommodityTypeService->getName($id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->CommodityTypeService->getList($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('CommodityType.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CommodityTypeStoreRequest $request)
    {
        return $this->CommodityTypeService->storeData($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->CommodityTypeService->getRow($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Commodity_type  $commodity_type
     * @return \Illuminate\Http\Response
     */
    public function edit(Commodity_type $commodity_type)
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
        return $this->CommodityTypeService->updateData($id,  $request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->CommodityTypeService->destroyData($id);
    }
}

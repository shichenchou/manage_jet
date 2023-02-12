<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommodityItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $result = array(
            'list' => [],
        );

        $result['total'] = $this->total();
        $result['perPage']  = $this->perPage();
        $result['currentPage']  = $this->currentPage();
        $result['lastPage']  = $this->lastPage();

        foreach ($this->resource->items() as $val) {
            $id = isset($val['id']) ? (int)($val['id']) : 0;
            if ($id === 0) {
                continue;
            }

            $tmp = array(
                'id' => $id,
                'type_id' => isset($val['type_id']) ? (int)($val['type_id']) : 0,
                'name' => isset($val['name']) ? trim($val['name']) : '',
                'price' => isset($val['price']) ? (int)($val['price']) : 0,
                'remark' => isset($val['remark']) ? trim($val['remark']) : '',
                'discount_type' => isset($val['discount_type']) ? (int)($val['discount_type']) : 0,
                'sort' => isset($val['sort']) ? (int)($val['sort']) : 99,
                'display' => isset($val['display']) ? ($val['display']) : false,
            );
            if(isset($val['type'])){
                $type_tmp = $this->typeToArray($val['type']);
                $tmp = array_merge($tmp,$type_tmp);
            }

            $result['list'][] = $tmp;
        }
        return $result;
    }

    private function typeToArray($types){
        $result = [];
        $result['type_name'] = isset($types['name']) ? trim($types['name']):'';
        $result['type_content'] = isset($types['content']) ? trim($types['content']):'';
        $result['type_remark'] = isset($types['remark']) ? trim($types['remark']):'';
        $result['type_discount_type'] = isset($types['discount_type']) ? (int)$types['discount_type']:0;
        return $result;
    }
}

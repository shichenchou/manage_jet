<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommodityTypeResource extends JsonResource
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
        $result['perPage'] = $this->perPage();
        $result['currentPage'] = $this->currentPage();
        $result['lastPage'] = $this->lastPage();

        foreach ($this->resource->items() as $val) {
            $id = isset($val['id']) ? (int)($val['id']) : 0;
            if ($id === 0) {
                continue;
            }
            //取Attribute
            $val->append('content_remark');
            $val->append('checked');
            $tmp = array(
                'id' => $id,
                'name' => isset($val['name']) ? trim($val['name']) : '',
                'content' => isset($val['content']) ? trim($val['content']) : '',
                'remark' => isset($val['remark']) ? trim($val['remark']) : '',
                'content_remark' => isset($val['content_remark']) ? trim($val['content_remark']) : '',
                'discount_type' => isset($val['discount_type']) ? (int)($val['discount_type']) : 0,
                'sort' => isset($val['sort']) ? (int)($val['sort']) : 99,
                'display' => isset($val['display']) ? (int)($val['display']) : 0,
                'checked' => isset($val['checked']) ? trim($val['checked']) : '',
            );
            $result['list'][] = $tmp;

        }
        return $result;
    }
}

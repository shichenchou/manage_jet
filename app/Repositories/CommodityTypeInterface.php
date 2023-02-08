<?php
namespace App\Repositories;

interface CommodityTypeInterface
{

    public function getTypeList($search);

    public function create($data);

    public function getTypeById($id);

    public function deleteById($id);

    public function updateById($id, array $data);
}

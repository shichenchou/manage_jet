<?php
namespace App\Repositories;

interface CommodityItemInterface
{
    public function getPrice($search);

    public function getItemList($search);

    public function create($data);

    public function getItemById($id);

    public function deleteById($id);

    public function updateById($id, array $data);
}

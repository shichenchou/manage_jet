<?php
namespace App\Repositories;

interface OrderInterface
{

    public function getOrderList($search);

    public function create($data);

    public function getOrderById($id);

    public function getStatusById($id);

    public function deleteById($id);

    public function updateById($id, array $data);

}

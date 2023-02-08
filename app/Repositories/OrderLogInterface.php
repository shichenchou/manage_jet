<?php
namespace App\Repositories;

interface OrderLogInterface
{

 public function getOrderLogList($search);

 public function create($data);

}

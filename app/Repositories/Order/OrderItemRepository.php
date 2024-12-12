<?php
namespace App\Repositories\Order;

use App\Repositories\BaseRepository;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Order_item::class;
    }
}

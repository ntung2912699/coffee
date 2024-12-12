<?php
namespace App\Repositories\Cart;

use App\Repositories\BaseRepository;

class CartItemRepository extends BaseRepository implements CartItemRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Cart_item::class;
    }
}

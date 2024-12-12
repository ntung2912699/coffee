<?php
namespace App\Repositories\Product;

use App\Repositories\BaseRepository;

class ProductOptionRepository extends BaseRepository implements ProductOptionRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Product_option::class;
    }
}

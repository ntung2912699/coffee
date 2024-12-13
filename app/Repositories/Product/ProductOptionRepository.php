<?php
namespace App\Repositories\Product;

use App\Models\Product_option;
use App\Repositories\BaseRepository;

class ProductOptionRepository extends BaseRepository implements ProductOptionRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Product_option::class;
    }

    // Phương thức tìm các tùy chọn dựa trên ID sản phẩm
    public function deleteByProductId($productId)
    {
        // Tìm các tùy chọn liên quan đến sản phẩm
        return Product_option::where('product_id', $productId)->delete();
    }

}

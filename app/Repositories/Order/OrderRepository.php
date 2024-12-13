<?php
namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Order::class;
    }

    // Hàm lấy tất cả đơn hàng của ngày hôm nay
    public function getOrdersToday()
    {
        // Lấy ngày hôm nay (với định dạng YYYY-MM-DD)
        $today = Carbon::today();

        // Truy vấn để lấy tất cả đơn hàng có ngày tạo bằng hôm nay
        return Order::whereDate('created_at', $today)->get();
    }
}

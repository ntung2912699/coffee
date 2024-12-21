<?php
namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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

    public function getRevenueStatistics($type)
    {
        $query = DB::table('order')
            ->select(DB::raw('SUM(total_price) as total_revenue, COUNT(id) as total_orders'))
            ->where('status', 'completed'); // Chỉ tính đơn hoàn thành

        switch ($type) {
            case 'day':
                $query->addSelect(DB::raw('DATE(order_date) as group_date'))
                    ->groupBy(DB::raw('DATE(order_date)'));
                break;

            case 'week':
                $query->addSelect(DB::raw('YEARWEEK(order_date) as group_date'))
                    ->groupBy(DB::raw('YEARWEEK(order_date)'));
                break;

            case 'month':
                $query->addSelect(DB::raw('DATE_FORMAT(order_date, "%Y-%m") as group_date'))
                    ->groupBy(DB::raw('DATE_FORMAT(order_date, "%Y-%m")'));
                break;
        }

        return $query->get();
    }
}

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
                // Sử dụng CURRENT_DATE thay vì DATE để lấy ngày
                $query->addSelect(DB::raw('DATE(order_date) as group_date'))
                    ->groupBy(DB::raw('DATE(order_date)'));
                break;

            case 'week':
                // Lấy năm và tuần của ngày (EXTRACT)
                $query->addSelect(DB::raw('EXTRACT(YEAR FROM order_date) || \'-\' || EXTRACT(WEEK FROM order_date) as group_date'))
                    ->groupBy(DB::raw('EXTRACT(YEAR FROM order_date), EXTRACT(WEEK FROM order_date)'));
                break;

            case 'month':
                // Lấy năm và tháng với TO_CHAR
                $query->addSelect(DB::raw('TO_CHAR(order_date, \'YYYY-MM\') as group_date'))
                    ->groupBy(DB::raw('TO_CHAR(order_date, \'YYYY-MM\')'));
                break;
        }

        return $query->get();

//        $query = DB::table('order')
//            ->select(DB::raw('SUM(total_price) as total_revenue, COUNT(id) as total_orders'))
//            ->where('status', 'completed'); // Chỉ tính đơn hoàn thành
//
//        switch ($type) {
//            case 'day':
//                $query->addSelect(DB::raw('DATE(order_date) as group_date'))
//                    ->groupBy(DB::raw('DATE(order_date)'));
//                break;
//
//            case 'week':
//                $query->addSelect(DB::raw('YEARWEEK(order_date) as group_date'))
//                    ->groupBy(DB::raw('YEARWEEK(order_date)'));
//                break;
//
//            case 'month':
//                $query->addSelect(DB::raw('DATE_FORMAT(order_date, "%Y-%m") as group_date'))
//                    ->groupBy(DB::raw('DATE_FORMAT(order_date, "%Y-%m")'));
//                break;
//        }
//
//        return $query->get();
    }
}

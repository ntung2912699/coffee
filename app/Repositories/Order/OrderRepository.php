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
        return Order::whereDate('created_at', $today)->orderBy('updated_at', 'DESC')->paginate(10);
    }

    public function getRevenueStatistics($type)
    {
        $query = DB::table('order')
            ->select(DB::raw('SUM(total_price) as total_revenue, COUNT(id) as total_orders'))
            ->where('status', 'completed'); // Chỉ tính đơn hoàn thành

        switch ($type) {
            case 'day':
                $query->addSelect(DB::raw('DATE(order_date) as group_date'))
                    ->groupBy(DB::raw('DATE(order_date)'))
                    ->orderBy(DB::raw('DATE(order_date)'), 'desc');
                break;

            case 'week':
                $query->addSelect(DB::raw('EXTRACT(YEAR FROM order_date) as year, EXTRACT(WEEK FROM order_date) as week, CONCAT(EXTRACT(YEAR FROM order_date), \'-\', EXTRACT(WEEK FROM order_date)) as group_date'))
                    ->groupBy(DB::raw('EXTRACT(YEAR FROM order_date), EXTRACT(WEEK FROM order_date)'))
                    ->orderBy(DB::raw('EXTRACT(YEAR FROM order_date) DESC, EXTRACT(WEEK FROM order_date) DESC'));
                break;

            case 'month':
                $query->addSelect(DB::raw('TO_CHAR(order_date, \'YYYY-MM\') as group_date'))
                    ->groupBy(DB::raw('TO_CHAR(order_date, \'YYYY-MM\')'))
                    ->orderBy(DB::raw('TO_CHAR(order_date, \'YYYY-MM\')'), 'desc');
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
//                    ->groupBy(DB::raw('DATE(order_date)'))
//                    ->orderBy(DB::raw('DATE(order_date)'), 'desc');
//                break;
//
//            case 'week':
//                $query->addSelect(DB::raw('YEARWEEK(order_date) as group_date'))
//                    ->groupBy(DB::raw('YEARWEEK(order_date)'))
//                    ->orderBy(DB::raw('YEARWEEK(order_date)'), 'desc');
//                break;
//
//            case 'month':
//                $query->addSelect(DB::raw('DATE_FORMAT(order_date, "%Y-%m") as group_date'))
//                    ->groupBy(DB::raw('DATE_FORMAT(order_date, "%Y-%m")'))
//                    ->orderBy(DB::raw('DATE_FORMAT(order_date, "%Y-%m")'), 'desc');
//                break;
//        }
//
//        return $query->get();
    }

    public function orderByUpdatedAt() {
        return Order::query()->orderBy('updated_at', 'DESC')->paginate(10);
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getOrdersByDate($startDate, $endDate)
    {
        $query = Order::query();

        if ($startDate && $endDate) {
            $query->whereBetween('order_date', [
                Carbon::parse($startDate)->startOfDay()->toDateTimeString(),
                Carbon::parse($endDate)->endOfDay()->toDateTimeString(),
            ]);
        }

        return $query->orderBy('order_date', 'DESC')->paginate(10);
    }
}

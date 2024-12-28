<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StockEntry;
use App\Repositories\Order\OrderRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * @var OrderRepository
     */
    protected $orderRepository;
    /**
     * CategoryController constructor.
     * @param orderRepository $orderRepository
     */
    public function __construct
    (
        OrderRepository $orderRepository
    )
    {
        $this->orderRepository =  $orderRepository;
    }

    /**
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function index() {
        try {
            $ordersOfDay = $this->orderRepository->getOrdersToday();

            return view('admin.page.index', compact('ordersOfDay'));
        } catch (\Exception $exception) {
            return view('admin.page.error');
        }
    }

    /**
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function revenue()
    {
        try {
            // Lấy thống kê doanh thu theo ngày, tuần và tháng
            $revenueByDay = $this->orderRepository->getRevenueStatistics('day');
            $revenueByWeek = $this->orderRepository->getRevenueStatistics('week');
            $revenueByMonth = $this->orderRepository->getRevenueStatistics('month');

            // Lấy chi phí nhập hàng theo từng ngày trong tháng
            $currentMonth = \Carbon\Carbon::now()->format('m'); // Tháng hiện tại
            $currentYear = \Carbon\Carbon::now()->format('Y'); // Năm hiện tại

            // Lấy tất cả các bản ghi nhập hàng trong tháng này
            $stockEntries = StockEntry::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->get();

            // Tính tổng tiền nhập hàng trong tháng này
            $totalStockEntriesAmount = $stockEntries->sum('amount');

            return view('admin.page.revenue', compact(
                    'revenueByDay',
                    'revenueByWeek',
                    'revenueByMonth',
                    'stockEntries',
                    'totalStockEntriesAmount'
                )
            );
        } catch (\Exception $exception) {
            return view('admin.page.error');
        }
    }

    public function stockStore(Request $request) {
        try {
            // Lấy dữ liệu từ form
            $entryDate = $request->input('entry_date');
            $amount = $request->input('amount');

            // Lưu thông tin nhập hàng vào cơ sở dữ liệu
            StockEntry::create([
                'entry_date' => Carbon::parse($entryDate),
                'amount' => $amount,
            ]);

            return redirect()->route('admin.orders-revenue')->with('success', 'Thông tin nhập hàng đã được lưu');
        } catch (\Exception $exception) {
            return view('admin.page.error');
        }
    }

    public function getProfitOfTheMonth()
    {
        try {
            // Lấy tháng và năm hiện tại
            $currentMonth = Carbon::now()->format('Y-m'); // Format YYYY-MM

            // Tính tổng doanh thu của tháng này từ bảng Order
            $totalRevenue = Order::where('status', 'completed') // Lọc theo trạng thái đơn hàng
            ->where('order_date', 'like', "{$currentMonth}%") // Lọc theo tháng
            ->sum('total_price'); // Tổng doanh thu

            // Tính tổng chi phí hàng nhập của tháng này từ bảng StockEntry
            $totalExpenses = StockEntry::where('created_at', 'like', "{$currentMonth}%") // Lọc theo tháng
            ->sum('amount'); // Tổng chi phí nhập hàng

            // Tính lợi nhuận (Doanh thu - Chi phí)
            $profit = $totalRevenue - $totalExpenses;

            return view('admin.page.profit', compact('profit', 'totalRevenue', 'totalExpenses'));
        } catch (\Exception $exception) {
            return view('admin.page.error');
        }
    }
}

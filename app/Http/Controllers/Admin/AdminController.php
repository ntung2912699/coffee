<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepository;
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
        $ordersOfDay = $this->orderRepository->getOrdersToday();

        return view('admin.page.index', compact('ordersOfDay'));
    }

    /**
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function revenue() {
        // Lấy thống kê doanh thu theo ngày, tuần và tháng
        $revenueByDay = $this->orderRepository->getRevenueStatistics('day');
        $revenueByWeek = $this->orderRepository->getRevenueStatistics('week');
        $revenueByMonth = $this->orderRepository->getRevenueStatistics('month');

        return view('admin.page.revenue', compact('revenueByDay', 'revenueByWeek', 'revenueByMonth'));
    }
}

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

    public function index() {
        $ordersOfDay = $this->orderRepository->getOrdersToday();
        return view('admin.page.index', compact('ordersOfDay'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderItemRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var OrderItemRepository
     */
    protected $orderItemRepository;
    /**
     * CategoryController constructor.
     * @param orderRepository $orderRepository
     */
    public function __construct
    (
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository
    )
    {
        $this->orderRepository =  $orderRepository;
        $this->orderItemRepository =  $orderItemRepository;
    }

    /**
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function index(Request $request)
    {
        try {
            // Lấy dữ liệu lọc từ request
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Gọi hàm từ Repository
            $orders = $this->orderRepository->getOrdersByDate($startDate, $endDate);

            return view('admin.page.order.index', compact('orders', 'startDate', 'endDate'));
        } catch (\Exception $exception) {
            return view('admin.page.error');
        }
    }


    /**
     * Thêm mới category
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $this->orderRepository->create([
                'name' => $request->get('name'),
            ]);

            return redirect()->route('admin.orders-indexx')->with('success', 'Thành Công!');
        } catch (\Exception $exception) {
            return redirect()->route('admin.orders-index')->with('error', 'Không Thành Công: ' . $exception->getMessage());
        }
    }

    /**
     * Cập nhật category
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $this->orderRepository->update($id, [
                'name' => $request->get('name'),
            ]);

            return redirect()->route('admin.orders-index')->with('success', 'Thành Công!');
        } catch (\Exception $exception) {
            return redirect()->route('admin.orders-index')->with('error', 'Không Thành Công: ' . $exception->getMessage());
        }
    }

    /**
     * Xóa order
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        try {
            $this->orderItemRepository->deleteByOrderId($id);
            $this->orderRepository->delete($id);

            return redirect()->route('admin.orders-index')->with('success', 'Thành Công!');
        } catch (\Exception $exception) {
            return redirect()->route('admin.orders-index')->with('error', 'Không Thành Công: ' . $exception->getMessage());
        }
    }
}

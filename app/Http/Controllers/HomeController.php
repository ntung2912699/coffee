<?php

namespace App\Http\Controllers;

use App\Mail\OrderCreated;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\ProductRepository;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var CartRepository
     */
    protected $cartRepository;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * CategoryController constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct
    (
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        CartRepository $cartRepository,
        OrderRepository $orderRepository
    )
    {
        $this->categoryRepository =  $categoryRepository;
        $this->productRepository =  $productRepository;
        $this->cartRepository =  $cartRepository;
        $this->orderRepository =  $orderRepository;
    }

    /**
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function index() {
        try {
            $categories = $this->categoryRepository->getAll();
            return view('welcome', compact('categories'));
        } catch (\Exception $exception) {
            return view('admin.page.error');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function storeOrder(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.options' => 'string',
            'total' => 'required|numeric|min:0',
        ]);
        // Lưu đơn hàng vào bảng `orders`
        $order = new Order();
        $order->customer_name = !empty($request->get('customerName')) ? $request->get('customerName') : 'new customer';
        $order->phone_number = !empty($request->get('customerPhone')) ? $request->get('customerPhone') : 'N/A';
        $order->address = !empty($request->get('customerAddress')) ? $request->get('customerAddress') : 'N/A';
        $order->status = 'pending'; // Trạng thái mặc định
        $order->total_price = $validated['total'];
        $order->order_date = now(); // Gán ngày hiện tại
        $order->save();

        // Lưu từng sản phẩm vào bảng `order_items`
        foreach ($validated['items'] as $item) {
            $totalItemPrice = $item['quantity'] * $item['price']; // Tính tổng tiền cho từng sản phẩm
            $order->items()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total_price' => $totalItemPrice, // Lưu tổng tiền
                'attributes' => $item['options'], // thuộc tính
            ]);
        }

        Mail::to('ntung2912699@gmail.com')->send(new OrderCreated($order));
        $isAuthenticated = auth()->check();

        return response()->json([
            'order_id' => $order->id,
            'is_authenticated' => $isAuthenticated,
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function printOrder($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        // Thông tin thanh toán
        $bankName = 'MB BANK';
        $accountNumber = '001099022228';

        // Dữ liệu cho mã QR (theo chuẩn yêu cầu)
        $qrData = "BANK:$bankName;STK:$accountNumber;AMOUNT:$order->total_price;NOTE:COFFEE GIO Thanh Toan Don Hang $order->id";

        // Tạo đối tượng QrCode
        $qrCode = new QrCode($qrData);

        // Tạo đối tượng Writer
        $writer = new PngWriter();

        // Lưu mã QR vào file
        $qrCodePath = public_path('qr_codes/qr_' . $order->id . '.png');
        $writer->write($qrCode)->saveToFile($qrCodePath);

        // Trả về view
        return view('order.print-order', [
            'order' => $order,
            'qrCodePath' => 'qr_codes/qr_' . $order->id . '.png',
        ]);
    }


    /**
     * @param $id
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function show($id)
    {
        // Lấy thông tin đơn hàng theo ID
        $order = Order::findOrFail($id);  // Tìm đơn hàng theo ID, nếu không tìm thấy sẽ trả về lỗi 404

        // Trả về view với dữ liệu đơn hàng
        return view('order.show', compact('order'));
    }


    /**
     * @param $orderId
     */
    public function printReceipt($orderId)
    {
        try {
            $order = Order::with('items.product')->findOrFail($orderId);

            // Kết nối đến máy in POS-80
            $connector = new WindowsPrintConnector("POS-80");

            // Tạo đối tượng máy in
            $printer = new Printer($connector);
            $printer->setFont(Printer::FONT_B);

            // In thông tin cửa hàng
            $printer->text("COFFEE GIÓ\n");
            $printer->text("Địa chỉ: 123 Đường ABC, TP HCM\n");
            $printer->text("Điện thoại: 0123 456 789\n");

            // In thông tin đơn hàng
            $printer->text("HÓA ĐƠN BÁN LẺ\n");
            $printer->text("Mã đơn hàng #{$order->id}\n");
            $printer->text("Ngày: {$order->created_at->format('d/m/Y H:i:s')}\n");
            $printer->text("Tên khách hàng: New Customer\n");
            $printer->text("Số điện thoại: N/A\n");

            // In các sản phẩm trong đơn hàng
            foreach ($order->items as $item) {
                $printer->text("{$item->product->name} x {$item->quantity} - {$item->price} VNĐ\n");
            }

            // Tổng tiền
            $printer->text("Tổng tiền: {$order->total_price} VNĐ\n");

            // Cảm ơn
            $printer->text("Cảm ơn quý khách!\n");

            // Cắt giấy
            $printer->cut();

            // Đóng kết nối máy in
            $printer->close();

            $this->orderRepository->update($orderId, [
                'status' => 'completed'
            ]);

            // Quay về trang welcome với thông báo thành công
            return redirect()->route('welcome')->with('success', "Đơn Hàng {$orderId} Đã Hoàn Tất!");

        } catch (\Exception $e) {
            // Nếu không kết nối được máy in, quay lại trang 'orders.print' và thông báo lỗi
            return redirect()->route('orders.print', ['id' => $orderId])
                ->with('error', 'Không thể kết nối với máy in. Vui lòng kiểm tra lại.');
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function cancelOrder($id) {
        try {
            $this->orderRepository->update($id, [
                'status' => 'cancelled'
            ]);
            return redirect()->route('orders.print', ['id' => $id])->with('success', "Đơn Hàng {$id} Đã Hủy Thành Công!");
        } catch (\Exception $e) {
            // Nếu không kết nối được máy in, quay lại trang 'orders.print' và thông báo lỗi
            return redirect()->route('orders.print', ['id' => $id])
                ->with('error', 'Đã xảy ra lỗi trong quá trình hủy đơn. Vui lòng kiểm tra lại.');
        }
    }

    /**
     * @param $categoryId
     * @return mixed
     */
    public function getProductsByCategory($categoryId)
    {
        $products = $this->productRepository->getByCategoryId($categoryId);

        return response()->json($products);
    }

    /**
     * @param $productId
     * @return mixed
     *
     */
    public function getOptions()
    {
        $options = DB::table('product_option')
            ->select('attribute_name', 'attribute_value', 'price')
            ->get()
            ->groupBy('attribute_name');

        return response()->json($options);
    }

    /**
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function orderSuccess($id){
        $order = $this->orderRepository->find($id);
        return view('admin.page.order_success', compact('order'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function searchProducts(Request $request) {
        $query = $request->input('search');

        $products = Product::where('name', 'LIKE', '%' . strtolower($query) . '%')->get();

        return response()->json($products);
    }

}

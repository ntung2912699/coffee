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
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
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
            'items.*.options' => 'nullable|string',
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
                'attributes' => !empty($item['options'])?$item['options']:"", // thuộc tính
            ]);
        }

        Mail::to('coffeegio071088@gmail.com')->send(new OrderCreated($order));
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
        $bankId = "970422"; // Mã ngân hàng MB
        $accountNumber = "001099022228"; // Số tài khoản nhận tiền
        $amount = $order->total_price; // Số tiền VND
        $description = "Thanh toan don hang COFFE GIO" . $order->id; // Nội dung chuyển khoản
        $qrCode = $this->generateVietQR($bankId, $accountNumber, $amount, $description);
        // Trả về view
        return view('order.print-order', [
            'order' => $order,
            'qrCode' => $qrCode,
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
     * @return mixed
     * @throws \Exception
     */
    public function printReceipt($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);

        // Kết nối đến máy in POS-80
        $connector = new WindowsPrintConnector('POS-80');
        $qrCodePath = public_path('qr_codes/qrcode.png');

        // Tạo đối tượng máy in
        $printer = new Printer($connector);

        // In thông tin cửa hàng
        $printer->text("COFFEE GIÓ\n");
        $printer->text("Địa chỉ: Số 3 - đường Đầm Vực Giang - xã Hạ Bằng - huyện Thạch Thất - tp Hà Nội\n");
        $printer->text("Điện thoại: 0968 251 663\n");

        // In thông tin đơn hàng
        $printer->text("HÓA ĐƠN BÁN LẺ\n");
        $printer->text("Mã đơn hàng: #{$order->id}\n");
        $printer->text("Ngày: {$order->created_at->format('d/m/Y H:i:s')}\n");
        $printer->text("Tên khách hàng: {$order->customer_name}\n");
        $printer->text("Số điện thoại: {$order->phone_number}\n");
        $printer->text("Địa chỉ: {$order->address}\n");

        // In các sản phẩm trong đơn hàng
        foreach ($order->items as $item) {
            $printer->text("{$item->product->name} x {$item->quantity} - {$item->price} VNĐ\n");
        }

        // Tổng tiền
        $printer->text("Tổng tiền: {$order->total_price} VNĐ\n");

        // Chèn mã QR vào biên lai
        if (file_exists($qrCodePath)) {
            $qrCode = EscposImage::load($qrCodePath);
            $printer->bitImage($qrCode);
        } else {
            $printer->text("QR Code không có sẵn!\n"); // Thông báo nếu không tìm thấy ảnh
        }

        // Cảm ơn
        $printer->text("\nCảm ơn quý khách!\n");

        // Cắt giấy
        $printer->cut();

        // Đóng kết nối máy in
        $printer->close();

        $this->orderRepository->update($orderId, [
            'status' => 'completed'
        ]);

        return response()->json([
            'order_id' => $order->id,
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function statusOrderUpdate(Request $request, $id) {
        $validate = $request->validate(
            ['status' => 'required|string']
        );

        $order = $this->orderRepository->update($id, [
            'status' => $validate['status']
        ]);

        return response()->json([
            'order' => $order,
        ]);
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

        $products = Product::where('name', 'ILIKE', '%' . $query . '%')->get();

        return response()->json($products);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getProductAttributes($id)
    {
        $product = Product::findOrFail($id);

        // Tách chuỗi attribute_name thành mảng
        $attributes = $product->attribute_name ? explode(',', $product->attribute_name) : [];

        return response()->json($attributes);
    }

    /**
     * @return mixed
     */
    public function getProductOptions()
    {
        $options = DB::table('product_option')
            ->select('attribute_name', 'attribute_value', 'price')
            ->get()
            ->groupBy('attribute_name');

        return response()->json($options);
    }

    protected function generateVietQR($bankId, $accountNumber, $amount, $description) {
        $client = new Client();
        $response = $client->post('https://api.vietqr.io/v2/generate', [
            'json' => [
                'accountNo' => $accountNumber,
                'accountName' => 'NGUYEN VAN TUNG', // Thay tên tài khoản thật của bạn
                'acqId' => $bankId, // Mã ngân hàng MB
                'amount' => number_format($amount, 0, '.', ''),
                'addInfo' => $description,
                'format' => 'text',
                'template' => 'compact',
            ]
        ]);

        $body = json_decode($response->getBody(), true);
        return $body['data']['qrDataURL'];
    }
}

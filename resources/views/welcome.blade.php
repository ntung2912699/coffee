@extends('admin.layout')

@section('content')
    <div class="dashboard" style="min-height: 760px;">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">MÀN HÌNH ORDER</h1>
            <a href="{{ route('admin.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-user-alt fa-sm text-white-50"></i> Quản Lý Cửa Hàng
            </a>
        </div>

        <div class="row">
            <!-- Menu phần trái (80%) -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h2 class="m-0 font-weight-bold text-primary">MENU</h2>
                    </div>
                    <div class="card-body">
                        <!-- Hiển thị menu theo phân loại đồ uống -->
                        <div class="row">
                            @foreach($categories as $category)
                                <div class="col-md-4">
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">{{ $category->name }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                @foreach($category->product as $product)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span>{{ $product->name }}</span>
                                                        <a class="text-right">{{ number_format($product->price / 1000) }}K</a>
                                                        <i class="fas fa-cart-plus add-to-cart text-success" data-product-id="{{ $product->id }}" data-price="{{ $product->price }}"></i>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Giỏ hàng phần phải (30%) -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h2 class="m-0 font-weight-bold text-primary">ĐƠN HÀNG</h2>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-outline-danger shadow-sm" id="clear-cart-btn">Xóa Tất Cả Giỏ Hàng</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul id="cart-list" class="list-group">
                            <!-- Các sản phẩm trong giỏ hàng sẽ được thêm vào đây -->
                        </ul>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Tổng tiền:</strong>
                            <span id="total-price">0 VNĐ</span>
                        </div>
                        <button class="btn btn-primary mt-3" id="checkout-btn">Thanh toán</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Khởi tạo giỏ hàng
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Hàm thêm sản phẩm vào giỏ hàng
    $(document).on('click', '.add-to-cart', function() {
        const productId = $(this).data('product-id');
        const productName = $(this).closest('li').find('span').text(); // Lấy tên sản phẩm
        const productPrice = $(this).data('price');  // Lấy giá sản phẩm

        // Kiểm tra nếu sản phẩm đã có trong giỏ chưa
        const existingProductIndex = cart.findIndex(item => item.id === productId);

        if (existingProductIndex === -1) {
            // Thêm sản phẩm vào giỏ hàng
            cart.push({ id: productId, name: productName, price: productPrice, quantity: 1 });
        } else {
            // Cập nhật số lượng nếu sản phẩm đã có trong giỏ
            cart[existingProductIndex].quantity++;
        }

        // Lưu giỏ hàng vào localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Cập nhật giỏ hàng
        updateCart();
    });

    // Hàm định dạng tiền tệ
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount).replace('₫', '') + ' VNĐ';
    }

    // Cập nhật giỏ hàng
    function updateCart() {
        $('#cart-list').empty();
        let totalPrice = 0;

        // Duyệt qua giỏ hàng để hiển thị sản phẩm
        cart.forEach(item => {
            const itemTotalPrice = item.price * item.quantity;
            $('#cart-list').append(`
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${item.name} x ${item.quantity} - ${formatCurrency(itemTotalPrice)}
                    <i class="fas fa-trash-alt text-danger remove-from-cart" data-product-id="${item.id}"></i>
                </li>
            `);
            totalPrice += itemTotalPrice;
        });

        // Cập nhật tổng tiền
        $('#total-price').text(formatCurrency(totalPrice));
    }

    // Xóa sản phẩm khỏi giỏ hàng
    $(document).on('click', '.remove-from-cart', function() {
        const productId = $(this).data('product-id');
        cart = cart.filter(item => item.id !== productId);

        // Lưu giỏ hàng sau khi xóa sản phẩm
        localStorage.setItem('cart', JSON.stringify(cart));

        updateCart();
    });

    // Thực hiện thanh toán
    $(document).on('click', '#checkout-btn', function() {
        // Thực hiện các hành động thanh toán (ví dụ, gửi giỏ hàng đến backend)
        alert('Đã thanh toán!');

        // Xóa giỏ hàng sau khi thanh toán
        localStorage.removeItem('cart');
        cart = [];
        updateCart();
    });

    $(document).on('click', '#clear-cart-btn', function() {
        // Xóa giỏ hàng trong localStorage và bộ nhớ
        localStorage.removeItem('cart');
        cart = [];
        updateCart();
    });

    // Khôi phục giỏ hàng khi tải lại trang
    $(document).ready(function() {
        updateCart();
    });
</script>





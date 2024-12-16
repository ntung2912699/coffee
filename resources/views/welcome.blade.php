@extends('admin.layout')

@section('content')
    <style>
        /* Biểu tượng giỏ hàng */
        .cart-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            border-radius: 50%;
            padding: 10px;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Số lượng sản phẩm trong giỏ */
        #cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }

        /* Popup giỏ hàng */
        #cart-modal .modal-dialog {
            max-width: 400px;
        }
    </style>
    <div class="dashboard" style="min-height: 760px;">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">MÀN HÌNH ORDER</h1>
            <div class="cart-icon" id="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span id="cart-count" class="badge badge-danger">0</span> <!-- Số lượng sản phẩm trong giỏ -->
            </div>
            
            <!-- Giỏ hàng dưới dạng Popup -->
            <div id="cart-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="cart-modal-label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cart-modal-label">Giỏ hàng</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ul id="cart-list" class="list-group">
                                <!-- Các sản phẩm trong giỏ hàng sẽ được thêm vào đây -->
                            </ul>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Tổng tiền:</strong>
                                <span class="font-weight-bold" id="total-price">0 VNĐ</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-primary" id="checkout-btn">Thanh toán</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('admin.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-user-alt fa-sm text-white-50"></i> Quản Lý Cửa Hàng
            </a>
        </div>

        <div class="row">
            <!-- Menu phần trái (80%) -->
            <div class="col-lg-9">
                <div class="card shadow mb-4" style="min-height: 760px">
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
                                                        <i class="fas fa-cart-plus add-to-cart text-success" data-product-id="{{ $product->id }}" data-price="{{ $product->price }}" data-options="{{ $product->attributes }}"></i>
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
            <div class="col-lg-3">
                <div class="card shadow mb-4" style="min-height: 760px">
                    <div class="card-header py-3">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h4 class="m-0 font-weight-bold text-primary">ĐƠN HÀNG</h2>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-outline-danger shadow-sm" id="clear-cart-btn">Xóa Giỏ Hàng</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul id="cart-list" class="list-group">
                            <!-- Các sản phẩm trong giỏ hàng sẽ được thêm vào đây -->
                        </ul>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Tổng tiền:</strong>
                            <span class="font-weight-bold" id="total-price">0 VNĐ</span>
                        </div>
                        <button class="btn btn-primary mt-3" id="checkout-btn">Thanh toán</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup chọn option -->
    <div id="product-option-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="product-option-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="product-option-modal-label">Chọn tùy chọn sản phẩm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="option-list"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="confirm-option-btn">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
    // Khởi tạo giỏ hàng từ localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Cập nhật giỏ hàng
    function updateCart() {
        let totalPrice = 0;
        let totalItems = 0;

        $('#cart-list').empty();
        
        // Duyệt qua các sản phẩm trong giỏ
        cart.forEach(item => {
            const itemTotalPrice = item.price * item.quantity;
            $('#cart-list').append(`
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${item.name} 
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-secondary change-quantity" data-product-id="${item.id}" data-action="decrease">-</button>
                        <input type="number" class="form-control form-control-sm mx-2" value="${item.quantity}" data-product-id="${item.id}" style="width: 50px;">
                        <button class="btn btn-sm btn-outline-secondary change-quantity" data-product-id="${item.id}" data-action="increase">+</button>
                    </div>
                    <span class="ml-2">${formatCurrency(itemTotalPrice)}</span>
                    <i class="fas fa-trash-alt text-danger remove-from-cart" data-product-id="${item.id}"></i>
                </li>
            `);
            totalPrice += itemTotalPrice;
            totalItems += item.quantity;
        });

        // Cập nhật tổng tiền và số lượng sản phẩm
        $('#total-price').text(formatCurrency(totalPrice));
        $('#cart-count').text(totalItems); // Cập nhật số lượng sản phẩm
    }

    // Định dạng tiền tệ
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount).replace('₫', '') + ' VNĐ';
    }

    // Thêm sản phẩm vào giỏ hàng
    function addToCart(productId, productName, productPrice) {
        const product = {
            id: productId,
            name: productName,
            price: productPrice,
            quantity: 1
        };

        // Lấy giỏ hàng từ localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        const existingProductIndex = cart.findIndex(item => item.id === productId);

        if (existingProductIndex === -1) {
            cart.push(product);
        } else {
            cart[existingProductIndex].quantity++;
        }

        // Lưu giỏ hàng vào localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Cập nhật giỏ hàng
        updateCart();
    }

    // Xóa sản phẩm khỏi giỏ hàng
    $(document).on('click', '.remove-from-cart', function() {
        const productId = $(this).data('product-id');
        cart = cart.filter(item => item.id !== productId);

        // Lưu giỏ hàng sau khi xóa sản phẩm
        localStorage.setItem('cart', JSON.stringify(cart));

        // Cập nhật giỏ hàng
        updateCart();
    });

    // Thêm sự kiện cho nút "Thêm vào giỏ hàng"
    $(document).on('click', '.add-to-cart', function() {
        const productId = $(this).data('product-id');
        const productName = $(this).closest('li').find('.product-name').text();
        const productPrice = $(this).data('price');

        addToCart(productId, productName, productPrice);
    });

    // Sự kiện thay đổi số lượng sản phẩm trong giỏ
    $(document).on('input', 'input[type="number"]', function() {
        const productId = $(this).data('product-id');
        const newQuantity = parseInt($(this).val(), 10);

        if (isNaN(newQuantity) || newQuantity <= 0) {
            $(this).val(1); // Đảm bảo số lượng không nhỏ hơn 1
            return;
        }

        // Cập nhật số lượng trong giỏ hàng
        cart = cart.map(item => {
            if (item.id === productId) {
                item.quantity = newQuantity;
            }
            return item;
        });

        // Lưu lại vào localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Cập nhật giỏ hàng
        updateCart();
    });

    // Sự kiện thay đổi số lượng bằng nút cộng/trừ
    $(document).on('click', '.change-quantity', function() {
        const productId = $(this).data('product-id');
        const action = $(this).data('action');
        
        cart = cart.map(item => {
            if (item.id === productId) {
                if (action === 'increase') {
                    item.quantity++;
                } else if (action === 'decrease' && item.quantity > 1) {
                    item.quantity--;
                }
            }
            return item;
        });

        // Lưu lại vào localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Cập nhật giỏ hàng
        updateCart();
    });

    // Hiển thị popup giỏ hàng
    $('#cart-icon').on('click', function() {
        $('#cart-modal').modal('show');
    });

    // Xóa giỏ hàng
    $('#clear-cart-btn').on('click', function() {
        localStorage.removeItem('cart');
        cart = [];
        updateCart();
    });

    // Cập nhật giỏ hàng khi trang được tải
    updateCart();
});

</script>

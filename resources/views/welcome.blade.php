@extends('admin.layout')

@section('content')
    <style>
        /* Biểu tượng giỏ hàng */
        .cart-icon {
            position: fixed;
            bottom: 50px;
            right: 20px;
            background-color: #e67e22 !important;
            color: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            padding: 10px;
            cursor: pointer;
            z-index: 1000;
            font-size: 25px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .cart-icon i {
            color: white !important;
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

        .category-list {
            /*max-height: 760px;*/
            overflow-y: auto;
        }

        .product-list {
            /*max-height: 760px;*/
            overflow-y: auto;
        }

        @media (max-width: 820px) {
            #cart-icon {
                display: inline;
            }

            #cart-right {
                display: none;
            }
        }

        @media (min-width: 820px) {
            #cart-icon {
                display: none;
            }

            #cart-right {
                display: inline;
            }
        }

        /* Giỏ hàng trên mobile */
        #cart-mobile {
            position: fixed;
            top: 0;
            left: -100%; /* Ẩn ngoài màn hình bên trái */
            width: 100%;
            height: 100%;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
            z-index: 1050;
            transition: transform 0.3s ease-in-out;
            overflow-y: auto;
        }

        #cart-mobile.show {
            transform: translateX(100%); /* Hiển thị giỏ hàng */
        }

        /* Overlay che màn hình khi giỏ hàng mở */
        #cart-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1049;
            display: none;
        }

        @media (min-width: 820px) {
            #cart-mobile {
                display: none;
            }
        }

        /* Sidebar Giỏ Hàng */
        .cart-sidebar {
            position: fixed;
            top: 0;
            left: -100%; /* Ẩn sidebar ban đầu */
            width: 100%;
            height: 100%;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1050;
            overflow-y: auto;
            transition: left 0.3s ease-in-out;
        }

        .cart-sidebar.open {
            left: 0; /* Hiển thị sidebar */
        }

        .cart-sidebar-header {
            padding: 15px;
            /*background-color: #28a745;*/
            color: slategrey;
        }

        .cart-sidebar-body {
            padding: 10px;
        }

        .cart-sidebar-footer {
            padding: 15px;
            border-top: 1px solid #ddd;
        }

        .close-sidebar-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: slategrey;
            cursor: pointer;
        }

        @media (max-width: 820px) {
            /* Ẩn danh sách category dạng ul trên mobile */
            #category-list {
                display: none;
            }

            /* Hiển thị select input trên mobile */
            #category-select {
                display: block;
            }
        }

        @media (min-width: 820px) {
            /* Hiển thị danh sách category dạng ul trên desktop */
            #category-list {
                display: block;
            }

            /* Ẩn select input trên desktop */
            #category-select {
                display: none;
            }
        }

        /* Định dạng cho phần Background */
        .background {
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            background-image: url("{{ asset('assets/logo/background.jpg') }}");
            background-size: cover;
            background-position: center;
        }

        /* Định dạng cho phần Thông tin Cửa Hàng */
        .info-shop {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .info-shop h2 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .info-shop p {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        .info-shop .lead {
            font-size: 16px;
            color: #555;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .row {
                flex-direction: column; /* Stack các cột trên các thiết bị nhỏ */
            }

            .background, .info-shop {
                width: 100%;
            }
        }

        #cart-popup {
            animation: fadeInOut 3s ease;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; }
        }

        .cart-container {
            display: none;
        }

    </style>
    <div class="dashboard" style="min-height: 760px;">
        <div class="row">
            <div class="background col-md-6 col-sm-12" style="width: 100%; height: 260px"></div>
            <div class="info-shop col-md-6 col-sm-12">
                <section class="text-left" style="background: #ffffff">
                    <h2>COFFE GIÓ</h2>
                    <p class="lead text-muted" style="color: #34495e"><i class="fas fa-home"></i> : Số 3 - đường Đầm Vực Giang - xã Hạ Bằng - huyện Thạch Thất - tp Hà Nội</p>
                    <p class="lead text-muted" style="color: #34495e"><i class="fas fa-phone"></i> : 0968 251 663</p>
                    <p class="lead text-muted" style="color: #34495e"><i class="fas fa-clock"></i> : 8:00 AM - 23:30 PM</p>

                    @if (auth()->check())
                        <!-- Dropdown khi đăng nhập -->
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary shadow-sm dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-alt fa-sm text-white-50" style="color: #ffffff !important;"></i> {{ auth()->user()->name }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-cog" style="color: #ffffff !important;"></i> Quản Lý Cửa Hàng</a></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt" style="color: #ffffff !important;"></i> Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                    @else
                    <!-- Nút hiển thị mặc định nếu chưa đăng nhập -->
                        <a href="{{ route('login') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-sign-in-alt fa-sm text-white-50" style="color: #ffffff !important;"></i> Đăng Nhập
                        </a>
                    @endif
                </section>
            </div>
        </div>
        <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 30px">
            <h5 class="h5 mb-0 text-800" style="color: #34495e"><i class="fas fa-shopping-bag"></i> MÀN HÌNH ORDER</h5>
            <div class="cart-icon" id="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span id="cart-count" class="badge badge-danger">0</span>
            </div>
        </div>

        <div class="row">

            <!-- Danh sách category (20%) -->
            <div class="col-lg-3 category-list" id="category-list-container">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h2 class="m-0 font-weight-bold text-primary"><i class="fas fa-icons"></i></h2>
                    </div>
                    <div class="card-body">
                        <!-- Danh mục trên desktop -->
                        <ul class="list-group list-group-flush d-none d-lg-block" id="category-list">
                            @foreach($categories as $category)
                                <li class="list-group-item category-item {{ $loop->first ? 'active' : '' }}" data-category-id="{{ $category->id }}">
                                    {{ $category->name }}
                                </li>
                            @endforeach
                        </ul>

                        <!-- Danh mục trên mobile -->
                        <select class="form-control d-lg-none" id="category-select">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $loop->first ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Danh sách sản phẩm (50%) -->
            <div class="col-lg-5 product-list">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="row">
                            <div class="col-md-4">
                                <h2 class="m-0 font-weight-bold text-primary"><i class="fas fa-mug-hot"></i></h2>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control"
                                           name="search"
                                           id="search-product"
                                           placeholder="Tìm kiếm"
                                           aria-label="Search"
                                           onkeyup="searchProductOnEnter(event)">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="button" onclick="searchProductOnClick()">
                                            <i class="fas fa-search" style="color: #ffffff !important;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul id="product-container" class="list-group list-group-flush">
                            <!-- Sản phẩm sẽ được tải vào đây dựa trên danh mục đã chọn -->
                            @if($categories->isNotEmpty())
                                @foreach($categories->first()->product as $product)
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 card-title">{{ $product->name }}</h6>
                                                <b class="text-muted">{{ number_format($product->price / 1000) }}K</b>
                                            </div>
                                            <i class="fas fa-cart-plus add-to-cart text-success"
                                               data-product-id="{{ $product->id }}"
                                               data-product-name="{{ $product->name }}"
                                               data-product-price="{{ $product->price }}"
                                               data-product-option="{{ $product->attributes }}"></i>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Giỏ hàng (30%) -->
            <div class="col-lg-4" id="cart-right">
                <div class="container">
                    <div class="card shadow mb-4 form-customer">
                        <div class="card-header py-3">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h2 class="m-0 font-weight-bold text-primary"><i class="fas fa-cart-plus"></i></h2>
                                <button class="btn btn-sm select-cart" data-cart-id="cart-1">Giỏ Hàng 1</button>
                                <button class="btn btn-sm select-cart" data-cart-id="cart-2">Giỏ Hàng 2</button>
                                <button class="btn btn-sm select-cart" data-cart-id="cart-3">Giỏ Hàng 3</button>
                                <button class="btn btn-sm select-cart" data-cart-id="cart-4">Giỏ Hàng 4</button>
                                <button class="btn btn-sm select-cart" data-cart-id="cart-5">Giỏ Hàng 5</button>
{{--                                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-outline-danger shadow-sm" id="clear-cart-btn">Xóa Giỏ Hàng</a>--}}
                            </div>
                        </div>

                    <!-- Giỏ hàng 1 -->
                    <div class="cart-container" id="cart-1" style="display: none;">
                        <div class="card shadow mb-4 form-customer">
                            <div class="card-header py-3">
                                <h5 class="m-0 font-weight-bold text-primary" style="color: #e67e22 !important">Giỏ Hàng 1</h5>
                            </div>
                            <div class="card-body">
                                <ul id="cart-list-cart-1" class="list-group list-group-flush">
                                    <!-- Sản phẩm sẽ được thêm vào đây -->
                                </ul>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Tổng tiền:</strong>
                                    <span class="font-weight-bold" id="total-price-cart-1">0 VNĐ</span>
                                </div>
                                <button class="btn btn-primary mt-3 checkout-btn" data-cart-id="cart-1">Đặt Hàng</button>
                            </div>
                        </div>
                    </div>

                    <!-- Giỏ hàng 2 -->
                    <div class="cart-container" id="cart-2" style="display: none;">
                        <div class="card shadow mb-4 form-customer">
                            <div class="card-header py-3">
                                <h5 class="m-0 font-weight-bold text-primary" style="color: #e67e22 !important">Giỏ Hàng 2</h5>
                            </div>
                            <div class="card-body">
                                <ul id="cart-list-cart-2" class="list-group list-group-flush">
                                    <!-- Sản phẩm sẽ được thêm vào đây -->
                                </ul>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Tổng tiền:</strong>
                                    <span class="font-weight-bold" id="total-price-cart-2">0 VNĐ</span>
                                </div>
                                <button class="btn btn-primary mt-3 checkout-btn" data-cart-id="cart-2">Đặt Hàng</button>
                            </div>
                        </div>
                    </div>

                    <!-- Giỏ hàng 3 -->
                    <div class="cart-container" id="cart-3" style="display: none;">
                        <div class="card shadow mb-4 form-customer">
                            <div class="card-header py-3">
                                <h5 class="m-0 font-weight-bold text-primary" style="color: #e67e22 !important">Giỏ Hàng 3</h5>
                            </div>
                            <div class="card-body">
                                <ul id="cart-list-cart-3" class="list-group list-group-flush">
                                    <!-- Sản phẩm sẽ được thêm vào đây -->
                                </ul>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Tổng tiền:</strong>
                                    <span class="font-weight-bold" id="total-price-cart-3">0 VNĐ</span>
                                </div>
                                <button class="btn btn-primary mt-3 checkout-btn" data-cart-id="cart-3">Đặt Hàng</button>
                            </div>
                        </div>
                    </div>

                    <!-- Giỏ hàng  4 -->
                    <div class="cart-container" id="cart-4" style="display: none;">
                        <div class="card shadow mb-4 form-customer">
                            <div class="card-header py-3">
                                <h5 class="m-0 font-weight-bold text-primary" style="color: #e67e22 !important">Giỏ Hàng 4</h5>
                            </div>
                            <div class="card-body">
                                <ul id="cart-list-cart-4" class="list-group list-group-flush">
                                    <!-- Sản phẩm sẽ được thêm vào đây -->
                                </ul>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Tổng tiền:</strong>
                                    <span class="font-weight-bold" id="total-price-cart-4">0 VNĐ</span>
                                </div>
                                <button class="btn btn-primary mt-3 checkout-btn" data-cart-id="cart-4">Đặt Hàng</button>
                            </div>
                        </div>
                    </div>
                    <!-- Giỏ hàng 5 -->
                    <div class="cart-container" id="cart-5" style="display: none;">
                        <div class="card shadow mb-4 form-customer">
                            <div class="card-header py-3">
                                <h5 class="m-0 font-weight-bold text-primary" style="color: #e67e22 !important">Giỏ Hàng 5</h5>
                            </div>
                            <div class="card-body">
                                <ul id="cart-list-cart-5" class="list-group list-group-flush">
                                    <!-- Sản phẩm sẽ được thêm vào đây -->
                                </ul>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Tổng tiền:</strong>
                                    <span class="font-weight-bold" id="total-price-cart-5">0 VNĐ</span>
                                </div>
                                <button class="btn btn-primary mt-3 checkout-btn" data-cart-id="cart-5">Đặt Hàng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="cart-popup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10000; background: rgba(0, 0, 0, 0.8); color: white; padding: 20px; border-radius: 10px; text-align: center; font-size: 16px;">
            <span>Sản phẩm đã được thêm vào giỏ hàng!</span>
        </div>

        <div class="modal fade" id="product-options-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="product-modal-title">Chọn tùy chọn sản phẩm</h5>
                        <i type="button" class="btn-close text-secondary fas fa-close" data-bs-dismiss="modal" aria-label="Close"></i>
                    </div>
                    <div class="modal-body">
                        <div id="product-options-container"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" id="confirm-options-btn" class="btn btn-primary">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Giỏ Hàng -->
        <div id="cart-sidebar" class="cart-sidebar">
            <div class="cart-sidebar-header d-flex justify-content-between">
                <h5 class="m-0 font-weight-bold text-primary">Giỏ Hàng</h5>
                <button class="close-sidebar-btn">&times;</button>
            </div>
            <div class="cart-sidebar-body">
                <ul id="cart-list-sidebar" class="list-group list-group-flush">
                    <!-- Giỏ hàng sẽ đồng bộ từ localStorage -->
                </ul>
                <div class="cart-sidebar-footer">
                    <strong>Tổng tiền: <span id="total-price-sidebar">0 VNĐ</span></strong>

                    <!-- Form Thông Tin Khách Hàng -->
                    @if(auth()->check())
                    <form id="customer-info-form" class="mt-3">
                        <div class="form-group" hidden>
                            <label for="customer-name">Tên Khách</label>
                            <input type="text" class="form-control" id="customer-name" placeholder="Nhập họ và tên" required>
                        </div>
                        <div class="form-group" hidden>
                            <label for="customer-phone">Số Điện Thoại</label>
                            <input type="text" class="form-control" id="customer-phone" placeholder="Nhập số điện thoại" required>
                        </div>
                        <div class="form-group" hidden>
                            <label for="customer-address">Địa Chỉ</label>
                            <input type="text" class="form-control" id="customer-address" placeholder="Nhập địa chỉ" required>
                        </div>
                        <button type="button" class="btn btn-primary btn-block mt-2" id="confirm-order-btn">
                            Xác Nhận Đơn Hàng
                        </button>
                    </form>
                    @else
                    <form id="customer-info-form" class="mt-3">
                        <div class="form-group">
                            <label for="customer-name">Tên Khách</label>
                            <input type="text" class="form-control" id="customer-name" placeholder="Nhập họ và tên" required>
                        </div>
                        <div class="form-group">
                            <label for="customer-phone">Số Điện Thoại</label>
                            <input type="text" class="form-control" id="customer-phone" placeholder="Nhập số điện thoại" required>
                        </div>
                        <div class="form-group">
                            <label for="customer-address">Địa Chỉ</label>
                            <input type="text" class="form-control" id="customer-address" placeholder="Nhập địa chỉ" required>
                        </div>
                        <button type="button" class="btn btn-primary btn-block mt-2" id="confirm-order-btn">
                            Xác Nhận Đơn Hàng
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let selectedCartId = 'cart-1'; // Mặc định chọn cart-1 khi trang được tải

            // Kiểm tra xem giỏ hàng đã được lưu trong localStorage chưa, nếu có thì tải về
            const carts = JSON.parse(localStorage.getItem('carts')) || {
                'cart-1': [],
                'cart-2': [],
                'cart-3': [],
                'cart-4': [],
                'cart-5': []
            };

            // Hàm cập nhật hiển thị của giỏ hàng
            function updateCart(cartId, flagCheckOut = null) {

                if (flagCheckOut === true) {
                    const carts = JSON.parse(localStorage.getItem('carts')) || {
                        'cart-1': [],
                        'cart-2': [],
                        'cart-3': [],
                        'cart-4': [],
                        'cart-5': []
                    };

                    const cartList = $(`#cart-list-${cartId}`);
                    const totalPriceEl = $(`#total-price-${cartId}`);
                    let total = 0;

                    // Làm sạch danh sách hiện tại
                    cartList.empty();

                    // Thêm sản phẩm vào danh sách
                    if (carts[cartId] && carts[cartId].length > 0) {
                        carts[cartId].forEach((product, index) => {
                            total += product.price * product.quantity;
                            cartList.append(`
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${product.name}</strong>
                            <p>Giá: ${parseFloat(product.price).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</p>
                            <p>Số lượng: ${product.quantity}</p>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-primary increase-quantity" data-cart-id="${cartId}" data-index="${index}">+</button>
                            <button class="btn btn-sm btn-warning decrease-quantity" data-cart-id="${cartId}" data-index="${index}">-</button>
                            <button class="btn btn-sm btn-danger remove-item" data-cart-id="${cartId}" data-index="${index}">Xóa</button>
                        </div>
                    </li>
                    `);
                        });
                    }

                    // Cập nhật tổng tiền
                    totalPriceEl.text(total.toLocaleString() + ' VNĐ');

                    // Lưu lại giỏ hàng vào localStorage
                    localStorage.setItem('carts', JSON.stringify(carts));
                } else {
                    const cartList = $(`#cart-list-${cartId}`);
                    const totalPriceEl = $(`#total-price-${cartId}`);
                    let total = 0;

                    // Làm sạch danh sách hiện tại
                    cartList.empty();

                    // Thêm sản phẩm vào danh sách
                    if (carts[cartId] && carts[cartId].length > 0) {
                        carts[cartId].forEach((product, index) => {
                            total += product.price * product.quantity;
                            cartList.append(`
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${product.name}</strong>
                            <p>Giá: ${parseFloat(product.price).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</p>
                            <p>Số lượng: ${product.quantity}</p>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-primary increase-quantity" data-cart-id="${cartId}" data-index="${index}">+</button>
                            <button class="btn btn-sm btn-warning decrease-quantity" data-cart-id="${cartId}" data-index="${index}">-</button>
                            <button class="btn btn-sm btn-danger remove-item" data-cart-id="${cartId}" data-index="${index}">Xóa</button>
                        </div>
                    </li>
                    `);
                        });
                    }

                    // Cập nhật tổng tiền
                    totalPriceEl.text(total.toLocaleString() + ' VNĐ');

                    // Lưu lại giỏ hàng vào localStorage
                    localStorage.setItem('carts', JSON.stringify(carts));
                }
            }

            // Chọn giỏ hàng
            $('.select-cart').on('click', function () {
                selectedCartId = $(this).data('cart-id'); // Cập nhật giỏ hàng đang được chọn

                // Hiển thị giỏ hàng được chọn và ẩn các giỏ hàng khác
                $('.cart-container').hide();
                $(`#${selectedCartId}`).show();

                // Cập nhật giỏ hàng
                updateCart(selectedCartId);
            });

            // Thêm sản phẩm vào giỏ hàng được chọn
            $(document).on('click', '.add-to-cart', function () {
                if (!selectedCartId) {
                    alert('Vui lòng chọn giỏ hàng trước!');
                    return;
                }

                const productEl = $(this); // Lấy phần tử i trực tiếp, vì đây là nơi lưu thông tin sản phẩm
                const productId = productEl.data('product-id'); // Lấy data từ thuộc tính data-product-id
                const productName = productEl.data('product-name'); // Lấy data từ thuộc tính data-product-name
                const productPrice = productEl.data('product-price'); // Lấy data từ thuộc tính data-product-price
                const productOption = productEl.data('product-option'); // Lấy data từ thuộc tính data-product-option

                // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
                const existingProduct = carts[selectedCartId].find(item => item.id === productId);
                if (existingProduct) {
                    existingProduct.quantity += 1;
                } else {
                    carts[selectedCartId].push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        quantity: 1,
                        option: productOption
                    });
                }

                updateCart(selectedCartId); // Cập nhật lại giỏ hàng
            });

            // Tăng số lượng sản phẩm
            $(document).on('click', '.increase-quantity', function () {
                const cartId = $(this).data('cart-id');
                const productIndex = $(this).data('index');

                carts[cartId][productIndex].quantity += 1;
                updateCart(cartId);
            });

            // Giảm số lượng sản phẩm
            $(document).on('click', '.decrease-quantity', function () {
                const cartId = $(this).data('cart-id');
                const productIndex = $(this).data('index');

                if (carts[cartId][productIndex].quantity > 1) {
                    carts[cartId][productIndex].quantity -= 1;
                } else {
                    carts[cartId].splice(productIndex, 1); // Xóa sản phẩm nếu số lượng <= 0
                }

                updateCart(cartId);
            });

            // Xóa sản phẩm khỏi giỏ hàng
            $(document).on('click', '.remove-item', function () {
                const cartId = $(this).data('cart-id');
                const productIndex = $(this).data('index');

                carts[cartId].splice(productIndex, 1);
                updateCart(cartId);
            });

            // Khôi phục trạng thái giỏ hàng từ localStorage khi trang được tải lại
            $('.cart-container').hide(); // Ẩn tất cả giỏ hàng
            $(`#${selectedCartId}`).show(); // Hiển thị giỏ hàng mặc định
            updateCart(selectedCartId); // Cập nhật giỏ hàng mặc định


            // Khi nhấn nút thanh toán
            $('.checkout-btn').on('click', function () {
                // Lấy tất cả giỏ hàng từ localStorage
                const carts = JSON.parse(localStorage.getItem('carts')) || {
                    'cart-1': [],
                    'cart-2': [],
                    'cart-3': [],
                    'cart-4': [],
                    'cart-5': []
                };

                // Lấy giỏ hàng cụ thể theo cartId
                const cartId = $(this).data('cart-id'); // Lấy cart-id từ data-attribute
                const cart = carts[cartId] || []; // Truy cập giỏ hàng tương ứng, nếu không có thì trả về giỏ hàng rỗng

                // Kiểm tra nếu giỏ hàng có sản phẩm
                if (cart.length > 0) {
                    // Lấy thông tin người dùng
                    const customerName = $('#customer-name').val();
                    const customerPhone = $('#customer-phone').val();
                    const customerAddress = $('#customer-address').val();

                    // Hiển thị loading spinner
                    showLoading();

                    // Gửi dữ liệu giỏ hàng đến backend
                    $.ajax({
                        url: "{{ route('orders.store') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            customerName: customerName,
                            customerPhone: customerPhone,
                            customerAddress: customerAddress,
                            items: cart,
                            total: cart.reduce((sum, item) => sum + item.price * item.quantity, 0),
                        },
                        success: function(response) {
                            // Ẩn loading spinner
                            hideLoading();

                            // Lấy tất cả giỏ hàng từ localStorage
                            let carts = JSON.parse(localStorage.getItem('carts')) || {};

                            carts[cartId] = []; // Đặt giỏ hàng cụ thể thành một mảng rỗng

                            // Lưu lại đối tượng carts vào localStorage sau khi xóa các phần tử trong giỏ hàng
                            localStorage.setItem('carts', JSON.stringify(carts));

                            // Kiểm tra và gọi updateCart() chỉ khi có sản phẩm trong giỏ hàng
                            updateCart(cartId, true);

                            // Chuyển đến trang in hóa đơn hoặc thông báo đơn hàng thành công
                            if (response.is_authenticated === true) {
                                window.location.href = `/orders/printReceipt/${response.order_id}`;
                            } else {
                                window.location.href = `/orders/printReceipt/${response.order_id}`;
                            }

                            if (response.is_authenticated === true) {
                                window.location.href = `/orders/print/${response.order_id}`;
                            } else {
                                window.location.href = `/order-success/${response.order_id}`;
                            }

                            // if (response.is_authenticated === true) {
                            //     const orderId = response.order_id;
                            //     printReceipt(orderId, cartId);
                            // } else {
                            //     window.location.href = `/order-success/${response.order_id}`;
                            // }
                        },
                        error: function(xhr) {
                            // Ẩn loading spinner
                            hideLoading();
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Đã xảy ra lỗi! Vui lòng thử lại.',
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Vui lòng đảm bảo có sản phẩm trong giỏ!',
                    });
                }
            });

            function printReceipt(orderId, cartId) {
                // Hiển thị loading spinner
                showLoading();

                const url = `${window.location.origin}/orders/printReceipt/${orderId}`;

                // Gửi dữ liệu giỏ hàng đến backend
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        // Ẩn loading spinner
                        hideLoading();

                        // Lấy tất cả giỏ hàng từ localStorage
                        let carts = JSON.parse(localStorage.getItem('carts')) || {};

                        carts[cartId] = []; // Đặt giỏ hàng cụ thể thành một mảng rỗng

                        // Lưu lại đối tượng carts vào localStorage sau khi xóa các phần tử trong giỏ hàng
                        localStorage.setItem('carts', JSON.stringify(carts));

                        // Kiểm tra và gọi updateCart() chỉ khi có sản phẩm trong giỏ hàng
                        updateCart(cartId, true);

                        Swal.fire({
                            icon: 'success',
                            title: 'Hoàn Tất',
                            text: 'Đơn hàng đã hoàn tất, kiểm tra hóa đơn đã được in',
                        });
                        // Chuyển đến trang in hóa đơn hoặc thông báo đơn hàng thành công
                        window.location.href = `/`;
                    },
                    error: function(xhr) {
                        // Ẩn loading spinner
                        hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Đã xảy ra lỗi khi in hóa đơn! Vui lòng thử lại.',
                        });
                    }
                });
            }
        });
    </script>
    <script>
        // Xử lý khi chọn danh mục
        $(document).on('click', '.category-item', function () {
            $('.category-item').removeClass('active');
            $(this).addClass('active');
            // Hiển thị loading spinner
            showLoading();
            const categoryId = $(this).data('category-id');

            $.ajax({
                url: `/categories/${categoryId}/products`,
                method: 'GET',
                success: function (data) {
                    // Hiển thị loading spinner
                    hideLoading()
                    const productContainer = $('#product-container');
                    productContainer.empty();

                    const productList = $('<ul class="list-group"></ul>');  // Create the ul container

                    data.forEach(product => {
                        const productItem = $(`
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 card-title">${product.name}</h6>
                                        <b class="text-muted">${(product.price / 1000).toFixed(0)}K</b>
                                    </div>
                                    <i class="fas fa-cart-plus add-to-cart text-success"
                                    data-product-id="${product.id}"
                                    data-product-name="${product.name}"
                                    data-product-price="${product.price}"
                                    data-product-option="${product.attributes}"
                                    ></i>
                                </div>
                            </li>
                        `);
                        productList.append(productItem);  // Append li to the ul
                    });

                    productContainer.append(productList);  // Append ul to the container
                },
                error: function () {
                    hideLoading()
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Lỗi khi tải danh sách sản phẩm. Vui lòng thử lại.',
                    });
                }
            });
        });

        // Xử lý khi chọn danh mục trên mobile (dùng select input)
        $(document).on('change', '#category-select', function () {
            const categoryId = $(this).val();

            showLoading()
            $.ajax({
                url: `/categories/${categoryId}/products`,
                method: 'GET',
                success: function (data) {
                    hideLoading()
                    const productContainer = $('#product-container');
                    productContainer.empty();

                    const productList = $('<ul class="list-group"></ul>');  // Create the ul container

                    data.forEach(product => {
                        const productItem = $(`
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 card-title">${product.name}</h6>
                                <b class="text-muted">${(product.price / 1000).toFixed(0)}K</b>
                            </div>
                            <i class="fas fa-cart-plus add-to-cart text-success"
                                    data-product-id="${product.id}"
                                    data-product-name="${product.name}"
                                    data-product-price="${product.price}"
                                    data-product-option="${product.attributes}"
                                    ></i>
                        </div>
                    </li>
                `);
                        productList.append(productItem);  // Append li to the ul
                    });

                    productContainer.append(productList);  // Append ul to the container
                },
                error: function () {
                    hideLoading()
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Lỗi khi tải danh sách sản phẩm. Vui lòng thử lại.',
                    });
                }
            });
        });

        function searchProductOnClick() {
            const query = $('#search-product').val().trim(); // Lấy giá trị nhập vào và loại bỏ khoảng trắng thừa
            if (!query) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Vui lòng nhập từ khóa tìm kiếm!',
                });
                return;
            }

            // Bỏ trạng thái "active" ở các mục trong danh sách menu
            $('.category-item').removeClass('active');

            showLoading();

            // Gọi Ajax tìm kiếm sản phẩm
            $.ajax({
                url: '/search-products', // Đường dẫn tới API tìm kiếm
                method: 'GET',
                data: { search: query }, // Dữ liệu gửi đi
                success: function(data) {
                    // Hiển thị loading spinner
                    hideLoading();
                    const productContainer = $('#product-container');
                    productContainer.empty();

                    const productList = $('<ul class="list-group"></ul>'); // Create the ul container

                    data.forEach(product => {
                        const productItem = $(`
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 card-title">${product.name}</h6>
                                <b class="text-muted">${(product.price / 1000).toFixed(0)}K</b>
                            </div>
                            <i class="fas fa-cart-plus add-to-cart text-success"
                                    data-product-id="${product.id}"
                                    data-product-name="${product.name}"
                                    data-product-price="${product.price}"
                                    data-product-option="${product.attributes}"
                                    ></i>
                        </div>
                    </li>
                `);
                        productList.append(productItem); // Append li to the ul
                    });

                    productContainer.append(productList); // Append ul to the container
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Đã xảy ra lỗi trong quá trình tìm kiếm sản phẩm',
                    });
                    hideLoading();
                }
            });
        }

        function searchProductOnEnter(event) {
            if (event.key === "Enter") { // Kiểm tra phím Enter
                const query = event.target.value.trim(); // Lấy giá trị nhập vào và loại bỏ khoảng trắng thừa
                if (!query) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Vui lòng nhập từ khóa tìm kiếm!',
                    });
                    return;
                }

                // Bỏ trạng thái "active" ở các mục trong danh sách menu
                $('.category-item').removeClass('active');

                showLoading();

                // Gọi Ajax tìm kiếm sản phẩm
                $.ajax({
                    url: '/search-products', // Đường dẫn tới API tìm kiếm
                    method: 'GET',
                    data: { search: query }, // Dữ liệu gửi đi
                    success: function(data) {
                        // Hiển thị loading spinner
                        hideLoading();
                        const productContainer = $('#product-container');
                        productContainer.empty();

                        const productList = $('<ul class="list-group"></ul>'); // Create the ul container

                        data.forEach(product => {
                            const productItem = $(`
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 card-title">${product.name}</h6>
                                    <b class="text-muted">${(product.price / 1000).toFixed(0)}K</b>
                                </div>
                                <i class="fas fa-cart-plus add-to-cart text-success"
                                    data-product-id="${product.id}"
                                    data-product-name="${product.name}"
                                    data-product-price="${product.price}"
                                    data-product-option="${product.attributes}"
                                    ></i>
                            </div>
                        </li>
                    `);
                            productList.append(productItem); // Append li to the ul
                        });

                        productContainer.append(productList); // Append ul to the container
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Đã xảy ra lỗi trong quá trình tìm kiếm sản phẩm',
                        });
                        hideLoading();
                    }
                });
            }
        }

    </script>
@endsection

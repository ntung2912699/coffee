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
                                            <i class="fas fa-cart-plus add-to-cart text-success" data-product-id="{{ $product->id }}" data-price="{{ $product->price }}" data-option="{{ $product->attributes }}"></i>
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
                <div class="card shadow mb-4 form-customer" style="min-height: 760px">
                    <div class="card-header py-3">
                        <div class="d-sm-flex align-items-center justify-content-between">
                            <h2 class="m-0 font-weight-bold text-primary"><i class="fas fa-cart-plus"></i></h2>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-outline-danger shadow-sm" id="clear-cart-btn">Xóa Giỏ Hàng</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Input Tên khách và Số điện thoại -->
                        <!-- Danh sách sản phẩm trong giỏ -->
                        <ul id="cart-list-left" class="list-group list-group-flush">
                            <!-- Các sản phẩm trong giỏ hàng sẽ được thêm vào đây -->
                        </ul>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Tổng tiền:</strong>
                            <span class="font-weight-bold" id="total-price">0 VNĐ</span>
                        </div>
                        @if(auth()->check())
                            <p style="margin-top: 20px" hidden>THÔNG TIN KHÁCH HÀNG</p>
                            <div class="row" hidden>
                                <div class="col-md-6">
                                    <label for="customer-name" class="form-label">Tên khách</label>
                                    <input type="text" class="form-control customer-name" id="customer-name-left" placeholder="Nhập tên khách">
                                </div>
                                <div class="col-md-6">
                                    <label for="customer-phone" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control customer-phone" id="customer-phone-left" placeholder="Nhập số điện thoại">
                                </div>
                                <div class="col-md-12">
                                    <label for="customer-address" class="form-label">Địa Chỉ</label>
                                    <input type="text" class="form-control customer-address" id="customer-address-left" placeholder="Nhập địa chỉ">
                                </div>
                            </div>
                        @else
                            <p style="margin-top: 20px">THÔNG TIN KHÁCH HÀNG</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="customer-name" class="form-label">Tên khách</label>
                                    <input type="text" class="form-control customer-name" id="customer-name-left" placeholder="Nhập tên khách">
                                </div>
                                <div class="col-md-6">
                                    <label for="customer-phone" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control customer-phone" id="customer-phone-left" placeholder="Nhập số điện thoại">
                                </div>
                                <div class="col-md-12">
                                    <label for="customer-address" class="form-label">Địa Chỉ</label>
                                    <input type="text" class="form-control customer-address" id="customer-address-left" placeholder="Nhập địa chỉ">
                                </div>
                            </div>
                        @endif
                        <button class="btn btn-primary mt-3 checkout-btn" id="checkout-btn">Đặt Hàng</button>
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
                                    <i class="fas fa-cart-plus add-to-cart text-success" data-product-id="${product.id}" data-price="${product.price}"></i>
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
                                data-price="${product.price}"></i>
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
                            <i class="fas fa-cart-plus add-to-cart text-success" data-product-id="${product.id}" data-price="${product.price}"></i>
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
                                <i class="fas fa-cart-plus add-to-cart text-success" data-product-id="${product.id}" data-price="${product.price}"></i>
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

    <script>
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

        // Khởi tạo một mảng giỏ hàng trống hoặc lấy giỏ hàng từ localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let selectedProductId = null;
        let selectedProductName = null;
        let selectedProductPrice = null;

        // Cập nhật giỏ hàng
        function updateCart() {
            // Cập nhật số lượng sản phẩm trong giỏ
            $('#cart-count').text(cart.length);

            // Cập nhật danh sách sản phẩm trong giỏ
            const cartList = $('#cart-list-left');
            cartList.empty();

            let totalPrice = 0;

            cart.forEach(item => {
                totalPrice += item.price * item.quantity; // Cộng dồn giá trị với số lượng

                const cartItem = $(`
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">${item.name}</h6>
                            <span>Giá: <b class="text-muted">${formatCurrency(item.price)}</b></span><br>
                            <span>Số lượng: ${item.quantity}</span>
                        </div>
                        <div>
                            <i class="fas fa-minus-circle decrease-quantity text-secondary" data-product-price="${item.price}" data-product-name="${item.name}" data-product-id="${item.id}"></i>
                            <i class="fas fa-trash-alt remove-from-cart text-danger" data-product-price="${item.price}" data-product-name="${item.name}" data-product-id="${item.id}"></i>
                            <i class="fas fa-plus-circle increase-quantity text-success" data-product-price="${item.price}" data-product-name="${item.name}" data-product-id="${item.id}"></i>
                        </div>
                    </div>
                </li>
            `);
                cartList.append(cartItem);
            });

            // Cập nhật tổng tiền
            $('#total-price').text(`${formatCurrency(totalPrice)}`);

            // Lưu giỏ hàng vào localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
        }

        // Format tiền VNĐ
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'decimal',
                maximumFractionDigits: 0
            }).format(amount) + ' VNĐ';
        }

        $(document).on('click', '.add-to-cart', function () {
            const productId = $(this).data('product-id');
            const productName = $(this).closest('li').find('.card-title').text();
            const productPrice = $(this).data('price');

            // Lưu thông tin sản phẩm được chọn tạm thời
            selectedProductId = productId;
            selectedProductName = productName;
            selectedProductPrice = parseInt(productPrice);

            // Cập nhật tiêu đề modal
            $('#product-modal-title').text(`Tùy chọn cho sản phẩm: ${productName}`);

            // Hiển thị loading
            showLoading();

            // Gửi yêu cầu để lấy danh sách attribute_name của sản phẩm
            $.getJSON(`/product/${productId}/attributes`, function (attributes) {
                // Kiểm tra nếu sản phẩm không có attribute_name, thì không hiển thị modal
                if (!attributes || attributes.length === 0) {
                    addProductToCart(productId, productName, productPrice, ''); // optionsName là chuỗi rỗng vì không có tùy chọn

                    hideLoading();
                    return;
                }

                const $modalContainer = $('#product-options-container');
                $modalContainer.empty(); // Xóa nội dung cũ

                // Fetch tất cả product options từ API
                $.getJSON(`/product-options`, function (options) {
                    // Lọc chỉ các attribute_name có trong sản phẩm hiện tại
                    const filteredOptions = Object.keys(options)
                        .filter(attrName => attributes.includes(attrName))
                        .reduce((obj, key) => {
                            obj[key] = options[key];
                            return obj;
                        }, {});

                    // Kiểm tra nếu không có tùy chọn nào sau khi lọc
                    if (Object.keys(filteredOptions).length === 0) {
                        addProductToCart(productId, productName, productPrice, ''); // optionsName là chuỗi rỗng vì không có tùy chọn

                        hideLoading();
                        return;
                    }

                    // Tạo giao diện checkbox cho từng nhóm tùy chọn
                    $.each(filteredOptions, function (attributeName, attributes) {
                        const $groupDiv = $('<div>', { class: 'option-group' });
                        $groupDiv.append(`<h5><b>${attributeName}</b></h5>`);

                        $.each(attributes, function (index, option) {
                            const optionHTML = `
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input option-checkbox" id="option-${option.attribute_value}" data-price="${option.price}" value="${option.attribute_value}">
                            <label class="form-check-label" for="option-${option.attribute_value}">
                                ${option.attribute_value} (${Number(option.price).toLocaleString()} VNĐ)
                            </label>
                        </div>`;
                            $groupDiv.append(optionHTML);
                        });

                        $modalContainer.append($groupDiv);
                    });

                    // Hiển thị modal
                    const modal = new bootstrap.Modal(document.getElementById('product-options-modal'));
                    modal.show();

                    // Ẩn loading khi xong
                    hideLoading();
                }).fail(function() {
                    hideLoading();
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Có lỗi khi tải tùy chọn sản phẩm!',
                    });
                });
            }).fail(function() {
                hideLoading();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Có lỗi khi tải thông tin sản phẩm!',
                });
            });
        });

        // Xử lý khi người dùng nhấn nút xác nhận trong modal
        $('#confirm-options-btn').on('click', function () {
            const selectedOptions = [];
            let totalOptionsPrice = 0;

            // Duyệt qua các checkbox đã chọn và thu thập thông tin
            $('.option-checkbox:checked').each(function () {
                selectedOptions.push($(this).val());
                totalOptionsPrice += parseInt($(this).data('price'));
            });

            // Tạo tên sản phẩm bao gồm các tùy chọn
            const finalProductName = `${selectedProductName} (${selectedOptions.join(' + ')})`;

            const finalOptionName = `${selectedOptions.join(' + ')}`;

            // Tính giá cuối cùng của sản phẩm
            const finalProductPrice = selectedProductPrice + totalOptionsPrice;

            // Thêm sản phẩm vào giỏ hàng
            addProductToCart(selectedProductId, finalProductName, finalProductPrice, finalOptionName);

            $('.option-checkbox').prop('checked', false);

            // Đóng modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('product-options-modal'));
            modal.hide();
        });

        // Hàm thêm sản phẩm vào giỏ hàng (đã có ở mã cũ)
        function addProductToCart(productId, productName, productPrice, optionsName) {
            const existingProductIndex = cart.findIndex(item => item.id === productId && item.name === productName);

            if (existingProductIndex === -1) {
                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1,
                    options: optionsName
                });
            } else {
                cart[existingProductIndex].quantity += 1;
            }

            // Cập nhật giỏ hàng
            updateCart();

            // Hiển thị popup xác nhận
            showCartPopup();
        }

        // Xóa sản phẩm khỏi giỏ
        $(document).on('click', '.remove-from-cart', function () {
            // const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            cart = cart.filter(item => item.name !== productName);
            updateCart();
        });

        // Giảm số lượng sản phẩm trong giỏ
        $(document).on('click', '.decrease-quantity', function () {
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            const productIndex = cart.findIndex(item => item.id === productId && item.name === productName);
            if (productIndex !== -1 && cart[productIndex].quantity > 1) {
                cart[productIndex].quantity -= 1;
            }
            updateCart();
        });

        // Tăng số lượng sản phẩm trong giỏ
        $(document).on('click', '.increase-quantity', function () {
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            const productIndex = cart.findIndex(item => item.id === productId && item.name === productName);
            if (productIndex !== -1) {
                cart[productIndex].quantity += 1;
            }
            updateCart();
        });

        // Xóa toàn bộ giỏ hàng
        $('#clear-cart-btn').click(function () {
            cart = [];
            updateCart();
        });

        // Xử lý thanh toán
        $('#checkout-btn').click(function () {
            const customerName = $('#customer-name-left').val();
            const customerPhone = $('#customer-phone-left').val();
            const customerAddress = $('#customer-address-left').val();

            // Nếu giỏ hàng rỗng
            if (cart.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Giỏ hàng trống!',
                });
                return;
            }

            // Kiểm tra thông tin khách hàng nếu chưa đăng nhập
            if (!isAuthenticated) {
                // Thay thế alert bằng SweetAlert2
                if (!customerName.trim()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Vui lòng nhập tên khách hàng.',
                    });
                    return;
                }
                if (!customerPhone.trim()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Vui lòng nhập số điện thoại.',
                    });
                    return;
                }
                if (!customerAddress.trim()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Vui lòng nhập địa chỉ.',
                    });
                    return;
                }
            }

            if (cart.length > 0) {
                // Hiển thị loading spinner
                showLoading()
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
                        hideLoading()

                        // Xóa giỏ hàng sau khi thanh toán
                        localStorage.removeItem('cart');
                        cart = [];
                        updateCart();

                        if (response.is_authenticated === true) {
                            // Nếu đã đăng nhập, chuyển đến trang in hóa đơn
                            window.location.href = `/orders/print/${response.order_id}`;
                        } else {
                            // Nếu chưa đăng nhập, chuyển đến trang thông báo đơn hàng thành công
                            window.location.href = `/order-success/${response.order_id}`;  // Đổi thành URL của trang thông báo đơn hàng thành công
                        }
                    },
                    error: function(xhr) {
                        // Ẩn loading spinner
                        hideLoading()
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

        // Tải lại giỏ hàng từ localStorage khi trang được load
        $(document).ready(function () {
            updateCart();
        });

        // Mở sidebar khi click vào biểu tượng cart
        $('#cart-icon').click(function () {
            $('#cart-sidebar').addClass('open');
            updateSidebarCart(); // Cập nhật giỏ hàng trong sidebar
        });

        // Đóng sidebar khi click vào nút close
        $('.close-sidebar-btn').click(function () {
            $('#cart-sidebar').removeClass('open');
        });

        // Cập nhật nội dung giỏ hàng trong sidebar
        function updateSidebarCart() {
            const cartListSidebar = $('#cart-list-sidebar');
            const totalPriceSidebar = $('#total-price-sidebar');

            cartListSidebar.empty(); // Xóa nội dung cũ
            let totalPrice = 0;

            // Lặp qua giỏ hàng và thêm sản phẩm vào sidebar
            cart.forEach(item => {
                totalPrice += item.price * item.quantity;

                const cartItem = $(`
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">${item.name}</h6>
                            <span>Giá: ${formatCurrency(item.price)}</span><br>
                            <span>Số lượng: ${item.quantity}</span><br>
                        </div>
                        <div>
                            <i class="fas fa-minus-circle decrease-quantity text-secondary" data-product-price="${item.price}" data-product-name="${item.name}" data-product-id="${item.id}"></i>
                            <i class="fas fa-trash-alt remove-from-cart text-danger" data-product-price="${item.price}" data-product-name="${item.name}" data-product-id="${item.id}"></i>
                            <i class="fas fa-plus-circle increase-quantity text-success" data-product-price="${item.price}" data-product-name="${item.name}" data-product-id="${item.id}"></i>
                        </div>
                    </li>
                `);

                cartListSidebar.append(cartItem);
            });

            // Cập nhật tổng tiền
            totalPriceSidebar.text(formatCurrency(totalPrice));
        }

        $(document).on('click', '.increase-quantity, .decrease-quantity, .remove-from-cart', function () {
            updateCart();
            updateSidebarCart();
        });

        // Xử lý khi nhấn nút đặt hàng trên sidebar
        $('#confirm-order-btn').click(function () {
            const customerName = $('#customer-name').val();
            const customerPhone = $('#customer-phone').val();
            const customerAddress = $('#customer-address').val();

            // Nếu giỏ hàng rỗng
            if (cart.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Giỏ hàng trống',
                });
                return;
            }

            // Kiểm tra thông tin khách hàng nếu chưa đăng nhập
            if (!isAuthenticated) {
                // Thay thế alert bằng SweetAlert2
                if (!customerName.trim()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Vui lòng nhập tên khách hàng.',
                    });
                    return;
                }
                if (!customerPhone.trim()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Vui lòng nhập số điện thoại.',
                    });
                    return;
                }
                if (!customerAddress.trim()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Vui lòng nhập địa chỉ.',
                    });
                    return;
                }
            }

            if (cart.length > 0) {
                // Hiển thị loading spinner
                showLoading()
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
                        hideLoading()

                        // Xóa giỏ hàng sau khi thanh toán
                        localStorage.removeItem('cart');
                        cart = [];
                        updateCart();

                        if (response.is_authenticated === true) {
                            // Nếu đã đăng nhập, chuyển đến trang in hóa đơn
                            window.location.href = `/orders/print/${response.order_id}`;
                        } else {
                            // Nếu chưa đăng nhập, chuyển đến trang thông báo đơn hàng thành công
                            window.location.href = `/order-success/${response.order_id}`;  // Đổi thành URL của trang thông báo đơn hàng thành công
                        }
                    },
                    error: function(xhr) {
                        // Ẩn loading spinner
                        hideLoading()
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Đã xảy ra lỗi! Vui lòng thử lại.',
                        });
                        return;
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

        // Hàm hiển thị popup và mở giỏ hàng trên mobile
        function showCartPopup() {
            const popup = $('#cart-popup');
            const sidebar = $('#cart-sidebar');

            // Hiển thị popup
            popup.fadeIn(300); // Hiển thị popup với hiệu ứng fade in
            setTimeout(() => {
                popup.fadeOut(300); // Ẩn popup sau 2 giây
            }, 2000); // Thời gian hiển thị là 2 giây

            // Kiểm tra nếu đang ở màn hình mobile
            if (window.innerWidth <= 768) { // Ngưỡng 768px cho thiết bị di động
                // Mở sidebar
                sidebar.addClass('open');
                updateSidebarCart(); // Cập nhật giỏ hàng trong sidebar

                // Tự động đóng sidebar sau 2 giây
                setTimeout(() => {
                    sidebar.removeClass('open');
                }, 1000);
            }
        }

    </script>
@endsection

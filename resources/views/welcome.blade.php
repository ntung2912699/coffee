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

        .category-list {
            max-height: 760px;
            overflow-y: auto;
        }

        .product-list {
            max-height: 760px;
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

    </style>
    <div class="dashboard" style="min-height: 760px;">
        <div class="row">
            <div class="background col-md-6 col-sm-12" style="width: 100%; height: 260px"></div>
            <div class="info-shop col-md-6 col-sm-12">
                <section class="text-left" style="background: #ffffff">
                    <h2>COFFE GIÓ</h2>
                    <p class="lead text-muted"><i class="fas fa-home"></i> : Số 3 - đường Đầm Vực Giang - xã Hạ Bằng - huyện Thạch Thất - tp Hà Nội</p>
                    <p class="lead text-muted"><i class="fas fa-phone"></i> : 0968 251 663</p>
                    <p class="lead text-muted"><i class="fas fa-clock"></i> : 8:00 AM - 23:30 PM</p>
                    <a href="{{ route('admin.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-user-alt fa-sm text-white-50"></i> Quản Lý Cửa Hàng
                    </a>
                </section>
            </div>
        </div>
        <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 30px">
            <h5 class="h5 mb-0 text-primary text-800"><i class="fas fa-shopping-bag"></i> MÀN HÌNH ORDER</h5>
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
                        <h2 class="m-0 font-weight-bold text-primary"><i class="fas fa-mug-hot"></i></h2>
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
                        <button class="btn btn-primary mt-3 checkout-btn" id="checkout-btn">Đặt Hàng</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Topping Selection Modal (Popup) -->
        <div id="topping-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="toppingModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="toppingModalLabel">Chọn topping</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul id="topping-list">
                            <!-- List of toppings will be inserted here dynamically -->
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="add-topping">Thêm topping</button>
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

            const categoryId = $(this).data('category-id');

            $.ajax({
                url: `/categories/${categoryId}/products`,
                method: 'GET',
                success: function (data) {
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
                                    <i class="fas fa-cart-plus add-to-cart text-success" data-product-id="${product.id}" data-price="${product.price}" data-option="${product.attributes}"></i>
                                </div>
                            </li>
                        `);
                        productList.append(productItem);  // Append li to the ul
                    });

                    productContainer.append(productList);  // Append ul to the container
                },
                error: function () {
                    alert('Lỗi khi tải danh sách sản phẩm. Vui lòng thử lại.');
                }
            });
        });

        // Xử lý khi chọn danh mục trên mobile (dùng select input)
        $(document).on('change', '#category-select', function () {
            const categoryId = $(this).val();

            $.ajax({
                url: `/categories/${categoryId}/products`,
                method: 'GET',
                success: function (data) {
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
                                data-price="${product.price}" data-option=${product.attributes}"></i>
                        </div>
                    </li>
                `);
                        productList.append(productItem);  // Append li to the ul
                    });

                    productContainer.append(productList);  // Append ul to the container
                },
                error: function () {
                    alert('Lỗi khi tải danh sách sản phẩm. Vui lòng thử lại.');
                }
            });
        });
    </script>

    <script>
        // Khởi tạo một mảng giỏ hàng trống hoặc lấy giỏ hàng từ localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let selectedTopping = null;
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
                            ${item.topping ? `<br><span>Topping: <b class="text-muted">${item.topping.name} - ${formatCurrency(item.topping.price)}</b></span>` : ''}
                        </div>
                        <div>
                            <i class="fas fa-minus-circle decrease-quantity text-secondary" data-product-id="${item.id}"></i>
                            <i class="fas fa-trash-alt remove-from-cart text-danger" data-product-id="${item.id}"></i>
                            <i class="fas fa-plus-circle increase-quantity text-success" data-product-id="${item.id}"></i>
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

        // Thêm sản phẩm vào giỏ
        $(document).on('click', '.add-to-cart', function () {
            const productId = $(this).data('product-id');
            const productName = $(this).closest('li').find('.card-title').text();
            const productPrice = $(this).data('price');
            let toppings = $(this).data('option');  // Lấy thông tin toppings từ data-option

            // // Kiểm tra nếu sản phẩm có topping
            // if (toppings && toppings.length > 0) {
            //     selectedProductId = productId;
            //     selectedProductName = productName;
            //     selectedProductPrice = productPrice;
            //     // Show topping selection modal
            //     showToppingModal(toppings, productName, productPrice);
            // } else {
                // Nếu không có topping, thêm sản phẩm vào giỏ hàng ngay
                addProductToCart(productId, productName, productPrice);
            // }
        });

        // Hiển thị popup chọn topping
        function showToppingModal(toppings) {
            const toppingList = $('#topping-list');
            toppingList.empty();

            toppings.forEach(topping => {
                const toppingItem = $(
                    `<li>
                        <label>
                            <input type="radio" name="topping" value="${topping.id}" data-price="${topping.price}">
                                ${topping.attribute_value}
                        </label>
                    </li>`);
            toppingList.append(toppingItem);
            });

            $('#topping-modal').modal('show');
            }

        // Thêm topping vào giỏ hàng
        $('#add-topping').click(function () {
            const selectedToppingId = $('input[name="topping"]:checked').val();
            const selectedToppingPrice = $('input[name="topping"]:checked').data('price');
            const selectedToppingName = $('input[name="topping"]:checked').parent().text().trim();

            if (!selectedToppingId) {
                alert('Vui lòng chọn topping!');
                return;
            }

            // Thêm sản phẩm vào giỏ hàng với tên sản phẩm chính và tên topping
            addProductToCart(selectedProductId, selectedProductName, selectedProductPrice, selectedToppingName, selectedToppingPrice);

            // Đóng modal
            $('#topping-modal').modal('hide');
        });

        // Thêm sản phẩm vào giỏ hàng (có hoặc không có topping)
        function addProductToCart(productId, productName, productPrice, toppingName = null, toppingPrice = 0) {
            const existingProductIndex = cart.findIndex(item => item.id === productId);

            // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
            if (existingProductIndex === -1) {
                cart.push({
                    id: productId,
                    name: productName,  // Tên sản phẩm chính
                    price: parseInt(productPrice) + parseInt(toppingPrice), // Cộng giá topping vào giá sản phẩm
                    quantity: 1,
                    topping: toppingName ? { name: toppingName, price: toppingPrice } : null
                });
            } else {
                // Nếu sản phẩm đã có trong giỏ hàng, tăng số lượng
                cart[existingProductIndex].quantity += 1;

                // Nếu có topping, cập nhật topping và cộng giá topping vào giá sản phẩm
                if (toppingName) {
                    cart[existingProductIndex].topping = { name: toppingName, price: toppingPrice };
                    cart[existingProductIndex].price = productPrice + toppingPrice * cart[existingProductIndex].quantity;
                }
            }

            // Cập nhật giỏ hàng sau khi thêm sản phẩm
            updateCart();
        }

        // Xóa sản phẩm khỏi giỏ
        $(document).on('click', '.remove-from-cart', function () {
            const productId = $(this).data('product-id');
            cart = cart.filter(item => item.id !== productId);
            updateCart();
        });

        // Giảm số lượng sản phẩm trong giỏ
        $(document).on('click', '.decrease-quantity', function () {
            const productId = $(this).data('product-id');
            const productIndex = cart.findIndex(item => item.id === productId);
            if (productIndex !== -1 && cart[productIndex].quantity > 1) {
                cart[productIndex].quantity -= 1;
            }
            updateCart();
        });

        // Tăng số lượng sản phẩm trong giỏ
        $(document).on('click', '.increase-quantity', function () {
            const productId = $(this).data('product-id');
            const productIndex = cart.findIndex(item => item.id === productId);
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

            if (customerName && customerPhone && cart.length > 0) {
                // Hiển thị loading spinner
                $('#loading-spinner').show();
                console.log(cart);
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
                        $('#loading-spinner').hide();

                        // Xóa giỏ hàng sau khi thanh toán
                        localStorage.removeItem('cart');
                        cart = [];
                        updateCart();

                        // Chuyển hướng đến trang in hóa đơn
                        window.location.href = `/orders/print/${response.order_id}`;
                    },
                    error: function(xhr) {
                        // Ẩn loading spinner
                        $('#loading-spinner').hide();
                        alert('Đã xảy ra lỗi! Vui lòng thử lại.');
                    }
                });
            } else {
                alert('Vui lòng nhập thông tin khách hàng và đảm bảo có sản phẩm trong giỏ!');
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
                    ${item.topping ? `<small>Topping: ${item.topping.name} - ${formatCurrency(item.topping.price)}</small>` : ''}
                </div>
                <div>
                    <i class="fas fa-minus-circle decrease-quantity text-secondary" data-product-id="${item.id}"></i>
                    <i class="fas fa-trash-alt remove-from-cart text-danger" data-product-id="${item.id}"></i>
                    <i class="fas fa-plus-circle increase-quantity text-success" data-product-id="${item.id}"></i>
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

            if (customerName && customerPhone && cart.length > 0) {
                // Hiển thị loading spinner
                $('#loading-spinner').show();
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
                        $('#loading-spinner').hide();

                        // Xóa giỏ hàng sau khi thanh toán
                        localStorage.removeItem('cart');
                        cart = [];
                        updateCart();

                        if (response.is_authenticated === true) {
                            // Nếu đã đăng nhập, chuyển đến trang in hóa đơn
                            window.location.href = `/orders/print/${response.order_id}`;
                        } else {
                            // Nếu chưa đăng nhập, chuyển đến trang thông báo đơn hàng thành công
                            window.location.href = '/order-success';  // Đổi thành URL của trang thông báo đơn hàng thành công
                        }
                    },
                    error: function(xhr) {
                        // Ẩn loading spinner
                        $('#loading-spinner').hide();
                        alert('Đã xảy ra lỗi! Vui lòng thử lại.');
                    }
                });
            } else {
                alert('Vui lòng nhập thông tin khách hàng và đảm bảo có sản phẩm trong giỏ!');
            }
        });

    </script>
@endsection

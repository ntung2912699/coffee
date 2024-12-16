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

    </style>
    <div class="dashboard" style="min-height: 760px;">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h5 class="h5 mb-0 text-gray-800"><img src="{{ asset('assets/logo/logo-gio.png') }}" style="width: 150px; height: 150px">MÀN HÌNH ORDER</h5>
            <div class="cart-icon" id="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span id="cart-count" class="badge badge-danger">0</span> <!-- Số lượng sản phẩm trong giỏ -->
            </div>

            <!-- Giỏ hàng dưới dạng Popup -->
            <div id="cart-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="cart-modal-label" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content form-customer">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cart-modal-label">Giỏ hàng</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Input Tên khách và Số điện thoại -->
                            <div class="mb-3">
                                <label for="customer-name" class="form-label">Tên khách</label>
                                <input type="text" class="form-control customer-name" id="customer-name" placeholder="Nhập tên khách">
                            </div>
                            <div class="mb-3">
                                <label for="customer-phone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control customer-phone" id="customer-phone" placeholder="Nhập số điện thoại">
                            </div>

                            <ul id="cart-list" class="list-group">
                                <!-- Các sản phẩm trong giỏ hàng sẽ được thêm vào đây -->
                            </ul>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Tổng tiền:</strong>
                                <span class="font-weight-bold" id="total-price-modal">0 VNĐ</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-primary checkout-btn" id="checkout-btn-modal">Thanh toán</button>
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
            <div class="col-lg-3" id="cart-right">
                <div class="card shadow mb-4 form-customer" style="min-height: 760px">
                    <div class="card-header py-3">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h4 class="m-0 font-weight-bold text-primary">ĐƠN HÀNG</h4>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-outline-danger shadow-sm" id="clear-cart-btn">Xóa Giỏ Hàng</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Input Tên khách và Số điện thoại -->
                        <div class="mb-3">
                            <label for="customer-name" class="form-label">Tên khách</label>
                            <input type="text" class="form-control customer-name" id="customer-name-left" placeholder="Nhập tên khách">
                        </div>
                        <div class="mb-3">
                            <label for="customer-phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control customer-phone" id="customer-phone-left" placeholder="Nhập số điện thoại">
                        </div>

                        <!-- Danh sách sản phẩm trong giỏ -->
                        <ul id="cart-list-left" class="list-group">
                            <!-- Các sản phẩm trong giỏ hàng sẽ được thêm vào đây -->
                        </ul>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Tổng tiền:</strong>
                            <span class="font-weight-bold" id="total-price">0 VNĐ</span>
                        </div>
                        <button class="btn btn-primary mt-3 checkout-btn" id="checkout-btn">Thanh toán</button>
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
    // Khởi tạo giỏ hàng
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    $(document).on('click', '.add-to-cart', function() {
        const productId = $(this).data('product-id');
        const productName = $(this).closest('li').find('span').text();
        const productPrice = $(this).data('price');
        const productOptions = $(this).data('options');  // Dữ liệu tùy chọn sản phẩm (nếu có)

        if (productOptions && productOptions.length > 0) {
            // Nếu sản phẩm có tùy chọn, hiển thị popup
            showProductOptionsPopup(productId, productName, productPrice, productOptions);
        } else {
            // Nếu không có tùy chọn, thêm vào giỏ hàng ngay lập tức
            addToCart(productId, productName, productPrice);
        }
    });

    // Hiển thị popup chọn tùy chọn
    function showProductOptionsPopup(productId, productName, productPrice, options) {
        let optionsHtml = '';

        // Tạo các lựa chọn option để hiển thị trong popup
        options.forEach(option => {
            optionsHtml += `
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="option-${option.id}" value="${option.name}">
                    <label class="form-check-label" for="option-${option.id}">
                        ${option.name}
                    </label>
                </div>
            </div>
        `;
        });

        $('#option-list').html(optionsHtml);

        // Hiển thị popup
        $('#product-option-modal').modal('show');

        $('#confirm-option-btn').off('click').on('click', function() {
            const selectedOptions = [];

            // Lấy các tùy chọn đã chọn
            options.forEach(option => {
                if ($(`#option-${option.id}`).prop('checked')) { // Kiểm tra checkbox đã chọn
                    selectedOptions.push(option.name); // Thêm tên option vào danh sách
                }
            });

            // Kiểm tra nếu không có option nào được chọn
            if (selectedOptions.length === 0) {
                alert("Vui lòng chọn ít nhất một tùy chọn!");
                return; // Nếu không có option nào được chọn, không đóng popup và không thêm vào giỏ hàng
            }

            // Thêm sản phẩm vào giỏ hàng cho từng sự kết hợp option
            selectedOptions.forEach(option => {
                const selectedOptionText = `${productName} (${option})`; // Sự kết hợp giữa tên sản phẩm và option đã chọn
                addToCart(productId, selectedOptionText, productPrice); // Thêm mỗi sự kết hợp vào giỏ hàng
            });

            // Đóng popup
            $('#product-option-modal').modal('hide');
        });
    }

    function addToCart(productId, productName, productPrice) {
        // Tạo một đối tượng sản phẩm với tên, giá và id
        const product = {
            id: productId,
            name: productName,
            price: productPrice,
            quantity: 1 // Đặt mặc định số lượng là 1
        };

        // Lấy giỏ hàng từ localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        const existingProductIndex = cart.findIndex(item => item.id === productId && item.name === productName);

        if (existingProductIndex === -1) {
            // Nếu chưa có, thêm sản phẩm mới vào giỏ hàng
            cart.push(product);
        } else {
            // Nếu sản phẩm đã có, tăng số lượng lên 1
            cart[existingProductIndex].quantity++;
        }

        // Lưu giỏ hàng vào localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Cập nhật giỏ hàng trên giao diện
        updateCart();
    }

    function updateCart() {
        // Lấy giỏ hàng từ localStorage mỗi khi cập nhật
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        $('#cart-list').empty();
        $('#cart-list-left').empty();

        let totalPrice = 0;
        let totalItems = 0;

        // Duyệt qua giỏ hàng để hiển thị sản phẩm
        cart.forEach(item => {
            const itemTotalPrice = item.price * item.quantity;
            $('#cart-list-left').append(`
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
        $('#total-price-modal').text(formatCurrency(totalPrice));
        $('#total-price').text(formatCurrency(totalPrice));
        $('#cart-count').text(totalItems); // Cập nhật số lượng sản phẩm
    }

    // Khôi phục giỏ hàng từ localStorage khi trang được tải lại
    $(document).ready(function() {
        updateCart(); // Đảm bảo giỏ hàng luôn được hiển thị khi tải lại trang
    });

    // Định dạng tiền tệ
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount).replace('₫', '') + ' VNĐ';
    }

    // Sự kiện thay đổi số lượng sản phẩm trong giỏ
    $(document).on('input', 'input[type="number"]', function() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
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
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
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
    $(document).on('click', '#cart-icon', function() {
        $('#cart-modal').modal('show');
    });

    // Xóa sản phẩm khỏi giỏ hàng
    $(document).on('click', '.remove-from-cart', function() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const productId = $(this).data('product-id');
        cart = cart.filter(item => item.id !== productId);

        // Lưu giỏ hàng sau khi xóa sản phẩm
        localStorage.setItem('cart', JSON.stringify(cart));

        updateCart();
    });

    $(document).on('click', '.checkout-btn', function() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        if (cart.length === 0) {
            alert('Giỏ hàng trống!');
            return;
        }

        // Tìm container chứa các input
        let parentElm = $(this).closest('.form-customer');

        // Lấy giá trị từ input
        const customerName = parentElm.find('.customer-name').val();
        const customerPhone = parentElm.find('.customer-phone').val();

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

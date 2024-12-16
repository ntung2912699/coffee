<script>
    // Khởi tạo giỏ hàng
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    $(document).on('click', '.add-to-cart', function() {
        const productId = $(this).data('product-id');
        const productName = $(this).closest('li').find('span').text();
        const productPrice = $(this).data('price');
        const productOptions = $(this).data('options');  // Dữ liệu tùy chọn sản phẩm (nếu có)
        console.log(productOptions);

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

    // Khôi phục giỏ hàng từ localStorage khi trang được tải lại
    $(document).ready(function() {
        updateCart(); // Đảm bảo giỏ hàng luôn được hiển thị khi tải lại trang
    });

    // Định dạng tiền tệ
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount).replace('₫', '') + ' VNĐ';
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
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        if (cart.length === 0) {
            alert('Giỏ hàng trống!');
            return;
        }

        // Gửi dữ liệu giỏ hàng đến backend
        $.ajax({
            url: "{{ route('orders.store') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                items: cart,
                total: cart.reduce((sum, item) => sum + item.price * item.quantity, 0),
            },
            success: function(response) {
                // Xóa giỏ hàng sau khi thanh toán
                localStorage.removeItem('cart');
                cart = [];
                updateCart();

                // Chuyển hướng đến trang in hóa đơn
                window.location.href = `/orders/print/${response.order_id}`;
            },
            error: function(xhr) {
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
@extends('admin.layout')


@section('content')
    <div class="content" style="min-height: 760px">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
        @if (auth()->check())
            <!-- Dropdown khi đăng nhập -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary shadow-sm dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-alt fa-sm text-white-50"></i> {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('welcome') }}"><i class="fas fa-cart-plus"></i> Màn Hình Order</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
        @else
            <!-- Nút hiển thị mặc định nếu chưa đăng nhập -->
                <a href="{{ route('login') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-sign-in-alt fa-sm text-white-50"></i> Đăng Nhập
                </a>
            @endif
            <h5 class="h5 mb-0 text-gray-800" style="margin: 10px">
                <a href="{{ route('dashboard') }}"><i class="fas fa-arrow-circle-left"></i></a> DANH ĐƠN HÀNG
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">TÊN KHÁCH HÀNG</th>
                    <th scope="col">SĐT</th>
                    <th scope="col">TRẠNG THÁI</th>
                    <th scope="col">TỔNG TIỀN</th>
                    <th scope="col">NGÀY ORDER</th>
                    <th scope="col">NGÀY TẠO</th>
                    <th scope="col">NGÀY SỬA</th>
                    <th scope="col">CHỨC NĂNG</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <th scope="row">{{ $order->id }}</th>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->phone_number }}</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ $order->total_price }}</td>
                            <td>{{ $order->order_date }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>{{ $order->updated_at }}</td>
                            <td>
                                <form method="POST" action="{{ route('orders.delete', ['id' => $order->id]) }}" id="deleteForm_{{ $order->id }}">
                                @csrf
                                <div class="btn-group-lg">
                                    <a href="{{ route('orders.print', ['id' => $order->id]) }}" type="button" class="btn btn-outline-success">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger deleteBtn" data-form-id="deleteForm_{{ $order->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Khi nhấn vào nút sửa
            $('.edit-product-btn').on('click', function () {
                const productId = $(this).data('id');
                const productName = $(this).data('name');
                const productCategoryId = $(this).data('category-id');
                const productPrice = $(this).data('price');
                const productDescription = $(this).data('description');
                const productOptions = $(this).data('options') || []; // Các tùy chọn trước đó, nếu có

                // Điền thông tin vào các trường của modal
                $('#productEditModal #name').val(productName);
                $('#productEditModal #category_id').val(productCategoryId);
                $('#productEditModal #price').val(productPrice);
                $('#productEditModal #description').val(productDescription);

                // Cập nhật action của form để gửi đúng sản phẩm
                $('#productEditModal form').attr('action', '/admin/product-update/' + productId);

                // Thêm các option động nếu có (nếu đã có tùy chọn trước đó)
                $('#optionsContainerEdit').empty(); // Xóa các tùy chọn cũ

                // Duyệt qua các tùy chọn và hiển thị chúng
                productOptions.forEach(function(option, index) {
                    $('#optionsContainerEdit').append(`
                <div class="form-group d-flex align-items-center mb-2" id="option${index}">
                    <input type="text" class="form-control" name="options[${index}]" value="${option.name}" placeholder="Tùy chọn ${index + 1}">
                    <button type="button" class="btn btn-outline-danger ms-2 removeOptionBtn" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `);
                });
            });

            // Khi nhấn nút "Thêm Tùy Chọn" trong modal chỉnh sửa
            $('#addOptionBtnEdit').on('click', function() {
                const optionCount = $('#optionsContainerEdit .form-group').length; // Đếm số lượng option hiện tại
                $('#optionsContainerEdit').append(`
            <div class="form-group d-flex align-items-center mb-2" id="option${optionCount}">
                <input type="text" class="form-control" name="options[${optionCount}]" placeholder="Tùy chọn ${optionCount + 1}">
                <button type="button" class="btn btn-outline-danger ms-2 removeOptionBtn" data-index="${optionCount}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `);
            });

            // Sử dụng event delegation để gắn sự kiện xóa cho các nút xóa dynamically added
            $(document).on('click', '.removeOptionBtn', function() {
                const index = $(this).data('index');
                $(`#option${index}`).remove(); // Xóa option
            });
        });



        function formatPrice(input) {
            let value = input.value.replace(/[^\d]/g, '');  // Remove non-numeric characters
            if (value) {
                // Add commas every 3 digits
                value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
                // Add 'VNĐ' suffix
                input.value = value + ' VNĐ';
            } else {
                input.value = '';
            }
        }

        $(document).ready(function() {
            // Initialize form validation
            $(".product-create").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
                    },
                    category_id: {
                        required: true
                    },
                    price: {
                        required: true,
                    },
                    description: {
                        required: false,
                        maxlength: 1000
                    }
                },
                messages: {
                    name: {
                        required: "Vui lòng nhập tên sản phẩm",
                        maxlength: "Tên sản phẩm không được vượt quá 255 ký tự"
                    },
                    category_id: {
                        required: "Vui lòng chọn danh mục sản phẩm"
                    },
                    price: {
                        required: "Vui lòng nhập giá sản phẩm",
                        number: "Giá sản phẩm phải là một số hợp lệ",
                        min: "Giá sản phẩm không thể nhỏ hơn 0"
                    },
                    description: {
                        required: "Vui lòng nhập mô tả sản phẩm",
                        maxlength: "Mô tả sản phẩm không được vượt quá 1000 ký tự"
                    }
                },
                errorElement: "span", // Customize error messages' element
                errorClass: "text-danger", // Customize error messages' class
                highlight: function(element) {
                    $(element).addClass('is-invalid'); // Add invalid class on invalid fields
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid'); // Remove invalid class when the field is valid
                }
            });

            $(".product-update").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
                    },
                    category_id: {
                        required: true
                    },
                    price: {
                        required: true,
                    },
                    description: {
                        required: false,
                        maxlength: 1000
                    }
                },
                messages: {
                    name: {
                        required: "Vui lòng nhập tên sản phẩm",
                        maxlength: "Tên sản phẩm không được vượt quá 255 ký tự"
                    },
                    category_id: {
                        required: "Vui lòng chọn danh mục sản phẩm"
                    },
                    price: {
                        required: "Vui lòng nhập giá sản phẩm",
                        number: "Giá sản phẩm phải là một số hợp lệ",
                        min: "Giá sản phẩm không thể nhỏ hơn 0"
                    },
                    description: {
                        required: "Vui lòng nhập mô tả sản phẩm",
                        maxlength: "Mô tả sản phẩm không được vượt quá 1000 ký tự"
                    }
                },
                errorElement: "span", // Customize error messages' element
                errorClass: "text-danger", // Customize error messages' class
                highlight: function(element) {
                    $(element).addClass('is-invalid'); // Add invalid class on invalid fields
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid'); // Remove invalid class when the field is valid
                }
            });
        });

        $(document).ready(function() {
            // Khi nhấn nút delete
            $(document).on("click", ".deleteBtn", function() {
                var formId = $(this).data("form-id");  // Lấy form ID tương ứng từ thuộc tính data-form-id
                console.log("Form ID:", formId); // Kiểm tra xem ID có đúng không

                // Hiển thị modal xác nhận
                $('#confirmDeleteModal').modal('show');

                // Khi nhấn "Có, Xóa" trong modal
                $("#confirmDeleteBtn").on("click", function() {
                    $("#" + formId).submit();  // Submit form tương ứng
                });
            });
        });

        $(document).ready(function() {
            // Khi nhấn vào biểu tượng "+"
            $("#addOptionBtn").on("click", function() {
                // Tạo một input mới với nút xóa
                var newOption = `
            <div class="form-group d-flex align-items-center mb-2" class="input-option">
                <input type="text" class="form-control form-control-user" name="options[]" placeholder="Tùy Chọn">
                <button type="button" class="btn btn-outline-danger ms-2 remove-option">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

                // Thêm input mới vào div #additional-options
                $("#additional-options").append(newOption);
            });

            // Khi nhấn vào nút "Xóa", xóa input option
            $(document).on("click", ".remove-option", function() {
                $(this).closest(".form-group").remove();
            });
        });

    </script>
@endsection

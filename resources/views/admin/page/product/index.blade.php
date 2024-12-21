@extends('admin.layout')


@section('content')
    <style>
        i {
            color: white !important;
        }
    </style>
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
                <a href="{{ route('dashboard') }}"><i class="fas fa-arrow-circle-left" style="color: #34495e !important;"></i></a> DANH SÁCH SẢN PHẨM
                <button class="btn btn-sm btn-outline-primary text-primary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#productModal">
                    <i class="fas fa-plus"></i>
                    Thêm Mới
                </button>
            </h5>
        </div>

        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <form class="product-create" action="{{ route('admin.product-store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Thêm Mới Sản Phẩm</h5>
                            <i type="button" class="btn-close fas fa-close" data-bs-dismiss="modal" aria-label="Close"></i>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name" class="form-label">Tên Sản Phẩm</label>
                                <input type="text" class="form-control form-control-user" name="name" id="name" placeholder="Tên Sản Phẩm">
                            </div>
                            <div class="form-group">
                                <label for="category_id" class="form-label">Danh Mục Sản Phẩm</label>
                                <select id="category_id" name="category_id" class="form-control">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ (isset($selectedCategory) && $selectedCategory == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="price" class="form-label">Giá Tiền</label>
                                <input type="text" class="form-control form-control-user" name="price" id="price" placeholder="Giá Tiền" onkeyup="formatPrice(this)">
                            </div>
                            <div class="form-group">
                                <label for="price" class="form-label">Tùy Chọn Sản Phẩm</label>
                                @foreach($attribute as $key=>$item)
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="attribute_name[]"
                                            value="{{ $key }}"
                                            id="flexCheck{{ $key }}">
                                        <label class="form-check-label" for="flexCheck{{ $key }}">
                                            {{ $key }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">DANH MỤC</th>
                    <th scope="col">TÊN</th>
                    <th scope="col">GIÁ TIỀN</th>
                    <th scope="col">MÔ TẢ</th>
                    <th scope="col">NGÀY TẠO</th>
                    <th scope="col">NGÀY SỬA</th>
                    <th scope="col">CHỨC NĂNG</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <th scope="row">{{ $product->id }}</th>
                            <td>{{ $product->category->name }}</td>
                            <td>
                                {{ $product->name }}
                            </td>
                            <td>{{ number_format($product->price , 0) }} VNĐ</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ $product->created_at }}</td>
                            <td>{{ $product->updated_at }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.product-delete', ['id' => $product->id]) }}" id="deleteForm_{{ $product->id }}">
                                @csrf
                                <div class="btn-group-lg">
                                    <button type="button" class="btn btn-outline-info edit-product-btn" data-bs-toggle="modal" data-bs-target="#productEditModal"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-category-id="{{ $product->category_id }}"
                                            data-price="{{ $product->price }}"
                                            data-description="{{ $product->description }}"
                                            data-attribute-name="{{ $product->attribute_name }}">
                                        <i class="fas fa-pen-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger deleteBtn" data-form-id="deleteForm_{{ $product->id }}">
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

    <!-- Modal Edit Product -->
    <div class="modal fade" id="productEditModal" tabindex="-1" aria-labelledby="productEditModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form class="product-update" action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Chỉnh Sửa Sản Phẩm</h5>
                        <i type="button" class="btn-close fas fa-close" data-bs-dismiss="modal" aria-label="Close"></i>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-label">Tên Sản Phẩm</label>
                            <input type="text" class="form-control form-control-user" name="name" id="name" placeholder="Tên Sản Phẩm">
                        </div>
                        <div class="form-group">
                            <label for="category_id" class="form-label">Danh Mục Sản Phẩm</label>
                            <select id="category_id" name="category_id" class="form-control">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price" class="form-label">Giá Tiền</label>
                            <input type="text" class="form-control form-control-user" name="price" id="price" placeholder="Giá Tiền" onkeyup="formatPrice(this)">
                        </div>
                        <!-- Attributes -->
                        <div class="form-group">
                            <label for="editProductAttributes" class="form-label">Tùy chọn sản phẩm</label>
                            @foreach($attribute as $key=>$item)
                                <div class="form-check">
                                    <input
                                        class="form-check-input edit-attribute-checkbox"
                                        type="checkbox"
                                        name="attribute_name[]"
                                        value="{{ $key }}"
                                        id="editCheckbox{{ $key }}">
                                    <label class="form-check-label" for="editCheckbox{{ $key }}">
                                        {{ $key }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script type="text/javascript">
        // Khi nhấn vào nút sửa
        $(document).ready(function () {
            // Khi nhấn vào nút sửa
            $('.edit-product-btn').on('click', function () {
                const productId = $(this).data('id');
                const productName = $(this).data('name');
                const productCategoryId = $(this).data('category-id');
                const productPrice = $(this).data('price');
                const productDescription = $(this).data('description');
                const attributes = this.dataset.attributeName ? this.dataset.attributeName.split(',') : [];

                // Điền thông tin vào các trường của modal
                $('#productEditModal #name').val(productName);
                $('#productEditModal #category_id').val(productCategoryId);
                $('#productEditModal #price').val(productPrice);
                $('#productEditModal #description').val(productDescription);

                // Xử lý checkbox
                const checkboxes = document.querySelectorAll('.edit-attribute-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = attributes.includes(checkbox.value);
                });

                // Cập nhật action của form để gửi đúng sản phẩm
                $('#productEditModal form').attr('action', '/admin/product-update/' + productId);
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

    </script>
@endsection

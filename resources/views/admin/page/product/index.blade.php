@extends('admin.layout')


@section('content')
    <div class="content" style="min-height: 760px">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><a href="{{ route('admin.index') }}"><i class="fas fa-arrow-circle-left"></i></a> Sản Phẩm</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#productModal">
                <i class="fas fa-plus"></i>
                Thêm Sản Phẩm Mới
            </a>
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
                                <label for="description" class="form-label">Mô Tả</label>
                                <input type="text" class="form-control form-control-user" name="description" id="description" placeholder="Mô Tả">
                            </div>

                            <!-- Chỗ để thêm input option -->
                            <label for="option" class="form-label">Tùy Chọn</label>
                            <div id="additional-options">
                            </div>

                            <!-- Biểu tượng "+" để thêm input option -->
                            <button type="button" class="btn btn-outline-info" id="addOptionBtn">
                                <i class="fas fa-plus"></i> Thêm Tùy Chọn
                            </button>
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
                            <td>{{ $product->name }}</td>
                            <td>
                                {{ $product->category->name }}
                                @if(count($product->attributes) > 0)
                                    [
                                    @foreach($product->attributes as $index => $item)
                                        {{ $item->name }}@if($index < count($product->attributes) - 1), @endif
                                    @endforeach
                                    ]
                                @endif

                            </td>
                            <td>{{ $product->price }}</td>
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
                                            data-options="{{ json_encode($product->attributes) }}"
                                            data-description="{{ $product->description }}">
                                        <i class="text-info fas fa-pen-alt"></i>
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
                        <div class="form-group">
                            <label for="description" class="form-label">Mô Tả</label>
                            <input type="text" class="form-control form-control-user" name="description" id="description" placeholder="Mô Tả">
                        </div>

                        <!-- Dynamic Option Inputs -->
                        <div id="optionsContainerEdit">
                            <label for="options" class="form-label">Tùy Chọn</label>
                            <!-- Các input option sẽ được thêm vào đây -->
                        </div>
                        <button type="button" id="addOptionBtnEdit" class="btn btn-outline-info">
                            <i class="fas fa-plus"></i> Thêm Tùy Chọn
                        </button>
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

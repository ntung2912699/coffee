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
                <a href="{{ route('dashboard') }}"><i class="fas fa-arrow-circle-left" style="color: #34495e !important;"></i></a> DANH DANH MỤC
                <button class="btn btn-sm btn-outline-primary text-primary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#categoryModal">
                    <i class="fas fa-plus"></i>
                    Thêm Mới
                </button>
            </h5>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <form class="category-create" action="{{ route('admin.category-store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Thêm Mới Danh Mục</h5>
                            <i type="button" class="btn-close fas fa-close" data-bs-dismiss="modal" aria-label="Close"></i>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name" class="form-label">Tên Danh Mục</label>
                                <input type="text" class="form-control form-control-user" name="name" id="name" placeholder="Tên Danh Mục">
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
                    <th scope="col">TÊN</th>
                    <th scope="col">NGÀY TẠO</th>
                    <th scope="col">NGÀY SỬA</th>
                    <th scope="col">CHỨC NĂNG</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <th scope="row">{{ $category->id }}</th>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->created_at }}</td>
                            <td>{{ $category->updated_at }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.category-delete', ['id' => $category->id]) }}" id="deleteForm_{{ $category->id }}">
                                @csrf
                                <div class="btn-group-lg">
                                    <button type="button" class="btn edit-category-btn" data-bs-toggle="modal" data-bs-target="#categoryEditModal" data-id="{{ $category->id }}"
                                    data-name="{{ $category->name }}">
                                        <i class="fas fa-pen-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger deleteBtn" data-form-id="deleteForm_{{ $category->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-center">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="categoryEditModal" tabindex="-1" aria-labelledby="categoryEditModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form class="category-update" action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Sửa Danh Mục</h5>
                        <i type="button" class="btn-close fas fa-close" data-bs-dismiss="modal" aria-label="Close"></i>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-label">Tên Danh Mục</label>
                            <input type="text" class="form-control form-control-user" name="name" id="name" placeholder="Tên Danh Mục">
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
    <!-- Include jQuery Validation Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.edit-category-btn').on('click', function () {
                const categoryId = $(this).data('id');
                const categoryName = $(this).data('name');
                const modal = $('#categoryEditModal');

                // Điền dữ liệu vào modal
                modal.find('#name').val(categoryName);

                // Cập nhật action của form
                modal.find('form').attr('action', `/admin/category-update/${categoryId}`);
            });
        });

        $(document).ready(function() {
            // Initialize form validation for the category form
            $(".category-create").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
                    }
                },
                messages: {
                    name: {
                        required: "Vui lòng nhập tên danh mục",
                        maxlength: "Tên danh mục không được vượt quá 255 ký tự"
                    }
                },
                errorElement: "span", // Error messages will be displayed as <span>
                errorClass: "text-danger", // Error messages will have this class
                highlight: function(element) {
                    $(element).addClass('is-invalid'); // Add 'is-invalid' class to the element if invalid
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid'); // Remove 'is-invalid' class when valid
                }
            });
        });

        $(document).ready(function() {
            // Initialize form validation for the category form
            $(".category-update").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
                    }
                },
                messages: {
                    name: {
                        required: "Vui lòng nhập tên danh mục",
                        maxlength: "Tên danh mục không được vượt quá 255 ký tự"
                    }
                },
                errorElement: "span", // Error messages will be displayed as <span>
                errorClass: "text-danger", // Error messages will have this class
                highlight: function(element) {
                    $(element).addClass('is-invalid'); // Add 'is-invalid' class to the element if invalid
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid'); // Remove 'is-invalid' class when valid
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

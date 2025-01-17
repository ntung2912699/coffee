@extends('admin.layout')

@section('content')
    <style>
        i {
            color: white !important;
        }
    </style>

    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
                color: black;
                font-size: 45px; /* Tăng cỡ chữ cho toàn bộ nội dung */
            }

            .invoice-header h1 {
                font-size: 50px; /* Cỡ chữ cho tiêu đề hóa đơn */
            }

            .invoice-info p,
            .invoice-total h4 {
                font-size: 30px; /* Cỡ chữ cho các thông tin đơn hàng */
            }

            .invoice-total h3 {
                font-size: 40px; /* Cỡ chữ cho các thông tin đơn hàng */
            }

            .table th, .table td {
                font-size: 35px; /* Cỡ chữ cho bảng chi tiết đơn hàng */
            }

            .invoice-total {
                font-size: 45px; /* Cỡ chữ cho tổng tiền */
            }

            /* Ẩn các phần tử không cần thiết khi in */
            .print-button, /* Ẩn nút in */
            .dropdown, /* Ẩn dropdown */
                /*.invoice-info !* Ẩn thông tin chi tiết *!*/
            .d-sm-flex.align-items-center.justify-content-between.mb-4 /* Ẩn tiêu đề */
            {
                display: none !important;
            }
        }

    </style>
    <div class="dashboad" style="min-height: 760px">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
        @if (auth()->check())
            <!-- Dropdown khi đăng nhập -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary shadow-sm dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-alt fa-sm text-white-50"></i> {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('welcome') }}"><i class="fas fa-cart-plus"></i> Màn Hình Order</a></li>
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-user-edit"></i> Màn Quản Lý</a></li>
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
                <a href="{{ route('dashboard') }}"><i class="fas fa-arrow-circle-left" style="color: #34495e !important;"></i></a> BÁO CÁO DOANH THU
                <button class="btn btn-sm btn-outline-primary text-primary d-sm-inline-block" onclick="window.print()">
                    <i class="fas fa-print"></i>
                    Xuất Báo Cáo
                </button>
            </h5>
        </div>

        <!-- Form nhập hàng -->
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <!-- Doanh thu theo ngày -->
                    <h3 style="color: #e67e22 !important;">Thống Kê Chi Phí Nhập Hàng</h3>
                    <form method="POST" action="{{ route('stock.store') }}">
                        @csrf
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="entry_date">Ngày Nhập:</label>
                                <input type="date" id="entry_date" name="entry_date" class="form-control" required>
                            </div>
                            <div class="form-group col-6">
                                <label for="entry_amount">Số Tiền:</label>
                                <input type="text" id="amount" name="amount" class="form-control" required
                                       oninput="formatCurrency(this)"
                                       onblur="addCurrencySymbol(this)"
                                       onfocus="removeCurrencySymbol(this)">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Nhập Hàng</button>
                                <a href="{{ route('profit') }}">Xem Báo Cáo Lợi Nhuận</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Ngày Nhập Hàng</th>
                            <th>Số Tiền</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($stockEntries as $stat)
                            <tr>
                                <td>{{ $stat->entry_date }}</td>
                                <td>{{ number_format($stat->amount, 0) }} VNĐ</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <!-- Doanh thu theo ngày -->
                    <h3 style="color: #e67e22 !important;">Thống Kê Doanh Thu Theo Ngày</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Tổng Doanh Thu</th>
                            <th>Tổng Đơn Hàng</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($revenueByDay as $stat)
                            <tr>
                                <td>{{ $stat->group_date }}</td>
                                <td>{{ number_format($stat->total_revenue, 0) }} VNĐ</td>
                                <td>{{ $stat->total_orders }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <!-- Doanh thu theo tuần -->
                    <h3 style="color: #e67e22 !important;">Thống Kê Doanh Thu Theo Tuần</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Tuần (Năm-Tuần)</th>
                            <th>Tổng Doanh Thu</th>
                            <th>Tổng Đơn Hàng</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($revenueByWeek as $stat)
                            <tr>
                                <td>{{ $stat->group_date }}</td>
                                <td>{{ number_format($stat->total_revenue, 0) }} VND</td>
                                <td>{{ $stat->total_orders }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <!-- Doanh thu theo tháng -->
                    <h3 style="color: #e67e22 !important;">Thống Kê Doanh Thu Theo Tháng</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Tháng</th>
                            <th>Tổng Doanh Thu</th>
                            <th>Tổng Đơn Hàng</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($revenueByMonth as $stat)
                            <tr>
                                <td>{{ $stat->group_date }}</td>
                                <td>{{ number_format($stat->total_revenue, 0) }} VND</td>
                                <td>{{ $stat->total_orders }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function formatCurrency(input) {
            // Lấy giá trị nhập vào
            let value = input.value.replace(/[^\d]/g, ''); // Xóa tất cả các ký tự không phải là số

            // Chia thành nhóm 3 số và thêm dấu phân cách nghìn
            if (value) {
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            // Cập nhật lại giá trị vào input với dấu phân cách nghìn
            input.value = value;
        }

        // Hàm để xử lý khi người dùng rời khỏi trường nhập liệu (blur)
        function addCurrencySymbol(input) {
            let value = input.value.replace(/[^\d]/g, ''); // Lọc chỉ số
            if (value) {
                input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' VNĐ'; // Thêm dấu phẩy và VNĐ
            }
        }

        // Hàm để xử lý khi người dùng nhấn nút "Xóa" hoặc làm rỗng input
        function removeCurrencySymbol(input) {
            let value = input.value.replace(/[^\d]/g, ''); // Lọc chỉ số
            input.value = value; // Loại bỏ VNĐ khi xóa
        }
    </script>

@endsection

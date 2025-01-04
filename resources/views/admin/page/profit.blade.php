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

        <div class="dashboard">
            <!-- Thêm ô lọc theo tháng -->
            <form method="GET" action="{{ route('profit') }}">
                <div class="d-flex align-items-center">
                    <div class="form-group me-4">
                        <!-- Nếu có request('month') thì dùng giá trị đó, không thì dùng tháng hiện tại -->
                        <input type="month" name="month" class="form-control" value="{{ request('month') ?? \Carbon\Carbon::now()->format('Y-m') }}" />
                    </div>
                    <button class="btn btn-outline-primary text-primary">
                        Lọc Báo Cáo
                    </button>
                </div>
            </form>

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h5 class="h5 mb-0 text-gray-800">Lợi Nhuận Tháng {{ request('month') ?? \Carbon\Carbon::now()->format('Y-m') }}</h5>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tổng Doanh Thu Tháng {{ request('month') ?? \Carbon\Carbon::now()->format('Y-m') }}</h4>
                        </div>
                        <div class="card-body">
                            <h3>{{ number_format($totalRevenue, 0) }} VND</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tổng Chi Phí Nhập Hàng Tháng {{ request('month') ?? \Carbon\Carbon::now()->format('Y-m') }}</h4>
                        </div>
                        <div class="card-body">
                            <h3>{{ number_format($totalExpenses, 0) }} VND</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Lợi Nhuận Tháng {{ request('month') ?? \Carbon\Carbon::now()->format('Y-m') }}</h4>
                        </div>
                        <div class="card-body">
                            <h3>{{ number_format($profit, 0) }} VND</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

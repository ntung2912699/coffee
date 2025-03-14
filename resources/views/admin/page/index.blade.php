@extends('admin.layout')

@section('content')
    <style>
        i {
            color: white !important;
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
               BẢNG ĐIỀU KHIỂN
            </h5>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <a href="{{ route('admin.category-index') }}">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Quản Lý Danh Mục Sản Phẩm</div>
                                    {{--                            <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>--}}
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <a href="{{ route('admin.product-index') }}">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Quản Lý Sản Phẩm</div>
                                    {{--                            <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>--}}
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-database fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <a href="{{ route('admin.orders-index') }}">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Quản Lý Đơn Hàng</div>
                                    {{--                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>--}}
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-cart-plus fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <a href="{{ route('admin.orders-revenue') }}">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Thống Kê Doanh Thu</div>
                                    {{--                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>--}}
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
{{--            <div class="col-xl-3 col-md-6 mb-4">--}}
{{--                <div class="card border-left-danger shadow h-100 py-2">--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="row no-gutters align-items-center">--}}
{{--                            <div class="col mr-2">--}}
{{--                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">--}}
{{--                                    Quản Lý Tài Khoản</div>--}}
{{--                                --}}{{--                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>--}}
{{--                            </div>--}}
{{--                            <div class="col-auto">--}}
{{--                                <i class="fas fa-comments fa-2x text-gray-300"></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
        <!-- Content Row -->

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold" style="color: #34495e !important;">Đơn Hàng Hôm Nay</h6>
            </div>
            <div class="card-body">
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
                    @foreach($ordersOfDay as $order)
                        <tr>
                            <th scope="row">{{ $order->id }}</th>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->phone_number }}</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ number_format($order->total_price, 0) }} VNĐ</td>
                            <td>{{ $order->order_date }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>{{ $order->updated_at }}</td>
                            <td>
                                <div class="btn-group-lg">
                                    <a href="{{ route('orders.print', ['id' => $order->id]) }}" type="button" class="btn btn-outline-success">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                    <div class="text-center">
                        {{ $ordersOfDay->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

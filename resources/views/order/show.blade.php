@extends('layout')

@section('content')
    <div class="container mt-5">
        <h2>Chi Tiết Đơn Hàng #{{ $order->id }}</h2>

        <div class="row">
            <!-- Thông tin khách hàng -->
            <div class="col-md-6">
                <h4>Thông Tin Khách Hàng</h4>
                <p><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
                <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->customer_address }}</p>
            </div>

            <!-- Thông tin đơn hàng -->
            <div class="col-md-6">
                <h4>Thông Tin Đơn Hàng</h4>
                <p><strong>Trạng thái:</strong> {{ $order->status }}</p>
                <p><strong>Tổng tiền:</strong> {{ number_format($order->total, 0, ',', '.') }} VNĐ</p>
            </div>
        </div>

        <hr>

        <!-- Danh sách sản phẩm -->
        <h4>Danh Sách Sản Phẩm</h4>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Tên Sản Phẩm</th>
                <th>Số Lượng</th>
                <th>Giá</th>
                <th>Tổng</th>
            </tr>
            </thead>
            <tbody>
            @foreach(json_decode($order->items, true) as $item)
                <tr>
                    <td>{{ $item['name'] }} ({{ $item['attributes'] }})</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ number_format($item['price'], 0, ',', '.') }} VNĐ</td>
                    <td>{{ number_format($item['quantity'] * $item['price'], 0, ',', '.') }} VNĐ</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <a href="{{ route('order.success') }}" class="btn btn-primary mt-4">Quay lại trang thông báo đơn hàng thành công</a>
    </div>
@endsection

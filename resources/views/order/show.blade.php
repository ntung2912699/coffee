@extends('admin.layout')

@section('content')
    <div class="container mt-5">
        <div class="text-center">
            <h2  style="color: #e67e22 !important;">Chi Tiết Đơn Hàng #{{ $order->id }}</h2>
        </div>

        <div class="row">
            <!-- Thông tin khách hàng -->
            <div class="col-md-12">
                <h5  style="color: #e67e22 !important;">+ Thông Tin Khách Hàng</h5>
                <p><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
                <p><strong>Số điện thoại:</strong> {{ $order->phone_number }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
            </div>

            <!-- Thông tin đơn hàng -->
            <div class="col-md-12">
                <h5  style="color: #e67e22 !important;">+ Thông Tin Đơn Hàng</h5>
                <p><strong>Trạng thái:</strong> {{ $order->status }}</p>
                <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
            </div>
        </div>

        <hr>

        <!-- Danh sách sản phẩm -->
        <h5  style="color: #e67e22 !important;">+ Danh Sách Sản Phẩm</h5>
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
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }} {{ $item->attributes }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                    <td>{{ number_format($item->quantity * $item->price, 0, ',', '.') }} VNĐ</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <a href="{{ route('welcome') }}" class="btn btn-primary mt-4">Tiếp tục mua hàng</a>
    </div>
@endsection

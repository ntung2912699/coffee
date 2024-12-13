@extends('admin.layout')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">THANH TOÁN ĐƠN HÀNG</h1>
        <a href="http://127.0.0.1:8000" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-user-alt fa-sm text-white-50"></i> Quay Về Trang Order
        </a>
    </div>
    <div class="container" style="max-width: 500px" id="invoice">
        <div class="invoice-header text-center">
            <h1 class="font-weight-bold">COFFEE GIÓ</h1>
            <p>Địa chỉ: 123 Đường ABC, Phường XYZ, TP HCM</p>
            <p>Điện thoại: 0123 456 789</p>
            <h3 class="mt-3">HÓA ĐƠN BÁN LẺ</h3>
        </div>

        <div class="invoice-info">
            <p><strong>Mã đơn hàng:</strong> {{ $order->id }}</p>
            <p><strong>Ngày:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
            <p><strong>Tên khách:</strong> {{ $order->customer_name }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->phone_number }}</p>
        </div>

        <table class="table table-bordered mt-3">
            <thead>
            <tr>
                <th style="width: 35%;">Tên sản phẩm</th>
                <th class="text-center" style="width: 10%;">SL</th>
                <th class="text-right" style="width: 27.5%;">Đơn giá</th>
                <th class="text-right" style="width: 27.5%;">Thành tiền</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price) }} VNĐ</td>
                    <td class="text-right">{{ number_format($item->price * $item->quantity) }} VNĐ</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="invoice-total">
            <h4 class="text-left"><strong>Tổng tiền: {{ number_format($order->total_price) }} VNĐ</strong></h4>
            <p class="text-center">Xin cảm ơn quý khách! Hẹn gặp lại!</p>
        </div>

{{--        <div class="text-center mt-4">--}}
{{--            <h4>Quét mã QR để thanh toán</h4>--}}
{{--            <img src="{{ asset($qrCodePath) }}" alt="QR Code" />--}}
{{--        </div>--}}

        <div class="print-button text-center mt-3">
           <form method="POST" action="{{ route('orders.cancel', ['id' => $order->id]) }}">
               @csrf
               <a href="{{ route('orders.printReceipt', ['id' => $order->id]) }}" class="btn btn-primary">In Hóa Đơn</a>
               @if ($order->status == 'pending')
               <button type="submit" class="btn btn-outline-danger">Huỷ Đơn Hàng</button>
               @else
               <button type="submit" class="btn btn-outline-danger" style="display:none;">Huỷ Đơn Hàng</button>
               @endif
           </form>
        </div>
    </div>

    <style>
        @media print {
            .print-button {
                display: none; /* Ẩn nút in */
            }
        }
    </style>
@endsection

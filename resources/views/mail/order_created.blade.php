<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CÓ ĐƠN HÀNG MỚI</title>
</head>
<body>
<h1>CHI TIẾT ĐƠN HÀNG</h1>
<p> ID: {{ $order->id }}</p>
<p> Tên Khách Hàng: {{ $order->customer_name }}</p>
<p> Số Điện Thoại: {{ $order->phone_number }}</p>
<p> Địa Chỉ: {{ $order->address }}</p>
<p> Tổng Tiền: {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
<p> Trạng Thái: {{ $order->status }}</p>
<p> Ngày Order: {{ $order->order_date }}</p>
<h3> Chi Tiết:</h3>
<ul>
    @foreach($order->items as $item)
        <li>{{ $item->product->name }} {{ $item->attributes }} + {{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }} VNĐ</li>
    @endforeach
</ul>
<a href="{{ route('orders.print', ['id' => $order->id]) }}">Xem chi tiết đơn hàng</a>
</body>
</html>

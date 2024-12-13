<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Có Đơn Hàng Mới Được Tạo</title>
</head>
<body>
<h1>Đơn Hàng Mới Tạo</h1>
<p> ID: {{ $order->id }}</p>
<p>Tổng Tiền: ${{ $order->total_price }}</p>
<p>Trạng Thái: {{ $order->status }}</p>
<p>Ngày Order: {{ $order->order_date }}</p>
<h3>Chi Tiết:</h3>
<ul>
    @foreach($order->items as $item)
        <li>{{ $item->product->name }} - {{ $item->quantity }} x ${{ $item->price }}</li>
    @endforeach
</ul>
</body>
</html>

@extends('admin.layout')

@section('content')
    <div class="container text-center mt-5">
        <!-- Đoạn thông báo đơn hàng thành công -->
        <div class="alert alert-success">
            <h1>Đặt Hàng Thành Công</h1>
            <p>Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi!</p>
            <p>Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.</p>
            <p>Cửa hàng sẽ liên hệ lại với bạn khi đơn hàng hoàn tất.</p>
        </div>

        <div class="mt-4">
            <a href="{{ route('welcome') }}" class="btn btn-primary">Quay về Trang Chủ</a>
            <a href="{{ route('orders.show', ['id' => $order->id ]) }}" class="btn btn-secondary">Xem Đơn Hàng của Tôi</a>
        </div>
    </div>
@endsection

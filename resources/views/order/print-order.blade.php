@extends('admin.layout')

@section('content')
    <style>
        i {
            color: white !important;
        }

        b, strong {
            font-weight: 700 !important;
        }
    </style>
    <style>
        @media print {
    body {
        font-family: Arial, sans-serif;
        color: black;
        font-size: 50px !important; /* Tăng cỡ chữ cho toàn bộ nội dung */
    }

    .invoice-header h1 {
        font-size: 50px; /* Cỡ chữ cho tiêu đề hóa đơn */
    }

    h5 {
        font-size: 40px;
        }
    .invoice-info p,
    .invoice-total h4,
    .invoice-total h3{
        font-size: 40px; /* Cỡ chữ cho các thông tin đơn hàng */
    }

    .invoice-header p,
    .invoice-header h3 {
        font-size: 40px;
    } 

    .invoice-info p, 
    .invoice-total h4 {
        font-size: 40px; /* Cỡ chữ cho các thông tin đơn hàng */
    }

    .invoice-total h3,
    .invoice-total h4 {
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
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        @if (auth()->check())
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
            <a href="{{ route('login') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-sign-in-alt fa-sm text-white-50"></i> Đăng Nhập
            </a>
        @endif
        <h5 class="h5 mb-0 text-gray-800" style="margin: 10px">
            <a href="{{ route('dashboard') }}"><i class="fas fa-arrow-circle-left" style="color: #34495e !important;"></i></a> CHI TIẾT ĐƠN HÀNG
        </h5>
    </div>
    <div class="container" style="max-width: 700px" id="invoice">
        <div class="invoice-header text-center">
            <h1 class="font-weight-bold">COFFEE GIÓ</h1>
            <p>Địa chỉ: Số 3 - đường Đầm Vực Giang - xã Hạ Bằng - huyện Thạch Thất - tp Hà Nội</p>
            <p>Điện thoại: 0968 251 663</p>
            <h3 class="mt-3">HÓA ĐƠN BÁN LẺ</h3>
        </div>
        <div class="invoice-info">
            <p><strong>Mã đơn hàng:</strong> {{ $order->id }}</p>
            <p><strong>Trạng thái:</strong> <span id="status-order-prt">{{ $order->status }}</span></p>
            <p><strong>Ngày:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
            <p><strong>Tên khách:</strong> {{ $order->customer_name }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->phone_number }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
        </div>
        <b class="mt-3">CHI TIẾT ĐƠN HÀNG :</b>
        <table class="table table-bordered mt-3">
            <thead>
            <tr>
                <th style="width: 40%;">Tên sản phẩm</th>
                <th class="text-center" style="width: 10%;">SL</th>
                <th class="text-right" style="width: 25%;">Đơn giá</th>
                <th class="text-right" style="width: 25%;">Thành tiền</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }} {{ $item->attributes }}</td>
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
                <a onclick="printReceipt()" class="btn btn-primary"><i class="fas fa-print"></i> In Hóa Đơn</a>
                @if ($order->status == 'pending')
                    <button type="submit" class="btn btn-outline-danger">Huỷ Đơn Hàng</button>
                @else
                    <button type="submit" class="btn btn-outline-danger" style="display:none;">Huỷ Đơn Hàng</button>
                @endif
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function printReceipt() {
            // Ẩn các phần không cần thiết
            var elementsToHide = document.querySelectorAll('.print-button, .dropdown, .d-sm-flex.align-items-center.justify-content-between.mb-4');
            elementsToHide.forEach(function(element) {
                element.style.display = 'none';
            });

            showLoading();

            $.ajax({
                url: "{{ route('orders.updateStatus', ['id' => $order->id]) }}",
                type: "POST",
                data: {
                    order_id: {{ $order->id }},
                    status: 'completed',
                    _token: "{{ csrf_token() }}", // CSRF token
                },
                success: function (response) {
                    $('#status-order-prt').text(response.order.status)
                    hideLoading();
                    // Mở cửa sổ in
                    window.print();
                },
                error: function (xhr, status, error) {
                    hideLoading();
                    alert("An error occurred: " + error);
                }
            });

            // Sau khi in, hiển thị lại các phần tử đã ẩn
            elementsToHide.forEach(function(element) {
                element.style.display = '';
            });
        }
        //
        // function printInvoice() {
        //     // Tạo một bản sao của trang hoặc phần tử mà bạn muốn in
        //     var printContents = document.getElementById('invoice').innerHTML;
        //     var originalContents = document.body.innerHTML;
        //
        //     // Thay đổi nội dung trang web trước khi in
        //     document.body.innerHTML = printContents;
        //
        //     // Gọi chức năng in của trình duyệt
        //     window.print();
        //
        //     // Khôi phục lại nội dung ban đầu của trang sau khi in
        //     document.body.innerHTML = originalContents;
        // }

    </script>
@endsection

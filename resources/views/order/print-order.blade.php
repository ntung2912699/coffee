@extends('admin.layout')

@section('content')
    <style>
        i {
            color: white !important;
        }

        .table th, .table td {
            border: 0;
            color: #858796 !important;
            text-align: left;
        }
    </style>
    <style>
        @media print {
            @page {
                size: 80mm auto; /* In trên giấy cuộn 80mm */
            }

            body {
                width: 80mm;
                height: auto;
                margin: 0;
                padding: 0;
                font-size: 12px; /* Kích thước chữ phù hợp */
                line-height: 1.5; /* Khoảng cách dòng vừa phải */
            }

            .invoice {
                width: 100%;
                max-width: 80mm; /* Đảm bảo không vượt quá khổ giấy */
                page-break-before: avoid;
                page-break-after: avoid;
                page-break-inside: avoid;
            }

            /* Đảm bảo mỗi sản phẩm trong hóa đơn không bị tách trang */
            .invoice-item {
                page-break-inside: avoid;
                break-inside: avoid;
                display: block;
            }

            /* Ẩn header, footer không cần thiết khi in */
            header, footer, .no-print {
                display: none;
            }

            body {
                margin: 0;
                font-family: Arial, sans-serif;
                font-size: 40px;
                color: black;
            }

            p {
                margin-bottom: 0 !important;
            }

            .invoice {
                width: 100%;
                page-break-inside: avoid;
            }

            .invoice-total p {
                font-size: 40px;
            }

            .invoice-total h4 span {
                font-size: 40px;
            }

            .invoice-header p {
                font-size: 40px !important;
            }

            .invoice-header h1 {
                font-size: 45px !important;
            }

            .invoice-header h3 {
                font-size: 40px !important;
            }

            .invoice-info p,
            .invoice-info span {
                font-size: 40px !important;
            }

            .title-order {
                font-size: 40px !important;
            }

            .table {
                width: 100%;
                border-collapse: collapse;
            }

            .table th, .table td {
                font-size: 40px !important;
                border: 0;
                color: #000000 !important;
                padding: 4px;
                text-align: left;
            }

            .print-button, .dropdown, .d-sm-flex.align-items-center.justify-content-between.mb-4 , .none-content-print{
                display: none !important; /* Ẩn các phần không cần in */
            }

            #status-order {
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

    <div class="print-button text-center mt-3" style="margin-bottom: 15px">
        <form method="POST" action="{{ route('orders.cancel', ['id' => $order->id]) }}">
            @csrf
            <a onclick="printReceipt()" class="btn btn-primary"><i class="fas fa-print"></i> In Hóa Đơn</a>
            {{--                @if ($order->status == 'pending')--}}
            {{--                    <button type="submit" class="btn btn-outline-danger">HỦY ĐƠN</button>--}}
            {{--                @else--}}
            {{--                    <button type="submit" class="btn btn-outline-danger" style="display:none;">HỦY ĐƠN</button>--}}
            {{--                @endif--}}
        </form>
    </div>

    <div class="container invoice" style="max-width: 700px; margin-bottom: 40px; background-color: #ffffff; padding: 20px" id="invoice">
        <div class="invoice-header text-center">
            <h1 class="font-weight-bold" style="font-size: 28px;">COFFEE GIÓ</h1>
            <p style="font-size: 16px;">Đ/C: Số 3 - đường Đầm Vực Giang - Hạ Bằng - Thạch Thất - Hà Nội</p>
            <p style="font-size: 16px;">Điện thoại: 0968 251 663</p>
            <h3 class="mt-3" style="font-size: 25px;">HÓA ĐƠN BÁN LẺ</h3>
        </div>

        <div class="row">
            <div class="invoice-info col-12">
                <p><span>Mã đơn hàng:</span> {{ $order->id }}</p>
                <p id="status-order"><span>Trạng thái:</span> <span id="status-order-prt">{{ $order->status }}</span></p>
                <p><span>Ngày:</span> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
{{--                @if($order->customer_name === 'new customer')--}}
{{--                    <p class="none-content-print"><span>Tên khách:</span> {{ $order->customer_name }}</p>--}}
{{--                @else--}}
{{--                    <p><span>Tên khách:</span> {{ $order->customer_name }}</p>--}}
{{--                @endif--}}
{{--                @if($order->phone_number === 'N/A')--}}
{{--                    <p class="none-content-print"><span>Số điện thoại:</span> {{ $order->phone_number }}</p>--}}
{{--                @else--}}
{{--                    <p><span>Số điện thoại:</span> {{ $order->phone_number }}</p>--}}
{{--                @endif--}}
{{--                @if($order->address === 'N/A')--}}
{{--                    <p class="none-content-print"><span>Địa chỉ:</span> {{ $order->address }}</p>--}}
{{--                @else--}}
{{--                    <p><span>Địa chỉ:</span> {{ $order->address }}</p>--}}
{{--                @endif--}}
            </div>
        </div>

        <h5 class="title-order">CHI TIẾT ĐƠN HÀNG :</h5>
        <table class="table table-bordered mt-3">
            <thead>
            <tr>
                <th style="width: 40%;">Tên sản phẩm</th>
                <th class="text-center" style="width: 10%;">SL</th>
                <th class="text-right" style="width: 25%;">Giá</th>
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
            <h4 class="text-left"><span>Tổng tiền: {{ number_format($order->total_price) }} VNĐ</span></h4>
            <div class="invoice-paypal text-center col-12 text-center">
                <img style="width: 80%" src="{{ $qrCode }}" alt="QR Code Thanh Toán">
                <p>Quét mã QR để thanh toán</p>
            </div>
            <p class="text-center">Xin cảm ơn quý khách! Hẹn gặp lại!</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function printReceipt() {
            var elementsToHide = document.querySelectorAll('.print-button, .dropdown, .d-sm-flex.align-items-center.justify-content-between.mb-4');
            elementsToHide.forEach(function(element) {
                element.style.display = 'none';
            });

            $.ajax({
                url: "{{ route('orders.updateStatus', ['id' => $order->id]) }}",
                type: "POST",
                data: {
                    order_id: {{ $order->id }},
                    status: 'completed',
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    $('#status-order-prt').text(response.order.status);
                    window.print();
                },
                error: function (xhr, status, error) {
                    alert("Đã xảy ra lỗi: " + error);
                }
            });

            setTimeout(function () {
                elementsToHide.forEach(function(element) {
                    element.style.display = '';
                });
            }, 1000);
        }
    </script>
@endsection

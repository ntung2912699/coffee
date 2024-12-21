@extends('admin.layout')

@section('content')
    <style>
        i {
            color: white !important;
        }
    </style>
    <div class="text-center">
        <div class="error mx-auto" data-text="404">404</div>
        <p class="lead text-gray-800 mb-5">Page Not Found</p>
        <p class="text-gray-500 mb-0">Có vẻ như bạn đã tìm thấy lỗi trong hệ thống...</p>
        <a href="{{ route('welcome') }}">← Quay Về Trang Chính</a>
    </div>
@endsection


<!DOCTYPE html>
<html lang="en">
@include('admin.component.head')
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
{{--        @include('admin.component.left_menu')--}}
        <!-- End Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <style>
                    /*#loading-spinner {*/
                    /*    display: none; !* Ban đầu ẩn *!*/
                    /*    position: fixed;*/
                    /*    top: 50%;*/
                    /*    left: 50%;*/
                    /*    transform: translate(-50%, -50%);*/
                    /*    z-index: 9999;*/
                    /*}*/

                    /*.spinner {*/
                    /*    border: 4px solid #f3f3f3; !* Màu nền *!*/
                    /*    border-top: 4px solid #3498db; !* Màu spinner *!*/
                    /*    border-radius: 50%;*/
                    /*    width: 50px;*/
                    /*    height: 50px;*/
                    /*    animation: spin 2s linear infinite;*/
                    /*}*/

                    /*@keyframes spin {*/
                    /*    0% { transform: rotate(0deg); }*/
                    /*    100% { transform: rotate(360deg); }*/
                    /*}*/

                    #loading-overlay {
                        display: none; /* Ban đầu ẩn */
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background-color: rgba(0, 0, 0, 0.5); /* Màu nền mờ */
                        z-index: 9998; /* Nền nằm dưới spinner */
                    }

                    #loading-spinner {
                        display: none; /* Ban đầu ẩn */
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        z-index: 9999; /* Spinner nằm trên overlay */
                    }

                    .spinner {
                        border: 4px solid #f3f3f3; /* Màu nền */
                        border-top: 4px solid #3498db; /* Màu spinner */
                        border-radius: 50%;
                        width: 50px;
                        height: 50px;
                        animation: spin 2s linear infinite;
                    }

                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }

                </style>
                <!-- Thêm phần tử loading bên dưới nút thanh toán -->
                <div id="loading-overlay"></div>
                <div id="loading-spinner" style="display: none;">
                    <div class="spinner"></div> <!-- Có thể thay đổi bằng icon bạn muốn -->
                </div>

                <div style="height:15px"></div>
{{--       @include('admin.component.header')--}}
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <i type="button" class="btn-close fas fa-trash-alt" data-bs-dismiss="alert" aria-label="Close"></i>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <i type="button" class="btn-close fas fas fas fa-trash-alt" data-bs-dismiss="alert" aria-label="Close"></i>
                        </div>
                    @endif
                <!-- Modal xác nhận xóa -->
                    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteModalLabel">Xác Nhận Xóa</h5>
                                </div>
                                <div class="modal-body">
                                    Bạn có chắc chắn muốn xóa không?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Có, Xóa</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>

            <!-- Footer -->
            @include('admin.component.footer')
            <!-- End Footer -->
        </div>
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showLoading() {
            document.getElementById('loading-overlay').style.display = 'block';
            document.getElementById('loading-spinner').style.display = 'block';
        }

        function hideLoading() {
            document.getElementById('loading-overlay').style.display = 'none';
            document.getElementById('loading-spinner').style.display = 'none';
        }
    </script>
</body>

</html>

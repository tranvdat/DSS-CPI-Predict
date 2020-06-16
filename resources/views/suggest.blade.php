@extends('admin_layout')
@section('title')
<title>Trang Chủ</title>
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    @include('general.content-header',['name' => 'Trang Chủ'])
    <!-- /.content-header -->

    <div class="content">
        @if(!file_exists('C:/xampp/htdocs/DSS-CPI-Predict/public/py/Results/saiso.text'))
        <h3>Chưa có dữ liệu! Nhấn <a href="{{route('data')}}">vào đây!</a></h3>


        @else
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="{{route('suggest')}}">Gợi Ý</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('dashboard')}}">Kết Quả<span
                                class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('trend')}}">Xu Hướng</a>
                    </li>

                </ul>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Gợi ý cho nhà quản trị trong thời gian tới:</h3> <br>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td colspan="2"> CPI Việt Nam có sự sụt giảm trong 6 tháng đầu năm nay do
                                                ảnh hưởng của dịch bênh, nhưng đã có sự hồi phục lại.</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" rowspan="2">Đối với nhà nước</th>
                                            <td>Những gói hỗ trợ, chính sách kích cầu kinh tế, biện pháp phòng ngừa dịch
                                                bệnh đã giúp cứu vãn tình hình nền kinh tế.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Nhưng CPI vẫn còn ở mức khá cao do một số mặt hàng nhu yếu phẩm tăng
                                                giá. Cần có biện pháp rà soát, xuất nhập hàng hợp lý cho những mặt hàng
                                                này.</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" rowspan="2">Đối với doanh nghiệp</th>
                                            <td>CPI tăng trưởng cho thấy sức mua hàng của người tiêu dùng tăng lên sau
                                                dịch bệnh. Nên sớm quay lại sản xuất, và tìm những sản phẩm có tiềm năng
                                                trong thời gian này.</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection


@section('css')
<link rel="stylesheet" href="{{asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('vendor/predict.css')}}">

@endsection

@section('js')
<script src="{{asset('adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="{{asset('adminlte/dist/js/demo.js')}}"></script>
@endsection

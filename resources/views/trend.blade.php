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
            <a class="navbar-brand" href="{{route('trend')}}">Xu Hướng</a>
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
                        <a class="nav-link" href="{{route('suggest')}}">Gợi Ý</a>
                    </li>

                </ul>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7">
                    <div class="card">

                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Xu Hướng CPI Việt Nam</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <p class="d-flex flex-column">
                                </p>
                                <p class="ml-auto d-flex flex-column text-right">
                                </p>
                            </div>

                            <div class="position-relative mb-4">
                                <img src="{{asset('py/Results/Seassion.png')}}" class="image_trend" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">Giải Thích:</h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>Opserved</td>
                                                <td>Dữ liệu CPI gốc, cho thấy CPI Việt Nam tăng đều theo thời gian.</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">2</th>
                                                <td>Trend</td>
                                                <td>CPI Việt Nam có khuynh hướng tuyến tính, tăng chậm theo thời gian.
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">3</th>
                                                <td>Seasonal</td>
                                                <td>CPI Việt Nam có xu hướng theo mùa, tăng vào đầu và cuối năm, giảm
                                                    thấp vào
                                                    giữa năm.</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">Nhận Xét:</h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>CPI Việt Nam tăng đều theo các năm, tuy nhiên tăng ở mức cho phép và
                                                    có kiểm soát nên rất có lợi cho doanh nghiệp.</td>
                                            </tr>
                                            <tr>
                                                <td>Lưu ý hoạt động sản xuất phù hợp với giao động theo mùa của CPI:
                                                    tăng nhẹ vào cuối năm và đầu năm, giảm vào giữa năm.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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

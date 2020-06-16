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
        @if(!file_exists('C:/xampp/htdocs/DSS-CPI-Predict/public/py/Results/saiso.text') || $data == null)
        <h3>Chưa có dữ liệu! Nhấn <a href="{{route('data')}}">vào đây!</a></h3>


        @else
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="{{route('dashboard')}}">Kết Quả</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('trend')}}">Xu Hướng<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('suggest')}}">Gợi Ý</a>
                    </li>

                </ul>
            </div>
        </nav>





        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card">

                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Biểu đồ dự đoán CPI Việt Nam</h3>
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
                                <canvas id="visitors-chart" height="400" data-root="{{$data}}"
                                    data-predict="{{$data_predict}}" data-month="{{json_encode($month)}}"></canvas>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                                <span class="mr-2">
                                    <i class="fas fa-square text-primary"></i> CPI thực tế
                                </span>

                                <span>
                                    <i class="fas fa-square text-danger"></i> CPI dự đoán
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="card">

                        <div class="card-body">
                            <label>Sai Số:</label>
                            <br>
                            <?php
          $read = file('C:/xampp/htdocs/DSS-CPI-Predict/public/py/Results/saiso.text');
          foreach ($read as $line) {
            echo "". $line ."<br>";
          }
          ?>
                            <hr>
                            <label>CPI 12 tháng tới:</label>
                            <br>
                            @foreach($month_end as $item )
                            {{$item['date_time']}} : {{$item['value']}}
                            <br>
                            @endforeach
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="{{asset('adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

<script src="{{asset('vendor/predict.js')}}"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="{{asset('adminlte/plugins/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('adminlte/dist/js/demo.js')}}"></script>
<script src="{{asset('adminlte/dist/js/pages/dashboard3.js')}}"></script>
@endsection

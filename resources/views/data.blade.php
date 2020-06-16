@extends('admin_layout')
@section('title')
<title>Data</title>
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    @include('general.content-header',['name' => 'Data'])
    <!-- /.content-header -->

    <section class="content">

        <div class="card">
            <div class="card-header">
                <form action="{{route('postdata')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" accept=".csv" class="form-controle-file ">
                    @if(session('thatbai'))
                    <li class="sub_error" style="color: red;">
                        {{session('thatbai')}}
                    </li>
                    @endif
                    <button class="btn btn-sm btn-primary">Import New</button>
                    <button data-url="{{route('predict')}}" type="button"
                        class="btn btn-sm btn-primary float-right btn_predict">Predict Now</button>
                </form>
                <br>
                <h3 class="card-title">Data CPI:</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date Time</th>
                            <th>CPI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                        @if($loop -> index == 0)
                        @else
                        <tr>
                            <td>{{$loop -> index}}</td>
                            <td>{{$item -> date_time}}</td>
                            <td>{{$item -> cpi}}</td>

                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</div>
</section>
@endsection

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


@endsection




@section('js')
{{-- sweetalert2  --}}
<script src="vendor/sweetalert/sweetalert2@9.js"></script>
<!-- DataTables -->
<script src="{{asset('adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

<script src="{{asset('vendor/data.js')}}"></script>
@endsection

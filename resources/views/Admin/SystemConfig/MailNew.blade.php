@extends('Admin.Layouts.Master')
@section('Title', 'Mẫu mail | Cấu hình trang chủ')
@section('Style')
@endsection
@section('Content')
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1">
            @if($check_role == 1  ||key_exists(4, $check_role))

                <li class="add px-2 pt-1 check">
                    <a href="{{route('admin.templates.index')}}">
                        <i class="fa fa-th-list mr-1"></i>Danh sách
                    </a>
                </li>
            @endif
            @if($check_role == 1  ||key_exists(8, $check_role))

                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 pt-1 ml-1">
                    <a href="{{route('admin.system.mail.mail_trash')}}">
                        Thùng rác
                    </a>
                </li>
            @endif
            @if($check_role == 1  ||key_exists(1, $check_role))

                <li class="ml-2 phay">
                    /
                </li>
                <li class=" list-box px-2 pt-1 ml-1 active check">
                    <a href="{{route('admin.system.mail.new')}}">
                        <i class="fa fa-edit mr-1"></i>Thêm
                    </a>
                </li>
            @endif
        </ol>
    </div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4 class="m-0 text-bold text-center">THÊM MẪU MAIL</h4>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <form class="mb-3" action="{{route('admin.system.mail.new')}}" method="post">
                @csrf
                @include('Admin.SystemConfig.components._form')
            </form>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('Script')
    <script>
        @if(count($errors) > 0)
        toastr.error("Vui lòng kiểm tra các trường");
        @endif
    </script>
@endsection

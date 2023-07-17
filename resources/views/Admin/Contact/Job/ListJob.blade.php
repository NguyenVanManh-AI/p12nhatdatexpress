@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách nghề nghiệp | Quản lý liên hệ')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
    <style>
        /*Dropdown*/
        .dropdown-custom {
            background-color: #337ab7;
            border-color: #2e6da4;
            color: #fff;
        }

        .dropdown-custom:hover {
            color: white;
            background-color: #286090;
            border-color: #204d74;
        }

    </style>
@endsection
@section('Content')
    <!-- Breadcrumb -->
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1">
            @if($check_role == 1  ||key_exists(4, $check_role))
                <li class="list-box px-2 pt-1 active check">
                    <a href="{{route('admin.contact.job.list')}}">
                        <i class="fa fa-th-list mr-1"></i>Danh sách
                    </a>
                </li>
            @endif
            @if($check_role == 1  ||key_exists(8, $check_role))
                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 pt-1 ml-1">
                    <a href="{{route('admin.contact.job.trash')}}">
                        Thùng rác
                    </a>
                </li>
            @endif

        </ol>
    </div>
    <!-- ./Breadcrumb -->

    <!-- Filter -->

    <!-- ./Filter -->
    @if($check_role == 1  ||key_exists(1, $check_role))
        <div  id="add_job" class="col-md-12 col-lg-6">
            <form action="{{route('admin.contact.job.add')}}" method="POST">
                @csrf
                <label for=""> Thêm nghề nghiệp</label>
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <input class="form-control" type="text" name="job_name" value="{{old('job_name')}}" placeholder="Nghành nghề">
                        @if($errors->has('job_name'))
                            <small style="font-size: 100%;" class="text-danger">
                                {{$errors->first('job_name')}}
                            </small>
                        @endif
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <button type="submit" class="btn btn-primary"> Thêm </button>

                    </div>
                </div>
            </form>
        </div>
    @endif
    @if($check_role == 1  ||key_exists(2, $check_role))
        <div id="edit_job" class="col-md-12 col-lg-6"  style="display: none">
            <form action="{{ old('old_edit_url') ?? route('admin.contact.job.update', ['', '']) }}" id="post_edit" method="POST">
                @csrf
                <label for="">Sửa nghề nghiệp</label>
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <input class="form-control id_job" type="hidden"  name="id_job" value="{{old('id_job')}}">
                        <input class="form-control job_name" type="text"  name="job_name" value="{{old('job_name')}}" placeholder="Nghề nghiệp">
                        <input class="form-control" type="hidden" id="old_edit_url"  name="old_edit_url" value="{{old('old_edit_url')}}">
                        @if($errors->has('job_name'))
                            <small style="font-size: 100%;" class="text-danger">
                                {{$errors->first('job_name')}}
                            </small>
                        @endif
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <button type="submit" class="btn btn-primary"> Cập nhật </button>
                        <button type="button" class="btn btn-secondary remove_edit"> Hủy </button>
                    </div>
                </div>
            </form>
        </div>
    @endif
    <h4 class="text-center font-weight-bold mt-5 mb-4">DANH SÁCH NGHỀ NGHIỆP</h4>
    <!-- Main content -->
    <section class="content mb-2">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <!-- Filter -->
                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <form action="" method="GET">
                                <div class="form-group d-flex">
                                    <input type="text" class="form-control mr-3" name="keyword" placeholder="Nghề nghiệp" value="{{request('keyword')}}">
                                    <button class="btn btn-primary">Tìm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- //Filter -->

                    <div class="table-responsive">
                        <table class="table table-bordered text-center table-custom" id="table">
                            <thead>
                            <tr>
                                <th scope="row" class=" active" style="width: 3%">
                                    <input type="checkbox" class="select-all checkbox" name="select-all"/>
                                </th>
                                <th scope="col" style="width: 4%">STT</th>
                                <th scope="col" style="width: 28%">Ngành nghề</th>
                                <th scope="col" style="width: 14%">Người tạo</th>
                                {{-- <th scope="col" style="width: 11%">Trạng thái hiển thị</th> --}}
                                <th scope="col" style="width: 14%;">Cài đặt</th>
                            </tr>
                            </thead>
                            <tbody>
                            <form id="formtrash" action="{{route('admin.contact.job.trashlist')}}" method="POST">
                                @csrf
                                @foreach ( $list as $item )
                                    <tr>
                                        <td class="active">
                                            <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}"/>
                                            <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}" />
                                        </td>
                                        <td>{{$item->id}}</td>

                                        <td>
                                            <div class="d-flex align-items-center flex-column flex-fill">
                                                <span class="name-text">{{$item->param_name}} </span>
                                                <div class="review-box-main mt-3">

                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <span>{{$item->admin_fullname}}</span>
                                        </td>

                                        {{-- <td> @if ($item->is_show==0)
                                            <a href="javascript:{}" data-id="{{$item->id}}" class="btn btn-success changestatus ">Hiển thị</a>
                                            @else
                                            <a href="javascript:{}" data-id="{{$item->id}}" class="btn btn-danger changestatus">Ẩn</a>
                                        @endif

                                        </td> --}}
                                        <td>
                                            <div class="row flex-column justify-content-center pl-3">

                                                @if($check_role == 1  ||key_exists(2, $check_role))
                                                    <div class="text-left mb-2">
                                                        <i class="icon-setup fas fa-cog mr-2"></i>
                                                        <a href="javascript:{}" data-job_name ="{{$item->param_name}}"  data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}" class="text-primary edit_block">Chỉnh sửa</a>
                                                    </div>
                                                @endif
                                                @if($check_role == 1  ||key_exists(5, $check_role))
                                                    <div class="text-left mb-2">
                                                        <i class="icon-setup fas fa-times mr-2"></i>
                                                        <a href="{{route('admin.contact.job.delete', [$item->id, \Crypt::encryptString($item->created_by)])}}" class="delete" style="color:#ff0000;cursor: pointer;">Xóa</a>
                                                    </div>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex align-items-center justify-content-between my-4">
                        <div class="d-flex align-items-center">
                            <div class="d-flex">
                                <img src="image/manipulation.png" alt="" id="btnTop">
                                <div class="btn-group ml-1">
                                    <!-- data-flip="false" -->
                                    <button type="button" class="btn dropdown-toggle dropdown-custom"
                                            data-toggle="dropdown" aria-expanded="true" data-flip="false"
                                            aria-haspopup="true">
                                        Thao tác
                                    </button>
                                    <div class="dropdown-menu">
                                        @if($check_role == 1  ||key_exists(5, $check_role))
                                            <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">
                                                <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px"></i>Thùng rác
                                                <input type="hidden" name="action" value="trash">
                                            </a>
                                        @else
                                            <p class="dropdown-item m-0 disabled">
                                                Bạn không có quyền
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            </form>
                            <div class="d-flex align-items-center justify-content-between mx-4">
                                <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                                <form action="{{route('admin.block.list')}}" method="GET">
                                    <label class="select-custom2">
                                        <select id="paginateNumber" name="items" onchange="this.form.submit()">
                                            <option
                                                @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">
                                                10
                                            </option>
                                            <option
                                                @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">
                                                20
                                            </option>
                                            <option
                                                @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">
                                                30
                                            </option>
                                        </select>
                                    </label>
                                </form>
                            </div>
                            @if($check_role == 1  ||key_exists(8, $check_role))
                                <div class="d-flex flex-row align-items-center view-trash">
                                    <i class="far fa-trash-alt mr-2"></i>
                                    <div class="link-custom">

                                        <a href="{{route('admin.contact.job.trash')}}"><span style="color: #347ab6">Xem thùng rác</span>
                                            <span class="badge badge-pill badge-danger trashnum"
                                                  style="font-weight: 500">{{$count_trash}}</span>
                                        </a>

                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="count-item">Tổng cộng: {{$list->total()}} items</div>
                            @if($list)
                                {{ $list->render('Admin.Layouts.Pagination') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- /.content -->
@endsection

@section('Script')
    <script src="js/table.js"></script>
    <script>

        $(document).ready(function(){

            if("{{old('edit_job')}}"){
                $('#edit_job').attr('action', '').show();
                $('#add_job').hide();
            }

        });
        $('.edit_block').click(function () {
            key = $(this).data('job_name');
            id = $(this).data('id');
            created_by = $(this).data('created_by')
            $('#post_edit .job_name').val(key);
            url = "{{route('admin.contact.job.update',['',''])}}/" + id + '/' + created_by;
            $('#post_edit').attr('action', url);
            $('#old_edit_url').val(url)

            $('#add_job').hide();
            $('#edit_job').show();
        });

        $('.remove_edit').click(function(){
            $('#add_job').show();
            $('#edit_job').hide();
        });

    </script>
    <script>
        $('.delete').click(function (e) {
            e.preventDefault()
            Swal.fire({
                title: 'Xác nhận xóa',
                text: "Sau khi xóa sẽ chuyển vào thùng rác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = $(this).attr('href');
                }
            });
        });

        $('.moveToTrash').click(function () {
            // var id = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận xóa',
                text: "Sau khi xóa sẽ chuyển vào thùng rác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#formtrash').submit();
                }
            });
        });
    </script>
@endsection

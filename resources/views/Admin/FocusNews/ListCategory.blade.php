@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý danh mục | Tiêu điểm')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')

    <div class="row m-0 p-3">
        <ol class="breadcrumb">
            @if($check_role == 1  ||key_exists(4, $check_role))
                <li class="list-box px-2 pt-1">
                    <a href="{{route('admin.focuscategory.list')}}">
                        <i class="fa fa-th-list mr-1"></i>Danh sách
                    </a>
                </li>
            @endif
            @if($check_role == 1  ||key_exists(8, $check_role))
                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 pt-1 ml-1">
                    <a href="{{route('admin.focuscategory.listtrash')}}">
                        Thùng rác
                    </a>
                </li>
            @endif
            @if($check_role == 1  ||key_exists(1, $check_role))
                <li class="ml-2 phay">
                    /
                </li>
                <li class="add px-2 pt-1 ml-1">
                    <a href="{{route('admin.focuscategory.new')}}">
                        <i class="fa fa-edit mr-1"></i>Thêm
                    </a>
                </li>
            @endif
        </ol>
    </div>
    <h4 class="text-center text-bold ">DANH SÁCH DANH MỤC</h4>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <form action="{{route('admin.listcategory.trashlist')}}" id="formtrash" method="post">
                            <input type="hidden" name="action" id="action_list" value="">
                            @csrf
                            <table class="table table-bordered text-center table-custom" id="table">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 3%"><input type="checkbox" class="select-all"></th>
                                    <th scope="col" style="width: 4%">ID</th>
                                    <th scope="col" style="width: 28%">Tiêu đề</th>
                                    <th scope="col" style="width: 28%">Đường dẫn tĩnh</th>
                                    <th scope="col" style="width: 13%">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($group as $item )
                                    <tr>
                                        <td class="active">
                                            <input type="checkbox" class="select-item" value="{{$item->id}}"
                                                   name="select_item[]">
                                            <input type="hidden" class="select-item"
                                                   value="{{Crypt::encryptString($item->created_by)}}"
                                                   name="select_item_created[{{$item->id}}]">
                                            @if(!isset($item->child))
                                                <input type="hidden" class="select-item" value="{{($item->id)}}"
                                                       name=" parent[{{$item->id}}]">
                                            @else
                                                <input type="hidden" class="select-item" value="{{($item->id)}}"
                                                       name=" child[{{$item->id}}]">
                                            @endif
                                        </td>
                                        <td>{{$item->id}}</td>
                                        <td class="text-left pl-5">
                                            @if($item->parent_id ==47)
                                                {{$item->group_name}}
                                            @else
                                                {{"---- ".$item->group_name}}
                                            @endif
                                        </td>
                                        <td class="">
                                            <span>{{$item->group_url}}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="float-left ml-2">
                                                    <i class="fas fa-cog mr-2"></i>
                                                    <a href="{{route('admin.focuscategory.edit',[$item->id,\Crypt::encryptString($item->created_by)])}}"
                                                       class="text-primary ">Chỉnh sửa</a>
                                                </div>
                                                <br>

                                                <div class="float-left ml10">
                                                    <i class="fas fa-times mr12"></i>
                                                    <a class="setting-item delete text-danger action_delete cusor-point"
                                                       data-id="{{$item->id}}"
                                                       data-created_by="{{\Crypt::encryptString($item->created_by)}}">Xóa</a>
                                                </div>
                                                <div class="clear-both"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
                        <div class="text-left d-flex align-items-center">
                            <div class="manipulation d-flex mr-4 ">
                                <img src="image/manipulation.png" alt="" id="btnTop">
                                <div class="btn-group ml-1">
                                    <button type="button" class="btn dropdown-toggle dropdown-custom"
                                            data-toggle="dropdown"
                                            aria-expanded="false" data-flip="false" aria-haspopup="true">
                                        Thao tác
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">
                                            <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"
                                               style="color: white !important;font-size: 15px"></i>Thùng rác
                                            <input type="hidden" name="action" value="trash">
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="display d-flex align-items-center mr-4">
                                <span>Hiển thị:</span>
                                <form method="get" action="{{route('admin.focuscategory.list')}}">
                                    <select class="custom-select" id="paginateNumber" name="items"
                                            onchange="this.form.submit()">
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
                                </form>
                            </div>
                            <div class="view-trash">
                                <a href="{{route('admin.focuscategory.listtrash')}}"><i class="far fa-trash-alt"></i>
                                    Xem thùng rác</a>
                                <span class="count-trash">
                                    @if(isset($count_trash))
                                        {{$count_trash}}
                                    @endif
                                </span>
                            </div>


                        </div>
                        <div class="d-flex align-items-center">
                            <div class="count-item">Tổng cộng: @empty($group) {{0}} @else {{$group->total()}} @endempty
                                items
                            </div>
                            @if($group)
                                {{ $group->render('Admin.Layouts.Pagination') }}
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
    <script type="text/javascript">
        $('#tieudiem').addClass('active');
        $('#quanlydanhmuc').addClass('active');
        $('#nav-tieudiem').addClass('menu-is-opening menu-open');
    </script>

    <script src="js/table.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <script>
        @if(count($errors) > 0)
        toastr.error("Vui lòng kiểm tra các trường");
        @endif
    </script>

    <script>
        $('.delete').click(function () {
            var id = $(this).data('id');
            var created_by = $(this).data('created_by');
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
                    window.location.href = "/admin/focus-news-category/delete/" + id + "/" + created_by;
                }
            });
        });
        $('.moveToTrash').click(function () {
            var id = $(this).data('id');
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
                    $('#action_list').val("trash");
                    $('#formtrash').submit();

                }
            });
        });
    </script>

@endsection

@extends('Admin.Layouts.Master')
@section('Title', 'Thùng rác | Chặn cấm')
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
<div class="row m-0 p-3">
    <ol class="breadcrumb mt-1">
        @if($check_role == 1  ||key_exists(4, $check_role))
        <li class="list-box px-2 pt-1 active check">
            <a href="{{route('admin.block.list')}}">
                <i class="fa fa-th-list mr-1"></i>Danh sách
            </a>
        </li>
        @endif
    </ol>
</div>
<section class="content mb-2">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered text-center table-custom" id="table">
                        <thead>
                            <tr>
                                <th scope="row" class=" active" style="width: 3%">
                                    <input type="checkbox" class="select-all checkbox" name="select-all"/>
                                </th>
                                <th scope="col" style="width: 4%">STT</th>
                                <th scope="col" style="width: 14%">Từ cấm</th>
                                <th scope="col" style="width: 28%">Người tạo</th>
                                <th scope="col" style="width: 14%">Ngày tạo</th>
                                {{-- <th scope="col" style="width: 11%">Trạng thái hiển thị</th> --}}
                                <th scope="col" style="width: 22%;">Cài đặt</th>
                            </tr>
                            </thead>
                        <tbody>
                            <form id="formtrash" action="{{route('admin.block.untrashlist')}}" method="POST">
                                @csrf
                                <input type="hidden" name="action" id="formaction" value="">
                            @foreach ( $trash_block as $item )


                            <tr>
                                <td class="active">
                                    <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}"/>
                                    <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}" />

                                </td>
                                <td>{{$item->id}}</td>
                                <td>
                                    <div class="d-flex align-items-center flex-column flex-fill">
                                        <span class="name-text">{{$item->forbidden_word}} </span>
                                        <div class="review-box-main mt-3">

                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span>{{$item->admin_fullname}}</span>
                                </td>

                                <td>
                                    <span>{{\Carbon\Carbon::parse($item->created_at)->format('d/m/Y')}}</span>
                                </td>

                                <td>
                                    <div class="row flex-column justify-content-center pl-3">
                                        @if($check_role == 1  ||key_exists(7, $check_role))
                                        <div class="text-left mb-2">
                                            <i class=" icon-setup fas fa-times"></i>
                                            <a href="javascript:{}" class="deletealways" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}" style="color:#ff0000">Xóa</a>
                                        </div>
                                        @endif
                                        @if($check_role == 1  ||key_exists(6, $check_role))
                                        <div class="text-left mb-2">
                                            <i class="icon-setup fas fa-undo-alt text-black"></i>
                                            <a href="javascript:{}" class="setting-item delete text-primary mb-2" style="cursor: pointer" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">
                                                Khôi phục</a>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </form>
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
                                    {{-- <a class="dropdown-item updateShowOrder "  type="button" href="javascript:{}">
                                        <i class="fas fa-pencil-alt bg-orange p-1 mr-2 rounded"
                                           style="color: white !important;font-size: 15px"></i> Cập nhật
                                    </a> --}}
                                    @if($check_role == 1  ||key_exists(6, $check_role)||key_exists(7, $check_role))
                                      @if($check_role == 1  ||key_exists(6, $check_role))
                                    <a class="dropdown-item unToTrash" type="button" href="javascript:{}">
                                        <i class="fas fa-undo-alt bg-primary     p-1 mr-2 rounded"
                                           style="color: white !important;font-size: 15px"></i>Khôi phục
                                           {{-- <input type="hidden" name="action" value="restore"> --}}
                                    </a>
                                        @endif
                                      @if($check_role == 1  ||key_exists(7, $check_role))
                                      <a class="dropdown-item delete_list" type="button" href="javascript:{}">
                                        <i class="fas fas fa-times bg-red     p-1 mr-2 rounded"
                                           style="color: white !important;font-size: 18px"></i>Xóa
                                           {{-- <input type="hidden" name="action" value="delete"> --}}
                                    </a>
                                      @endif
                                    @else
                                    <p class="dropdown-item m-0 disabled">
                                        Bạn không có quyền
                                    </p>
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mx-4">
                            <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                            <form action="{{route('admin.block.trash')}}" method="GET">
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
                        <div>
                            @if($check_role == 1  ||key_exists(4, $check_role))
                            <a href="{{route('admin.block.list')}}" class="btn btn-primary">
                                Quay lại
                            </a>
                            @endif
                        </div>

                    </div>
                    <div class="d-flex align-items-center">
                        <div class="count-item">Tổng cộng: {{$trash_block->total()}} items</div>
                        <div>
                            @if($trash_block)
                     {{ $trash_block->render('Admin.Layouts.Pagination') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('Script')
    <script src="js/table.js"></script>
    <script type="text/javascript">
        $('#huongdan').addClass('active');
        $('#nav-huongdan').addClass('menu-is-opening menu-open');
    </script>

     <script>
        $('.delete').click(function () {
            var id = $(this).data('id');
            var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Xác nhận khôi phục',
                text: "Nhấn đồng ý thì sẽ tiến hành khôi phục!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/block/untrash-block/" + id+"/"+created_by;
                }
            });
        });
        $('.unToTrash').click(function () {
            Swal.fire({
                title: 'Xác nhận khôi phục',
                text: "Nhấn đồng ý thì sẽ tiến hành khôi phục !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#formaction').val('restore');
                    // alert($('#formaction').val('restore'));
                    $('#formtrash').submit();

                }
            });
        });
        $('.delete_list').click(function () {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: "Sau khi xóa thì không thể khôi phục",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#formaction').val('delete');
                    $('#formtrash').submit();

                }
            });
        });
        $('.deletealways').click(function () {
        var id = $(this).data('id');
        var created_by = $(this).data('created_by');
        Swal.fire({
            title: 'Xác nhận xóa',
            text: "Sau khi xóa sẽ không thể khôi phục",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/admin/block/deletealways/" + id+"/"+created_by;
            }
        });
    });
     </script>
@endsection

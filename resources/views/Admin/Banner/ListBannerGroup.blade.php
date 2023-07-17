@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý nhóm banner')
@section('Content')
    <!-- Content Header (Page header) -->
{{--    <div class="row m-0 p-3">--}}
{{--        <ol class="breadcrumb mt-1 align-items-center">--}}
{{--            <li class="recye px-2 p-1 check active">--}}
{{--                <a href="javascript:{}">--}}
{{--                    <i class="fa fa-th-list mr-1"></i>Danh sách--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            @if($check_role == 1  ||key_exists(5, $check_role))--}}
{{--                <li class="phay ml-2">--}}
{{--                    /--}}
{{--                </li>--}}
{{--                <li class="recye px-2 ml-1">--}}
{{--                    <a href="{{route('admin.banner.locate.trash')}}">--}}
{{--                        <i class="far fa-trash-alt mr-1"></i>Thùng rác--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endif--}}
{{--            @if($check_role == 1  ||key_exists(1, $check_role))--}}
{{--                <li class="ml-2 phay">--}}
{{--                    /--}}
{{--                </li>--}}
{{--                <li class="add px-2 ml-1 p-1">--}}
{{--                    <a href="{{route('admin.banner.locate.add')}}">--}}
{{--                        <i class="fa fa-edit mr-1"></i>Thêm--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endif--}}
{{--        </ol>--}}
{{--    </div>--}}
    <!-- ./Breakcum -->
    <!-- Filter -->
    <div class="container-fluid p-3">
        <form action="" method="GET" id="filterForm">
            <div class="row">
                <div class="col-md-12 col-lg-3">
                    <input class="form-group form-control" type="text" name="keyword"  placeholder="Từ khóa" value="{{request()->query('keyword') ?? ""}}">
                </div>

                <div class="col-md-12 col-lg-3">
                    <select class="form-group form-control select2" style="width: 100%;height: 34px !important;" name="banner_group">
                        <option selected="selected" value="" >Nhóm banner</option>
                        <option @if(request()->query('banner_group') == 'H') {{ 'selected' }} @endif value="H" >Trang chủ</option>
                        <option @if(request()->query('banner_group') == 'C') {{ 'selected' }} @endif value="C" >Trang chuyên mục</option>
                        <option @if(request()->query('banner_group') == 'O') {{ 'selected' }} @endif value="O" >Khác</option>
                    </select>
                </div>
                <div class="col-md-12 col-lg-3">
                    <select class="form-group form-control select2" style="width: 100%;height: 34px !important;" name="banner_type">
                        <option selected="selected" value="" >Thiết bị</option>
                        <option @if(request()->query('banner_type') == 'D') {{ 'selected' }} @endif value="D" >Desktop</option>
                        <option @if(request()->query('banner_type') == 'M') {{ 'selected' }} @endif value="M" >Mobile</option>
                    </select>
                </div>
                <div class="col-md-12 col-lg-3">
                    <select class="form-group form-control select2" style="width: 100%;height: 34px !important;" name="banner_position">
                        <option selected="selected" value="" >Vị trí</option>
                        <option @if(request()->query('banner_position') == 'T') {{ 'selected' }} @endif value="T" >Trên</option>
                        <option @if(request()->query('banner_position') == 'B') {{ 'selected' }} @endif value="B" >Dưới</option>
                        <option @if(request()->query('banner_position') == 'L') {{ 'selected' }} @endif value="L" >Trái</option>
                        <option @if(request()->query('banner_position') == 'R') {{ 'selected' }} @endif value="R" >Phải</option>
                        <option @if(request()->query('banner_position') == 'C') {{ 'selected' }} @endif value="C" >Giữa</option>
                    </select>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-3">
                    <button class="search-button btn btn-primary w-100" style="height: 38px;line-height: 16px"><i class="fa fa-search mr-2" aria-hidden="true"></i>Tìm kiếm
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- ./Filter -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12 text-center">
                    <h4 class="m-0 font-weight-bold">QUẢN LÝ NHÓM BANNER</h4>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center table-hover table-custom " id="table">
                            <thead>
                            <tr>
                                <th scope="row" class="active">
{{--                                    <input type="checkbox" class="select-all checkbox" name="select-all" />--}}
                                </th>
                                <th scope="col">STT</th>
                                <th scope="col" width="25%">Tên banner</th>
                                <th scope="col">Vị trí banner</th>
                                <th scope="col">Size (width x height)</th>
                                <th scope="col">Giá tiền</th>
                                <th scope="col">Giá Coin</th>
                                <th scope="col">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            <form action="{{route('admin.banner.locate.action')}}" id="formAction" method="post">
                                @csrf
                                @forelse($list as $item)
                                    <tr>
                                        <td class=" active">
                                            <input type="checkbox"  value="{{$item->id}}" data-name="{{$item->banner_name}}" class="select-item checkbox" name="select_item[]" />
                                            <input type="text" hidden name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}">
                                        </td>
                                        <td class="id_role">{{$loop->index + 1}}</td>
                                        <td>{{$item->banner_name}}</td>
                                        <td class="font-italic">
                                            <p class="m-0 pb-1">
                                                {{$item->banner_group_name}}
                                            </p>
                                            <span>
                                                @if($item->banner_type == 'D')
                                                    Desktop
                                                @elseif($item->banner_type == 'M')
                                                    Mobile
                                                @endif
                                                -
                                                @if($item->banner_position == 'T')
                                                        Trên
                                                    @elseif($item->banner_position == 'B')
                                                        Dưới
                                                    @elseif($item->banner_position == 'L')
                                                        Trái
                                                    @elseif($item->banner_position == 'R')
                                                        Phải
                                                    @elseif($item->banner_position == 'C')
                                                        Giữa
                                                    @endif
                                            </span>
                                        </td>
                                        <td>
                                            <p>{{$item->banner_width ?? 'auto'}} x {{$item->banner_height ?? 'auto'}}</p>
                                            <label class=" mr-2"> <!-- form-control-409 -->
                                                <input type="checkbox" class="checkbox checkbox-select" value="1" id="banner_permission_{{$item->id}}" name="banner_permission[{{$item->id}}]" {{  $item->banner_permission == 1 ? "checked" : ""}}>
                                            </label>
                                            <label class="font-weight-normal" for="banner_permission_{{$item->id}}">Cho phép người dùng quảng cáo</label>
                                        </td>
                                        <td>
                                            <span style="font-weight: 550">{{\App\Helpers\Helper::format_money($item->banner_price)}}</span>
                                            <p class="m-0 pt-1">VNĐ</p>
                                        </td>
                                        <td>
                                            <span style="font-weight: 550">{{\App\Helpers\Helper::format_money($item->banner_coin_price)}}</span>
                                            <p class="m-0 pt-1">Coin</p>
                                        </td>
                                        <td class="text-left">
                                            <div class="table_action">
                                                @if($check_role == 1 || key_exists(2, $check_role))
                                                    <div class="ml-3 mb-2"><i class="fas fa-cog mr-2"></i>
                                                        <a href="{{route('admin.banner.locate.edit',[$item->id, \Crypt::encryptString($item->created_by)])}}" class="text-primary">Chỉnh sửa</a>
                                                    </div>
                                                @endif
{{--                                                @if($check_role == 1 || key_exists(5, $check_role))--}}
{{--                                                    <div class="ml-3"><i class="fas fa-times mr-2" style="padding-right: 6px"></i>--}}
{{--                                                        <a href="javascript:{}" class="text-danger action_delete" data-id="{{$item->id}}" data-created="{{\Crypt::encryptString($item->created_by)}}">--}}
{{--                                                            Xóa--}}
{{--                                                        </a>--}}
{{--                                                    </div>--}}
{{--                                                @endif--}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <td colspan="8">Chưa có dữ liệu</td>
                                @endforelse
                            </form>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex align-items-center justify-content-between my-4">
                        <div class="d-flex align-items-center">
                            <div class="d-flex">
                                <img src="image/manipulation.png" alt="" id="btnTop">
                                <div class="btn-group ml-1">
                                    <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown" aria-expanded="false" data-flip="false" aria-haspopup="true">
                                        Thao tác
                                    </button>
                                    <div class="dropdown-menu">
                                        @if( $check_role == 1 || key_exists(2, $check_role))
                                            <a class="dropdown-item updatePermission" type="button" href="javascript:{}">
                                                <i class="fas fa-pencil-alt bg-orange p-1 mr-2 rounded" style="color: white !important;font-size: 15px"></i> Cập nhật
                                            </a>
                                        @endif
                                        @if(is_array($check_role) && !key_exists(2, $check_role) && !key_exists(5, $check_role))
                                            <p class="dropdown-item m-0 disabled">
                                                Bạn không có quyền
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                </label>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mx-4">
                                <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                                <label class="select-custom2">
                                    <select id="paginateNumber" name="items" onchange="submitPaginate(event)">
                                        <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
                                        <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
                                        <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
                                    </select>
                                </label>
                            </div>
{{--                            @if($check_role == 1 || key_exists(8, $check_role))--}}
{{--                                <div class="d-flex flex-row align-items-center view-trash">--}}
{{--                                    <i class="far fa-trash-alt mr-2"></i>--}}
{{--                                    <div class="link-custom">--}}
{{--                                        <a href="{{route('admin.banner.locate.trash')}}"><span style="color: #347ab6">Xem thùng rác</span>--}}
{{--                                            <span class="badge badge-pill badge-danger trashnum" style="font-weight: 500">{{$trash_num}}</span>--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endif--}}
                        </div>
                        <div class="d-flex align-items-center" >
                            <div class="count-item" >Tổng cộng: @empty($list) {{0}} @else {{$list->total()}} @endempty items</div>
                            <div class="count-item count-item-reponsive" style="display: none">@empty($list) {{0}} @else {{$list->total()}} @endempty items</div>
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
    <script src="js/table.js" type="text/javascript"></script>
    <script>
        {{--function deleteItem(id, created)--}}
        {{--{--}}
        {{--    Swal.fire({--}}
        {{--        title: 'Xác nhận xóa',--}}
        {{--        text: "Sau khi xóa sẽ chuyển vào thùng rác!",--}}
        {{--        icon: 'warning',--}}
        {{--        showCancelButton: true,--}}
        {{--        confirmButtonColor: '#d33',--}}
        {{--        cancelButtonColor: '#3085d6',--}}
        {{--        cancelButtonText: 'Quay lại',--}}
        {{--        confirmButtonText: 'Đồng ý'--}}
        {{--    }).then((result) => {--}}
        {{--        if (result.isConfirmed) {--}}
        {{--            window.location.href= `{{route('admin.banner.locate.delete', ['',''])}}/${id}/${created}`;--}}
        {{--        }--}}
        {{--    })--}}
        {{--}--}}

        jQuery(document).ready(function ($) {
            $(function () {
                // update permission
                $('.dropdown-item.updatePermission').click(function () {
                    const selectedArray = getSelected();
                    if (selectedArray)
                        $('#formAction').attr('action', $('#formAction').attr('action') + '?action=update').submit();
                })
                // date filter
                hiddenInputTextDate('#txtDateStart')
                hiddenInputTextDate('#txtDateEnd')
                setMinMaxDate('.date_start', '.date_end')
                // remove click
                // $('.action_delete').click(function () {
                //     deleteItem($(this).data('id'), $(this).data('created'))
                // })

                // move to trash
                // $('.dropdown-item.moveToTrash').click(function () {
                //     const selectedArray = getSelected();
                //     Swal.fire({
                //         title: 'Xác nhận xóa',
                //         text: "Sau khi xóa sẽ chuyển vào thùng rác!",
                //         icon: 'warning',
                //         showCancelButton: true,
                //         confirmButtonColor: '#d33',
                //         cancelButtonColor: '#3085d6',
                //         cancelButtonText: 'Quay lại',
                //         confirmButtonText: 'Đồng ý'
                //     }).then((result) => {
                //         if (result.isConfirmed) {
                //             if (selectedArray)
                //                 $('#formAction').attr('action', $('#formAction').attr('action') + '?action=trash').submit();
                //         }
                //     });
                // })

                $("#filterForm").on("submit",function(e){
                    $(this).append('<input type="hidden" name="items" value="'+ $('#paginateNumber').val() + '" /> ');
                });
            })
        })
        function hideTextDateStart(){
            $('#txtDateStart').hide();
        }
        function hideTextDateEnd(){
            $('#txtDateEnd').hide();
        }
        function submitPaginate(event){
            const uri = window.location.toString();
            const exist = uri.indexOf('?')
            const existItems = uri.indexOf('?items')
            const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
            exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() : window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
        }
    </script>
    @if(count($errors) > 0)
        @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
@endsection

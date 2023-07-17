@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý trang tĩnh | Trang tĩnh')
@section('Content')
    <!-- Content Header (Page header) -->
    <div class="row m-0 px-3 pt-3">
        <ol class="breadcrumb mt-1">
            <li class="recye px-2 p-1">
                <a href="{{route('admin.banner.list')}}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
        </ol>
    </div>
    <!-- ./Breakcum -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12 text-center">
                    <h4 class="m-0 font-weight-bold">QUẢN LÝ THÙNG RÁC BANNER</h4>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form action="" method="GET" id="filterForm">
                        <div class="form-group select2-custom">
                            <label for="page_content">Vị trí <span class="font-weight-bold text-danger">*</span></label>
                            <select class="form-control select2" style="width: 100%;height: 34px !important;" name="banner_group_id" data-placeholder="Vị trí"
                                    onchange="$(this).parents('#filterForm').submit()">
                                @foreach($group_banner as $item)
                                    <option @if(request()->query('banner_group_id') == $item->id) {{ 'selected' }} @endif value="{{$item->id}}">
                                        {{$item->banner_name}} ({{$item->banner_width ?? 'auto'}} x {{$item->banner_height ?? 'auto'}})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center table-hover table-custom " id="table">
                            <thead>
                            <tr>
                                <th scope="row" class="active">
                                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                                </th>
                                <th scope="col">ID</th>
                                <th scope="col" width="15%">Hình ảnh</th>
                                <th scope="col" width="30%">Tiêu đề</th>
                                <th scope="col">Chuyên mục</th>
                                <th scope="col">Thông tin</th>
                                <th scope="col">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            <form action="{{route('admin.banner.action')}}" id="formAction" method="post">
                                @csrf
                                @forelse($list as $item)
                                    <tr>
                                        <td class=" active">
                                            <input type="checkbox"  value="{{$item->id}}" data-name="{{$item->banner_title}}" class="select-item checkbox" name="select_item[]" />
                                            <input type="text" hidden name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}">
                                        </td>
                                        <td class="id_role">{{$item->id}}</td>
                                        <td>
                                            @if($item->image_url)
                                                <a data-fancybox="image_{{$item->id}}" data-src="{{strpos($item->image_url, 'http') === 0 ? $item->image_url : asset($item->image_url)}}">
                                                    <img src="{{strpos($item->image_url, 'http') === 0 ? $item->image_url : asset($item->image_url)}}" width="200px" height="200px" style="object-fit: contain" />
                                                </a>
                                            @endif
                                        </td>
                                        <td class="title_role text-wrap" style="word-break: break-word">{{$item->banner_title}}</td>
                                        <td>{{$item->group_name}}</td>
                                        <td>
                                            <p><span class="text-gray mr-2">Ngày tạo:</span>{{date('d/m/Y H:i:s',$item->created_at)}}</p>
                                            <p><span class="text-gray mr-2">Cập nhật:</span>{{date('d/m/Y H:i:s',$item->updated_at ?? $item->created_at)}}</p>
                                            @if($item->date_from)
                                                <hr>
                                                <p><span class="text-gray mr-2">Ngày bắt đầu:</span>{{date('d/m/Y H:i:s',$item->date_from)}}</p>
                                                <p><span class="text-gray mr-2">Ngày kết thúc:</span>{{date('d/m/Y H:i:s',$item->date_to)}}</p>
                                            @endif
                                        </td>
                                        <td class="text-left">
                                            <div class="table_action">
                                                @if( $check_role == 1 || key_exists(6, $check_role))
                                                    <div class="mb-2">
                                                        <i class="fas fa-undo-alt mr-2"></i>
                                                        <a href="javascript:{}" class="text-primary action_restore" data-c="{{$item->created_by}}" data-id="{{$item->id}}" data-created="{{\Crypt::encryptString($item->created_by)}}">Khôi phục</a>
                                                    </div>
                                                @endif
                                                <x-admin.force-delete-button
                                                    :check-role="$check_role"
                                                    id="{{ $item->id }}"
                                                    url="{{ route('admin.banner.force-delete-multiple') }}"
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">Chưa có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </form>
                            </tbody>
                        </table>
                    </div>

                    <form action="" class="force-delete-item-form d-none" method="POST">
                        @csrf
                        <input type="hidden" name="ids">
                    </form>

                    <div class="d-flex align-items-center justify-content-between my-4">
                        <div class="d-flex align-items-center">
                            <div class="d-flex">
                                <img src="image/manipulation.png" alt="" id="btnTop">
                                <div class="btn-group ml-1">
                                    <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown" aria-expanded="false" data-flip="false" aria-haspopup="true">
                                        Thao tác
                                    </button>
                                    <div class="dropdown-menu">
                                        @if($check_role == 1 || key_exists(6, $check_role))
                                            <a class="dropdown-item restoreItem" type="button" href="javascript:{}">
                                                <i class="fas fa-undo-alt bg-blue p-1 mr-2 rounded text-center" style="color: white !important;font-size: 13px; width: 23px"></i>Khôi phục
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
                            <div>
                                <a class="btn btn-primary" type="button" href="{{route('admin.banner.list')}}">Quay lại</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="count-item">Tổng cộng: @empty($list) {{0}} @else {{$list->total()}} @endempty items</div>
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
        function restoreItem(id, created)
        {
            Swal.fire({
                title: 'Xác nhận khôi phục!',
                text: `Sau khi khôi phục sẽ được chuyển sang danh sách`,
                icon: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-success btn-lg mr-2',
                    cancelButton: 'btn btn-outline-secondary btn-lg'
                },
                confirmButtonText: 'Khôi phục',
                cancelButtonText: 'Hủy',
            }).then((e) => {
                if (e.value) {
                    window.location.href= `{{route('admin.banner.restore', ['',''])}}/${id}/${created}`;
                } else {
                    e.dismiss;
                }
            })
        }
        jQuery(document).ready(function ($) {
            $(function () {
                // remove click
                $('.action_delete').click(function () {
                    deleteItem($(this).data('id'), $(this).data('created'))
                })
                // restore click
                $('.action_restore').click(function () {
                    restoreItem($(this).data('id'), $(this).data('created'))
                })
                // move to trash
                $('.dropdown-item.moveToTrash').click(function () {
                    const selectedArray = getSelected();
                    if (!selectedArray) return;
                    Swal.fire({
                        title: 'Xác nhận xóa',
                        text: "Sau khi xóa sẽ không thể khôi phục!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        cancelButtonText: 'Quay lại',
                        confirmButtonText: 'Đồng ý'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (selectedArray)
                                $('#formAction').attr('action', $('#formAction').attr('action') + '?action=delete').submit();
                        }
                    });
                })

                // restote
                $('.dropdown-item.restoreItem').click(function () {
                    const selectedArray = getSelected();
                    if (!selectedArray) return;
                    Swal.fire({
                        title: 'Xác nhận khôi phục!',
                        text: `Sau khi khôi phục sẽ được chuyển sang danh sách`,
                        icon: 'warning',
                        showCancelButton: true,
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-success btn-lg mr-2',
                            cancelButton: 'btn btn-outline-secondary btn-lg'
                        },
                        confirmButtonText: 'Khôi phục',
                        cancelButtonText: 'Hủy',
                    }).then((e) => {
                        if (e.value) {
                            if (selectedArray)
                                $('#formAction').attr('action', $('#formAction').attr('action') + '?action=restore').submit();
                        } else {
                            e.dismiss;
                        }
                    })
                })

                if($('#txtDateStart').siblings("input").val()) $('#txtDateStart').hide()
                if($('#txtDateEnd').siblings("input").val()) $('#txtDateEnd').hide()

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

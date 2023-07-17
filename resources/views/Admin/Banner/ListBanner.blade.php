@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách | Banner')
@section('Content')
    <!-- Content Header (Page header) -->
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1 align-items-center">
            <li class="recye px-2 p-1 check active">
                <a href="javascript:{}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
            @if($check_role == 1  ||key_exists(5, $check_role))
                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 ml-1">
                    <a href="{{route('admin.banner.trash')}}">
                        <i class="far fa-trash-alt mr-1"></i>Thùng rác
                    </a>
                </li>
            @endif
            @if($check_role == 1  ||key_exists(1, $check_role))
                <li class="ml-2 phay">
                    /
                </li>
                <li class="add px-2 ml-1 p-1">
                    <a href="{{route('admin.banner.add')}}">
                        <i class="fa fa-edit mr-1"></i>Thêm
                    </a>
                </li>
            @endif
        </ol>
    </div>
    <!-- ./Breakcum -->
    <!-- Filter -->
{{--    <div class="container-fluid p-3">--}}
{{--        <form action="" method="GET" id="filterForm">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-12 col-lg-3">--}}
{{--                    <input class="form-group form-control" type="text" name="keyword"  placeholder="Từ khóa" value="{{request()->query('keyword') ?? ""}}">--}}
{{--                </div>--}}
{{--                @php--}}
{{--                    $created_at = request()->get('created_at');--}}
{{--                    if ($created_at){--}}
{{--                        $date_start = $created_at['date_start'] ?? "";--}}
{{--                        $date_end = $created_at['date_end'] ?? "";--}}
{{--                    }--}}
{{--                @endphp--}}
{{--                <div onclick="hideTextDateStart()" class="col-md-12 col-lg-3 form-group">--}}
{{--                    --}}{{--                    <input class="form-control" name="created_at[date_start]" type="date" placeholder="Từ ngày" value="{{ $date_start ?? ""}}" />--}}
{{--                    <div style="position: relative">--}}
{{--                        <div id="txtDateStart" style="width:200px; height: 22px;background: #fff;position: absolute;top: 9px;left: 9px">Từ ngày</div>--}}
{{--                        <input name="created_at[date_start]" class="form-control float-right date_start" type="date" value="{{$date_start ?? ""}}" />--}}
{{--                        <small class="text-danger error-message-custom" style="display: none" id="errorDateStart">--}}
{{--                        </small>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div onclick="hideTextDateEnd()" class="col-md-12 col-lg-3 form-group">--}}
{{--                    --}}{{--                    <input class="form-control" name="created_at[date_end]" type="date" placeholder="Đến ngày" value="{{ $date_end ?? "" }}">--}}
{{--                    <div style="position: relative">--}}
{{--                        <div id="txtDateEnd" style="width: 200px;height: 22px;background: #fff;position: absolute;top: 9px;left: 9px;z-index: 1">Đến ngày</div>--}}
{{--                        <input name="created_at[date_end]" class="form-control float-right date_end" type="date" value="{{$date_end ?? ""}}" />--}}
{{--                        <small class="text-danger error-message-custom" style="display: none" id="errorDateEnd">--}}
{{--                        </small>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-12 col-lg-3 select2-custom">--}}
{{--                    <select class="form-group form-control select2" style="width: 100%;height: 34px !important;" name="parent_id">--}}
{{--                        <option selected="selected" value="" disabled>Cấp cha</option>--}}
{{--                        --}}{{--                        @foreach($parent_group as $group)--}}
{{--                        --}}{{--                            <option @if(isset($_GET['parent_id']) && $_GET['parent_id'] == $group->id) {{ 'selected' }} @endif value="{{$group->id}}">{{$group->group_title}}</option>--}}
{{--                        --}}{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="row justify-content-center mt-2">--}}
{{--                <div class="col-md-12 col-lg-3">--}}
{{--                    <button class="search-button btn btn-primary w-100" style="height: 38px;line-height: 16px"><i class="fa fa-search mr-2" aria-hidden="true"></i>Tìm kiếm--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </div>--}}
    <!-- ./Filter -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12 text-center">
                    <h4 class="m-0 font-weight-bold">QUẢN LÝ BANNER</h4>
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
                                <th scope="col" width="15%">Thao tác</th>
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
                                                @if($check_role == 1 || key_exists(2, $check_role))
                                                    <div class="ml-3 mb-2"><i class="fas fa-cog mr-2"></i>
                                                        <a href="{{route('admin.banner.edit',[$item->id, \Crypt::encryptString($item->created_by)])}}" class="text-primary">Chỉnh sửa</a>
                                                    </div>
                                                @endif
                                                @if($check_role == 1 || key_exists(5, $check_role))
                                                    <div class="ml-3"><i class="fas fa-times mr-2" style="padding-right: 6px"></i>
                                                        <a href="javascript:{}" class="text-danger action_delete" data-id="{{$item->id}}" data-created="{{\Crypt::encryptString($item->created_by)}}">
                                                            Xóa
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <td colspan="7">Chưa có dữ liệu</td>
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
{{--                                        @if( $check_role == 1 || key_exists(2, $check_role))--}}
{{--                                            <a class="dropdown-item updateShowOrder" type="button" href="javascript:{}">--}}
{{--                                                <i class="fas fa-pencil-alt bg-orange p-1 mr-2 rounded" style="color: white !important;font-size: 15px"></i> Cập nhật--}}
{{--                                            </a>--}}
{{--                                        @endif--}}

                                        @if($check_role == 1 || key_exists(5, $check_role))
                                            <a class="dropdown-item moveToTrash" type="button" href="javascript:{}" >
                                                <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded" style="color: white !important;font-size: 15px"></i>Thùng rác
                                            </a>
                                        @endif
                                        @if(is_array($check_role) && !key_exists(2, $check_role) && !key_exists(5, $check_role))
                                            <p class="dropdown-item m-0 disabled">
                                                Bạn không có quyền
                                            </p>
                                        @endif
                                    </div>
                                </div>

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
                            @if($check_role == 1 || key_exists(8, $check_role))
                                <div class="d-flex flex-row align-items-center view-trash">
                                    <i class="far fa-trash-alt mr-2"></i>
                                    <div class="link-custom">
                                        <a href="{{route('admin.banner.trash')}}"><span style="color: #347ab6">Xem thùng rác</span>
                                            <span class="badge badge-pill badge-danger trashnum" style="font-weight: 500">{{$trash_num}}</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
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
        function deleteItem(id, created)
        {
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
                    window.location.href= `{{route('admin.banner.delete', ['',''])}}/${id}/${created}`;
                }
            })
        }

        jQuery(document).ready(function ($) {
            $(function () {
                // date filter
                hiddenInputTextDate('#txtDateStart')
                hiddenInputTextDate('#txtDateEnd')
                setMinMaxDate('.date_start', '.date_end')
                // remove click
                $('.action_delete').click(function () {
                    deleteItem($(this).data('id'), $(this).data('created'))
                })

                // move to trash
                $('.dropdown-item.moveToTrash').click(function () {
                    const selectedArray = getSelected();
                    if (selectedArray) {
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
                                if (selectedArray)
                                    $('#formAction').attr('action', $('#formAction').attr('action') + '?action=trash').submit();
                            }
                        });
                    }
                })
                // update show order
                // $('.dropdown-item.updateShowOrder').click(function () {
                //     const selectedArray = getSelected();
                //     if (selectedArray)
                //         $('#formAction').attr('action', $('#formAction').attr('action') + '?action=update').submit();
                // })

                $("#filterForm").on("submit",function(e){
                    console.log('adads')
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

@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách quảng cáo | Quảng cáo express')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/main.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/plusb.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/responsivehao.css")}}">
    <style>

        .block-dashed {
            margin: 30px 0;
            padding: 30px 30px 14px;
            position: relative;
            border: 1px dashed #c2c5d6;
        }
        .block-dashed .title {
            display: inline-block;
            font-size: 18px;
            padding: 0 20px;
            background-color: #fff;
            font-weight: 500;
            line-height: 1;
            margin-bottom: 0;
            text-transform: uppercase;
            color: #000;
            position: absolute;
            top: -9px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
        }
        .form-filter .custom-select {
            color: #0c0c0c;
        }
        .bg-blue-light {
            background-color: #00bff3 !important;
            color: #fff !important;
        }
        .content .table thead th {
            background-color: #034076;
            color: #fff;
            line-height: 1;
            font-weight: 400;
        }
        .content .table th, .content .table td {
            border: 1px solid #b7b7b7;
            text-align: center;
            font-size: 14px;
            color: #0d0d0d;
            vertical-align: middle;
            position: relative;
        }
        .content .table th, .content .table td {
            border: 1px solid #b7b7b7;
            text-align: center;
            font-size: 14px;
            color: #0d0d0d;
            vertical-align: middle;
            position: relative;
        }
        .table tr.active {
            background-color: #eefff2;
        }
        .add-package a {
            display: flex;
            align-items: center;
        }

        .text-blue-light {
            color: #00bff3 !important;
        }
        .content .table td .setting-item {
            display: block;
            color: #0090ff;
            font-size: 14px;
            text-align: left;
            min-width: 150px;
        }
        .add-package a i {
            font-size: 24px;
            margin-right: 5px;
        }

    </style>
@endsection

@section('Content')

    <!-- Main content -->
    <section class="content hiden-scroll mt-3">
        <div class="container-fluid">

            <!-- Filter -->

            <div class="filter block-dashed">

                <h3 class="title">Bộ lọc</h3>

                <form action="" method="get" class="form-filter">

                    <div class="form-row">

                        <div class="col-md-3 form-group">
                            <input type="text" class="form-control" name="keyword" placeholder="Nhập từ khóa (Tên chiến dịch, sđt hoặc email)" value="{{request()->query('keyword') ?? ""}}">
                        </div>

                        <div class="col-md-3 form-group">
                            <select class="custom-select" name="status">
                                <option @if(request()->query('status') == '') {{ 'selected' }} @endif value="">Tình trạng</option>
                                <option @if(request()->query('status') != '' && request()->query('is_confirmed') == 0) {{ 'selected' }} @endif value="0">Chờ duyệt</option>
                                <option @if(request()->query('status') == 1) {{ 'selected' }} @endif value="1">Đã duyệt</option>
                                <option @if(request()->query('status') == 2) {{ 'selected' }} @endif value="2">Không duyệt</option>
                                <option @if(request()->query('status') == -1) {{ 'selected' }} @endif value="-1">Hết hạn</option>
                            </select>
                        </div>

                        @php
                            $created_at = request()->get('time_at');
                            if ($created_at){
                                $date_start = $created_at['date_start'] ?? "";
                                $date_end = $created_at['date_end'] ?? "";
                            }
                        @endphp
                        <div onclick="hideTextDateStart()" class="col-md-3">
                            <div style="position: relative">
                                <div id="txtDateStart" style="width:200px; height: 22px;background: #fff;position: absolute;top: 9px;left: 9px">Từ ngày</div>
                                <input name="time_at[date_start]"class="form-control float-right date_start" type="date" value="{{$date_start ?? ""}}" />
                                <small class="text-danger error-message-custom" style="display: none" id="errorDateStart">
                                </small>
                            </div>
                        </div>
                        <div onclick="hideTextDateEnd()" class="col-md-3">
                            <div style="position: relative">
                                <div id="txtDateEnd" style="width: 200px;height: 22px;background: #fff;position: absolute;top: 9px;left: 9px;z-index: 1">Đến ngày</div>
                                <input name="time_at[date_end]" class="form-control float-right date_end" type="date" value="{{$date_end ?? ""}}" />
                                <small class="text-danger error-message-custom" style="display: none" id="errorDateEnd">
                                </small>
                            </div>
                        </div>

                    </div>


                    <div class="form-group text-center">
                        <button class="btn bg-blue-light"><i class="fas fa-search"></i> Tìm kiếm</button>
                    </div>

                </form>

            </div>

            <!-- / (Filter) -->

            <!-- Main row -->
{{--            <div class="add-package mb-3">--}}
{{--                <a href="{{route('admin.express.add')}}" class="text-blue-light"><i class="fas fa-plus-circle"></i> Thêm chiến dịch</a>--}}
{{--            </div>--}}

            <div class="table-contents">

                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th style="min-width: 150px">
                            Tài khoản tạo
                        </th>
                        <th>Hình ảnh</th>
                        <th>Tên chiến dịch</th>
                        <th>Mô hình</th>
                        <th>Vị trí</th>
                        <th style="min-width: 150px">Thời gian</th>
                        <th style="min-width: 150px">Chi tiết</th>
                        <th>Tình trạng</th>
                        <th width="10%">Thiết lập</th>
                    </tr>
                    </thead>

                    <tbody>
                    <form action="#" id="formAction" method="post">
                        @csrf
                        @forelse($list as $item)
                            <tr>
                                <td>
                                    {{$item->id}}
                                </td>
                                <td>
                                    <p>{{ data_get($item->user_detail , 'fullname') }}</p>
                                    <p>{{ data_get($item->user , 'phone_number') }}</p>
                                    <p>{{ data_get($item->user , 'email') }}</p>
                                </td>
                                <td>
                                    <img src="{{ asset($item->image_url)}}" alt="" style="max-height: 200px;max-width: 200px;">
                                </td>
                                <td>
                                    {{ $item->banner_title }}
                                </td>
                                <td>
                                    {{ data_get($item->bannerGroup, 'banner_name') }}
                                </td>
                                <td>
                                    {{ data_get($item->group, 'group_name') }}
                                </td>
                                <td>
                                    <p class="date">{{date('d/m/Y', $item->date_from) . " - " . date('d/m/Y', $item->date_to)}}</p>
                                </td>

                                <td>
{{--                                    @if($item->total_price)--}}
{{--                                        <p class="locate">{{number_format($item->total_price, 0, ".", "") ?? 0}} VNĐ</p>--}}
{{--                                    @else--}}
                                        <p class="locate">{{number_format($item->total_coin_amount, 0, ".", "") ?? 0}} coin</p>
{{--                                    @endif--}}
                                    <p class="locate">{{ $item->num_click }} lượt click</p>
                                    <p class="locate">{{ $item->banner_name }}</p>
                                </td>

                                <td class="
                                    @if($item->date_to < time())
                                    {{"bg-gray-medium"}}
                                    @elseif($item->is_confirm == 0)
                                    {{"bg-orange-light"}}
                                    @elseif($item->is_confirm == 1)
                                    {{"bg-green-light"}}
                                    @elseif($item->is_confirm == 2)
                                    {{"bg-red-light"}}
                                    @endif">
                                    <select name="is_confirmed" id="is_confirmed" class="custom-select" onchange="changeStatus({{$item->id}}, this.value)"
                                    {{ $item->date_to < time() || $item->is_confirm == 2 ? 'disabled' : ''}} >
                                        <option value="0" {{ $item->is_confirm == 0 ? 'selected' : '' }}> Chưa duyệt </option>
                                        <option value="1" {{ $item->is_confirm == 1 ? 'selected' : '' }}> Đã duyệt </option>
                                        <option value="2" {{ $item->is_confirm == 2 ? 'selected' : '' }}> Không duyệt </option>
                                        <option value="-1" {{ $item->date_to < time() ? 'selected' : '' }}> Hết hạn </option>
                                    </select>
                                </td>
                                <td>
                                    @if($item->date_to < time())
                                    <div class="ml-3 mb-2">
                                        <a href="{{route('admin.express.renewal_ajax', $item->id)}}" class="setting-item outstanding highlight" ><i class="fas fa-redo-alt"></i> Gia hạn</a>
                                    </div>
                                    @endif
                                    @if($check_role == 1 || key_exists(2, $check_role))
                                        <div class="ml-3 mb-2 ">
                                            <a href="{{route('admin.express.edit', $item->id)}}" class="setting-item outstanding"><i class="fas fa-cog"></i> Chỉnh sửa</a>
                                        </div>
                                    @endif
                                        <div class="ml-3 mb-2 text-left setting-item">
                                            <a href="#" class="setting-item create" data-toggle="modal" data-target="#modalCreateNotify"
                                               data-user-id="{{ $item->user_id }}" data-user-username="{{ data_get($item->user, 'username') }}"><i class="fas fa-comment-dots"></i> Tạo thông báo </a> <!-- <span class="count">2</span> -->
                                        </div>
{{--                                    <a href="#" style="margin-left: 2px" class="setting-item delete text-red mb-2" data-id="{{$item->id}}" data-created="{{\Crypt::encryptString($item->created_by)}}"><i class="fas fa-times"></i> Xóa</a>--}}

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">Chưa có dữ liệu</td>
                            </tr>
                        @endforelse
                    </form>
                    </tbody>
                </table>

            </div>

            <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
                <div class="text-left d-flex align-items-center">
                    <div class="manipulation d-flex">
                        <img src="image/manipulation.png" alt="" id="btnTop">
{{--                        <div class="btn-group ml-1">--}}
{{--                            <button type="button" class="btn dropdown-toggle dropdown-custom"--}}
{{--                                    data-toggle="dropdown"--}}
{{--                                    aria-expanded="false" data-flip="false" aria-haspopup="true">--}}
{{--                                Thao tác--}}
{{--                            </button>--}}
{{--                            <div class="dropdown-menu">--}}
{{--                                @if($check_role == 1  ||key_exists(6, $check_role))--}}
{{--                                    <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">--}}
{{--                                        <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"--}}
{{--                                           style="color: white !important;font-size: 15px"></i>Thùng rác--}}
{{--                                        <input type="hidden" name="action" value="trash">--}}
{{--                                    </a>--}}
{{--                                @else--}}
{{--                                    <p class="dropdown-item m-0 disabled">--}}
{{--                                        Bạn không có quyền--}}
{{--                                    </p>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                    <div class="d-flex align-items-center justify-content-between mx-4">
                        <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                        <form action="{{route('admin.project.list')}}" method="GET">
                            <label class="select-custom2">
                                <select id="paginateNumber" name="items" onchange="submitPaginate(event, this)">
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
                                </select>
                            </label>
                        </form>
                    </div>
{{--                    <div class="view-trash">--}}
{{--                        <a href="{{route('admin.event.trash')}}"><i class="far fa-trash-alt"></i> Xem thùng--}}
{{--                            rác</a>--}}
{{--                        <span class="count-trash">{{$count_trash}}</span>--}}
{{--                    </div>--}}
                </div>
                <div class="d-flex align-items-center" >
                    <div class="count-item" >Tổng cộng: @empty($list) {{0}} @else {{$list->total()}} @endempty items</div>
                    <div class="count-item count-item-reponsive" style="display: none">@empty($list) {{0}} @else {{$list->total()}} @endempty items</div>
                    @if($list)
                        {{ $list->render('Admin.Layouts.Pagination') }}
                    @endif
                </div>
            </div>

            <!-- /Main row -->

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('Script')
    <script src="{{asset('system/js/table.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
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
        function changeStatus(id, status)
        {
            Swal.fire({
                title: 'Xác nhận thay đổi trạng thái',
                text: "Sau khi thay đổi sẽ cập nhật trạng thái!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href= `{{route('admin.express.change-status', ['',''])}}/${id}/${status}`;
                }
            })
        }

        $(document).ready(function () {
            // date filter
            hiddenInputTextDate('#txtDateStart')
            hiddenInputTextDate('#txtDateEnd')
            setMinMaxDate('.date_start', '.date_end')

            // remove click
            $('.setting-item.delete').click(function (e) {
                e.preventDefault()
                deleteItem($(this).data('id'), $(this).data('created'))
            })

            // move to trash
            $('.dropdown-item.moveToTrash').click(function () {
                const selectedArray = getSelected();
                if(selectedArray) {
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

            $('.highlight').click(function (event) {
                event.preventDefault()
                let url = $(this).attr('href')
                let date = ''
                let html = `
                     <input type="text" autocomplete="off" class="form-control float-right timedates renewDate" name="date" placeholder="Chọn ngày bắt đầu - Ngày kết thúc" value="{{old('date') ?? ''}}">
                `;
                Swal.fire({
                    title: 'Xác nhận gia hạn',
                    html: html,
                    // text: "Sau khi thay đổi quảng cáo express sẽ được gia hạn lại!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'Quay lại',
                    confirmButtonText: 'Đồng ý',
                    willOpen: function () {
                        let isValidDates = <?php echo json_encode($isValidDates); ?>;
                        initDateRangePicker('input.timedates', isValidDates)
                    },
                    preConfirm: (value) => {
                        date = $('.renewDate').val()
                        if (!date) {
                            Swal.showValidationMessage('Ngày bắt đầu và ngày kết thúc phải được chọn')
                        }else{
                            $.ajax({
                                url: "{{route('param.check_valid_date')}}",
                                data: {
                                    'string_date' :date
                                },
                                success: function (data) {
                                    if(data == -1){
                                        Swal.fire({
                                            title: 'Ngày không hợp lệ',
                                            icon: 'error',
                                            text: 'Ngày bắt đầu và ngày kết thúc phải được ở dạng dd/mm/yyyy - dd/mm/yyyy'
                                        })
                                        return;
                                    }
                                    if(data > 0){
                                        Swal.fire({
                                            title: 'Ngày không hợp lệ',
                                            icon: 'error',
                                            text: 'Một trong các ngày đã chọn đã được người khác thuê. Vui lòng chọn ngày khác'
                                        })
                                        return;
                                    }else{
                                        $.ajax({
                                            url: url,
                                            type: "POST",
                                            dataType: "json",
                                            data: {
                                                _token : "{{csrf_token()}}",
                                                'date' : date
                                            },
                                            success: function (result){
                                                window.location.reload()
                                            }
                                        })
                                    }
                                }
                            }).fail(function (err) {
                            });
                        }
                    }
                })
            })
        });
    </script>
@endsection

@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách sự kiện | Quản lý sự kiện')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/main.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/plusb.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/responsivehao.css")}}">
@endsection

@section('Content')
<div class="row m-0 p-3">
    <ol class="breadcrumb mt-1 align-items-center">
        <li class="recye px-2">
            <a href="{{route('admin.event.list')}}">
                <i class="fa fa-th-list mr-1"></i>Danh sách
            </a>
        </li>
    </ol>
</div>

<section class="content hiden-scroll mt-3">
    <div class="container-fluid">
        <div class="filter block-dashed">

            <h3 class="title">Bộ lọc</h3>

            <form action="" method="get" class="form-filter">

                <div class="form-row">

                    <div class="col-md-6 form-group">
                        <input type="text" class="form-control" name="keyword" placeholder="Nhập từ khóa (Tên sự kiện, đơn vị tổ chức, ...)" value="{{request()->query('keyword') ?? ""}}">
                    </div>

                    @php
                        $created_at = request()->get('created_at');
                        if ($created_at){
                            $date_start = $created_at['date_start'] ?? "";
                            $date_end = $created_at['date_end'] ?? "";
                        }
                    @endphp
                    <div onclick="hideTextDateStart()" class="col-md-3">
                        <div style="position: relative">
                            <div id="txtDateStart" style="width:200px; height: 22px;background: #fff;position: absolute;top: 9px;left: 9px">Từ ngày</div>
                            <input name="created_at[date_start]"class="form-control float-right date_start" type="date" value="{{$date_start ?? ""}}" />
                            <small class="text-danger error-message-custom" style="display: none" id="errorDateStart">
                            </small>
                        </div>
                    </div>
                    <div onclick="hideTextDateEnd()" class="col-md-3">
                        <div style="position: relative">
                            <div id="txtDateEnd" style="width: 200px;height: 22px;background: #fff;position: absolute;top: 9px;left: 9px;z-index: 1">Đến ngày</div>
                            <input name="created_at[date_end]" class="form-control float-right date_end" type="date" value="{{$date_end ?? ""}}" />
                            <small class="text-danger error-message-custom" style="display: none" id="errorDateEnd">
                            </small>
                        </div>
                    </div>

                </div>

                <div class="form-row">
                    <div class="col-md-3 form-group">
                        <select class="custom-select" name="is_confirmed">
                            <option @if(request()->query('is_confirmed') == '') {{ 'selected' }} @endif value="">Tình trạng</option>
                            <option @if(request()->query('is_confirmed') != '' && request()->query('is_confirmed') == 0) {{ 'selected' }} @endif value="0">Chờ duyệt</option>
                            <option @if(request()->query('is_confirmed') == 1) {{ 'selected' }} @endif value="1">Đã duyệt</option>
                            <option @if(request()->query('is_confirmed') == 3) {{ 'selected' }} @endif value="3">Hết hạn</option>
                            <option @if(request()->query('is_confirmed') == 2) {{ 'selected' }} @endif value="2">Không duyệt</option>
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <select class="custom-select" name="province_id" id="province"
                                onchange="get_district(this, '{{route('param.get_district')}}', '#district')">
                            <option @if(isset($_GET['province_id']) && $_GET['province_id'] == '') {{ 'selected' }} @endif value="">Tỉnh / Thành phố</option>
                            @foreach($province as $item)
                                <option @if(request()->query('province_id') == $item->id) {{ 'selected' }} @endif value="{{$item->id}}">{{$item->province_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <select class="custom-select" name="district_id" id="district">
                            <option selected value="">Quận / Huyện</option>
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <select class="custom-select" name="is_highlight">
                            <option @if(request()->query('is_highlight') == '') {{ 'selected' }} @endif value="">Loại sự kiện</option>
                            <option @if(request()->query('is_highlight') == 1) {{ 'selected' }} @endif value="1">Nổi bật</option>
                            <option @if(request()->query('is_highlight') != '' && request()->query('is_highlight') == 0) {{ 'selected' }} @endif value="0">Thường</option>
                        </select>
                    </div>

                </div>

                <div class="form-group text-center">
                    <button class="btn bg-blue-light"><i class="fas fa-search"></i> Tìm kiếm</button>
                </div>

            </form>

        </div>

        <div class="table-contents">

            <table class="table">
                <thead>
                <tr>
                    <th><input type="checkbox" class="select-all checkbox"></th>
                    <th>STT</th>
                    <th>
                        Tình trạng
                    </th>
                    <th>
                        Trạng thái
                    </th>
                    <th>Sự kiện</th>
                    <th>Địa điểm</th>
                    <th style="min-width: 150px">Thời gian tổ chức</th>
                    <th>Cài đặt</th>
                </tr>
                </thead>

                <tbody>
                    @forelse($list as $item)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{$item->id}}" data-name="nnnn" class="select-item checkbox" name="select_item[]" />
                                <input type="hidden" hidden name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}">
                            </td>
                            <td>{{$item->id}}</td>
                            <td class="
                                @if($item->is_confirmed == 0)
                                {{"bg-orange-light"}}
                                @elseif($item->is_confirmed == 1)
                                {{"bg-green-light"}}
                                @elseif($item->is_confirmed == 2)
                                {{"bg-red-light"}}
                                @elseif($item->end_date < time() || $item->is_confirmed == 3)
                                {{"bg-gray-medium"}}
                                @endif">
                                <select name="is_confirmed" id="is_confirmed" class="custom-select" onchange="changeStatus({{$item->id}}, this.value)">
                                    <option value="0" {{$item->is_confirmed == 0 ? 'selected' : ''}}>Chờ duyệt</option>
                                    <option value="1" {{$item->is_confirmed == 1 ? 'selected' : ''}}>Đã duyệt</option>
                                    <option value="3" {{$item->is_confirmed == 3 ? 'selected' : ''}}>Hết hạn</option>
                                    <option value="2" {{$item->is_confirmed == 2 ? 'selected' : ''}}>Không duyệt</option>
                                </select>
                            </td>
                            <td>
                                @if($item->is_block)
                                    <span class="badge badge-pill badge-danger badge-sm">
                                        Đã chặn
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="text-left title"><a target="_blank" href="{{ route('home.event.detail', $item->event_url) }}" class="{{$item->is_highlight ? 'is_vip' : ''}}">{{$item->event_title}}</a></div>
                                <div class="text-left mt-2">Đơn vị tổ chức: {{$item->bussiness->user_detail->fullname??"Quản trị"}}</div>
                            </td>
                            <td>
                                {{ $item->getLocationAddress() }}
                            </td>
                            <td>
                                <p class="time mb-1">{{date('G', $item->start_date)}}h{{date('i', $item->start_date)}}</p>
                                <p class="date">{{date('d/m/Y', $item->start_date)}}</p>
                            </td>
                            <td>
                                @if($check_role == 1  ||key_exists(2, $check_role))
                                <a href="{{route('admin.event.edit', [$item->id, \Crypt::encryptString($item->created_by)])}}])}}" class="setting-item edit mb-2"><i class="fas fa-cog"></i> Chỉnh sửa</a>
                                @endif

                                <x-admin.delete-button
                                    :check-role="$check_role"
                                    url="{{ route('admin.event.delete-multiple', ['ids' => $item->id]) }}"
                                />

                                @if($item->user)
                                <a href="#" class="setting-item create" data-toggle="modal" data-target="#modalCreateNotify"
                                   data-user-id="{{$item->user_id}}" data-user-username="{{ $item->user->getFullName() }}">
                                    <i class="fas fa-comment-dots"></i> Tạo thông báo
                                </a> <!-- <span class="count">2</span> -->
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Chưa có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-admin.table-footer
            :check-role="$check_role"
            :lists="$list"
            :count-trash="$count_trash"
            view-trash-url="{{ route('admin.event.trash') }}"
            delete-url="{{ route('admin.event.delete-multiple') }}"
        />
    </div>
</section>
@endsection

@section('Script')
<script src="js/table.js" type="text/javascript"></script>
<script type="text/javascript">
    function hideTextDateStart(){
        $('#txtDateStart').hide();
    }
    function hideTextDateEnd(){
        $('#txtDateEnd').hide();
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
                window.location.href= `{{route('admin.event.change-status', ['',''])}}/${id}/${status}`;
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

        @if(request()->query('province_id') && request()->query('district_id'))
            get_district('#province', '{{route('param.get_district')}}', '#district', {{$_GET['province_id']}}, {{$_GET['district_id']}})
        @endif

        {{--$('.highlight').click(function (event) {--}}
        {{--    event.preventDefault()--}}
        {{--    Swal.fire({--}}
        {{--        title: 'Xác nhận thay đổi nổi bật',--}}
        {{--        text: "Sau khi thay đổi sẽ cập nhật trạng thái nổi bật!",--}}
        {{--        icon: 'warning',--}}
        {{--        showCancelButton: true,--}}
        {{--        confirmButtonColor: '#d33',--}}
        {{--        cancelButtonColor: '#3085d6',--}}
        {{--        cancelButtonText: 'Quay lại',--}}
        {{--        confirmButtonText: 'Đồng ý'--}}
        {{--    }).then((result) => {--}}
        {{--        if (result.isConfirmed) {--}}
        {{--            window.location.href= $(this).prop('href')--}}
        {{--        }--}}
        {{--    })--}}
        {{--})--}}
        {{--$('.un_hight_light').click(function (event) {--}}
        {{--    event.preventDefault()--}}
        {{--    Swal.fire({--}}
        {{--        title: 'Xác nhận bỏ nổi bật',--}}
        {{--        text: "Sau khi thay đổi sẽ cập nhật trạng thái nổi bật!",--}}
        {{--        icon: 'warning',--}}
        {{--        showCancelButton: true,--}}
        {{--        confirmButtonColor: '#d33',--}}
        {{--        cancelButtonColor: '#3085d6',--}}
        {{--        cancelButtonText: 'Quay lại',--}}
        {{--        confirmButtonText: 'Đồng ý'--}}
        {{--    }).then((result) => {--}}
        {{--        if (result.isConfirmed) {--}}
        {{--            window.location.href= $(this).prop('href')--}}
        {{--        }--}}
        {{--    })--}}
        {{--})--}}

        {{--$('.is_hight_light').click(function (event) {--}}
        {{--    event.preventDefault();--}}
        {{--    var id = $(this).data('id');--}}
        {{--    Swal.fire({--}}
        {{--        title: 'Thời gian nổi bật',--}}
        {{--        input: 'select',--}}
        {{--        inputOptions: {--}}
        {{--            '1': '1 tuần',--}}
        {{--            '2': '2 tuần',--}}
        {{--        },--}}
        {{--        inputPlaceholder: '---Chọn',--}}
        {{--        showCancelButton: true,--}}
        {{--        inputValidator: function (value) {--}}
        {{--            return new Promise(function (resolve, reject) {--}}
        {{--                if (value !== '') {--}}
        {{--                    resolve();--}}
        {{--                } else {--}}
        {{--                    resolve('Bạn cần chọn thời gian');--}}
        {{--                }--}}
        {{--            });--}}
        {{--        }--}}
        {{--    }).then(function (result) {--}}
        {{--        if (result.isConfirmed) {--}}
        {{--            var value = result.value;--}}
        {{--            window.location.href = "{{route('admin.event.high_light',['',''])}}/"+id+"/"+value;--}}
        {{--        }--}}
        {{--    });--}}
        {{--})--}}
    });
</script>
@endsection

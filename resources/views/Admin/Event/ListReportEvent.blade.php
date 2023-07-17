@extends('Admin.Layouts.Master')
@section('Title', 'Báo cáo | Sự kiện')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-event.css") }}">
    <style type="text/css">
        .dropdown-toggle::after{
            margin-top: 8px !important;
            float: right !important;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/main.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/plusb.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/responsivehao.css")}}">
@endsection
@section('Content')
    <div class="row m-0 p-3">
        <div class="col-12 p-0">
            <div class="box-dash mt-4 pt-4">
                <h3 class="title-info-reponsive font-weight-bold">BỘ LỌC</h3>
                <form id="filterForm"  method="get">
                    <div class="row m-0 pt-2 ">
                        <div class="col-12 col-sm-12 col-md-5 col-lg-5 box_input px-0">
                            <div class="input-reponsive-search pr-3">
                                <input class="form-control required" type="text" name="keyword" placeholder="Nhập từ khóa (Tên sự kiện, đơn vị tổ chức)" value="{{ app('request')->input('keyword') }}">
                            </div>
                        </div>
                        <div class="search-reponsive col-12 col-sm-12 col-md-7 col-lg-7 pl-0">
                            <div class="row m-0">
                                <div class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pr-0">
                                    <div class="search-reponsive ">
                                        <select name="report_content" class="form-control select2" style="width: 100%;height: 34px !important;">
                                            <option selected="selected" value="">Nội dung báo cáo</option>
                                            @foreach ($getReportReason as $item)
                                                <option value="{{$item->id}}" {{ request()->query('report_content') == $item->id ? 'selected' : '' }}>{{$item->content}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="from_date_box" class="mtdow10 search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pr-2 pl-4 ">
                                    <div  style="position: relative">
                                        @if(app('request')->input('from_date') == "")
                                            <div id="from_date_text"  style="position: absolute;width: 60%;height: 38px;padding: 1px;">
                                                <div class="bg-white"><span class="ml-2" style="line-height: 36px;">Từ ngày</span></div>

                                            </div>
                                        @endif
                                        <input id="handleDateFrom" class="start_day form-control float-left" name="from_date" type="date" placeholder="Từ ngày" value="{{ app('request')->input('from_date') }}" >
                                    </div>
                                </div>
                                <div id="to_date_box" class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pl-3 pr-2">
                                    <div  style="position: relative">
                                        @if(app('request')->input('to_date') == "")
                                            <div id="to_date_text" style="position: absolute;width: 60%;height: 38px;padding: 1px;">
                                                <div class="bg-white"><span class="ml-2" style="line-height: 36px;">Đến ngày</span></div>
                                            </div>
                                        @endif
                                        <input id="handleDateTo" class="end_day form-control float-right" name="to_date" type="date" placeholder="Đến ngày" value="{{ app('request')->input('to_date') }}" >
                                        <div id="appendDateError"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container pb-3">
                            <div class="row">
                                <div class="mtdow10 col text-center">
                                    <button id="filter-report" class=" mtdow10 search-button btn bg-blue-light w-100 mt-1" style="width: 130px !important"><i class="fa fa-search mr-2 ml-0" aria-hidden="true"></i>Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content mt-3" > <!-- hiden-scroll  -->
        <div class="container-fluid">
            <div class="row" >
                <div class="col-12">
                    <div class="table-contents">
                        <table class="table">
                            <thead>
                            <tr>
                                <th><input type="checkbox" class="select-all checkbox"></th>
                                <th>STT</th>
                                <th>Sự kiện</th>
                                <th style="min-width: 250px">Nội dung báo cáo</th>
                                <th>Thời gian</th>
                                <th class="dropdown" style="min-width: 180px">
                                    Tình trạng
{{--                                    <a href="#" class="nav-link" data-toggle="dropdown">Tình trạng</a>--}}
{{--                                    <div class="dropdown-menu dropdown-menu-right">--}}
{{--                                        <a href="#" class="dropdown-item">--}}
{{--                                            Chặn hiển thị--}}
{{--                                        </a>--}}
{{--                                        <a href="#" class="dropdown-item">--}}
{{--                                            Khôi phục hiển thị--}}
{{--                                        </a>--}}
{{--                                        <a href="#" class="dropdown-item">--}}
{{--                                            Chặn tài khoản--}}
{{--                                        </a>--}}
{{--                                        <a href="#" class="dropdown-item">--}}
{{--                                            Cấm tài khoản--}}
{{--                                        </a>--}}
{{--                                        <a href="#" class="dropdown-item">--}}
{{--                                            Xóa tài khoản--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
                                </th>
                                <th>Cài đặt</th>
                            </tr>
                            </thead>
                            <tbody>
                                <form action="{{route('admin.event.report.list_action')}}" id="trash_list" method="post">
                                    @csrf
                                    <input type="hidden" name="action" id="action_list" value="">
                                    <input type="hidden" name="action_method" id="action_method" value="">
                                    @forelse($list as $key => $item)
                                        <tr>
                                            <td>
                                                <input type="checkbox" value="{{$item->id}}" class="select-item checkbox" name="select_item[]" />
                                                <input type="hidden" hidden name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}">
                                            </td>
                                            <td>{{ ($list->currentpage()-1) * $list->perpage() + $key + 1 }}</td>
                                            <td>
                                                <div class="text-left title"><a href="javascript:{}">{{$item->event_title}}</a></div>
                                                <div class="text-left mt-2">Đơn vị tổ chức:
                                                    {{ data_get($item->bussiness, 'detail.fullname', data_get($item->admin, 'admin_fullname')) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="content-report">
                                                    <div class="list-report mb-2">
                                                        @foreach($item->report->groupBy('report_type') as $i)
                                                        <div class="item mb-2">{{$i[0]->report_group->content}}<span class="text-gray">({{count($i)}})</span></div>
                                                        @endforeach
                                                    </div>
                                                    @if(count($item->report->groupBy('report_type')) > 3)
                                                        <span class="view-more">Hiện thêm <span>»</span></span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                {{date('d/m/Y',$item->report->first()->report_time)}}
                                            </td>
                                            <td>
                                                <select name="" id="action_select" class="custom-select" data-id="{{$item->id}}">
                                                    <option value="1" {{$item->is_block == 1 ? 'selected' : ''}}>Chặn hiển thị</option>
                                                    <option value="0" {{$item->is_block == 0 ? 'selected' : ''}}>Hiển thị</option>
                                                </select>
                                            </td>
                                            <td>
                                                @if($check_role == 1  ||key_exists(2, $check_role))
                                                    <div style="display: flex;flex-direction: column;text-align: start">
                                                        @if($item->is_block ==0)
                                                            <div class=" ml-2 mb-1">
                                                                <i class="icon-setup fas fa-eye-slash" style="margin-right: 3px"></i>
                                                                <a href="javascript:{}" class=" block_display  text-red mb-2" data-id="{{$item->id}}"> Chặn hiển thị</a>
                                                            </div>
                                                        @endif
                                                        @if($item->is_block == 1)
                                                            <div class=" ml-2 mb-1">
                                                                <i class="fas fa-sync-alt"></i>
                                                                <a href="javascript:{}" class="ml-2 unblock_display edit mb-2" data-id="{{$item->id}}"> Khôi phục hiển thị</a>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($item->bussiness)
                                                            @if($item->bussiness->is_locked == 0)
                                                                <div class="ml-2 mb-1">
                                                                    <i class="icon-setup fas fa-lock"></i>
                                                                    <a class="text-red block_account" data-id="{{$item->id}}" data-created_by="" style="cursor:pointer">Chặn tài khoản</a>
                                                                </div>
                                                            @else
                                                                <div class="ml-2 mb-1">
                                                                    <i class="icon-setup fas fa-undo-alt"></i>
                                                                    <a class="text-primary unblock_account" data-id="{{$item->id}}" data-created_by="" style="cursor:pointer">Bỏ chặn</a>
                                                                </div>
                                                            @endif
                                                        
                                                            @if($item->bussiness->is_forbidden == 0)
                                                                <div class="ml-2 mb-1">
                                                                    <i class="icon-setup fas fa-ban"></i>
                                                                    <a class="text-red forbidden" data-id="{{$item->id}}" data-created_by="" style="cursor:pointer">Cấm tài khoản</a>
                                                                </div>
                                                            @else
                                                                <div class="ml-2 mb-1">
                                                                    <i class="icon-setup fas fa-undo-alt"></i>
                                                                    <a class="text-primary unforbidden" data-id="{{$item->id}}" data-created_by="" style="cursor:pointer">Bỏ cấm</a>
                                                                </div>
                                                            @endif

                                                            @if($item->bussiness->is_deleted == 0)
                                                                <div class="ml-2 mb-1">
                                                                    <i class="icon-setup fas fa-times mr-0"></i>
                                                                    <a class="text-red delete_user" data-id="{{$item->id}}" data-created_by="" style="cursor:pointer">Xóa tài khoản</a>
                                                                </div>
                                                            @else
                                                                <div class="ml-2 mb-1">
                                                                    <i class="icon-setup fas fa-undo-alt"></i>
                                                                    <a class="text-primary undelete_account" data-id="{{$item->id}}" data-created_by="" style="cursor:pointer">Khôi phục tài khoản</a>
                                                                </div>
                                                            @endif
                                                            <div class="ml-2 mb-1">
                                                                <i class="fas fa-bell" style="margin-right: 12px"></i>
                                                                <a href="#" class="text-primary" data-toggle="modal" data-target="#modalCreateNotify"
                                                                data-user-id="{{$item->user_id}}" data-user-username="{{ $item->user->getFullName() }}">
                                                                    Tạo thông báo
                                                                </a> <!-- <span class="count">2</span> -->
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
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

                    <div class="table-bottom d-flex align-items-center justify-content-between  pb-5" style="margin-bottom: 130px !important">
                        <div class="text-left d-flex align-items-center">
                            <div class="manipulation d-flex mr-4 ">
                                <img src="image/manipulation.png" alt="" id="btnTop">
                                <div class="btn-group ml-1">
                                    <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown"
                                            aria-expanded="false" data-flip="false" aria-haspopup="true">
                                        Thao tác
                                    </button>
                                    <div class="dropdown-menu">
                                        @if($check_role == 1  ||key_exists(2, $check_role))
                                            <a class="dropdown-item list_block_display" type="button" href="javascript:{}">
                                                <i class="fas fa-eye-slash bg-red p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px"></i>Chặn hiển thị
                                                <input type="hidden" name="action" value="trash">
                                            </a>
                                            <a class="dropdown-item list_unblock_display" type="button" href="javascript:{}">
                                                <i class="fas fa-undo-alt bg-primary p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px"></i>Khôi phục hiển thị
                                                <input type="hidden" name="action" value="trash">
                                            </a>
{{--                                            <a class="dropdown-item list_locked" type="button" href="javascript:{}">--}}
{{--                                                <i class="fas fa-lock bg-red p-1 mr-2 rounded"--}}
{{--                                                   style="color: white !important;font-size: 15px"></i>Chặn tài khoản--}}
{{--                                                <input type="hidden" name="action" value="trash">--}}
{{--                                            </a>--}}
{{--                                            <a class="dropdown-item list_forbidden" type="button" href="javascript:{}">--}}
{{--                                                <i class="fas fa-ban  bg-red p-1 mr-2 rounded"--}}
{{--                                                   style="color: white !important;font-size: 15px"></i>Cấm tài khoản--}}
{{--                                                <input type="hidden" name="action" value="trash">--}}
{{--                                            </a>--}}
{{--                                            <a class="dropdown-item list_deleted" type="button" href="javascript:{}">--}}
{{--                                                <i class="fas fa-times  bg-red p-1 mr-2 rounded"--}}
{{--                                                   style="color: white !important;font-size: 15px"></i>Xóa tài khoản--}}
{{--                                                <input type="hidden" name="action" value="trash">--}}
{{--                                            </a>--}}
                                        @else
                                            <p class="text-center">Không đủ quyền</p>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mr-2">
                                <div class="d-flex mr-0 align-items-center mr-2">Hiển thị</div>
                                <label class="select-custom2">
                                    <select id="paginateNumber" name="items" onchange="submitPaginate(event)">
                                        <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
                                        <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
                                        <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
                                    </select>
                                </label>
                            </div>
                            {{-- @if($check_role == 1  ||key_exists(8, $check_role))
                            <div class="view-trash">
                             <a href="{{route('admin.promotion.trash')}}"><i class="far fa-trash-alt"></i> Xem thùng rác</a>
                             <span class="count-trash">
                              @if(isset($count_trash))
                              {{$count_trash}}
                              @endif
                            </span>
                          </div>
                          @endif --}}
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="count-item">Tổng cộng: @empty($list) {{0}} @else {{$list->total()}} @endempty items</div>
                            @if($list)
                                {{ $list->render('Admin.Layouts.Pagination') }}
                            @endif
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('Script')
    <script src="js/table.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function () {
            // Init Calendar
            $('#from_date_box').click(function(){
                $('#from_date_text').hide();
            })
            $('#to_date_box').click(function(){
                $('#to_date_text').hide();
            })
            setMinMaxDate('#handleDateFrom', '#handleDateTo')

            // Readmore
            $(".view-more").on("click", function() {
                $(this).parent(".content-report").toggleClass("show");
                if ($(this).parent(".content-report").hasClass("show")) {
                    $(this).html("Thu gọn <span>&#187;</span>");
                } else {
                    $(this).html("Hiện thêm <span>&#187;</span>");
                }
            });


            $('#show-more').click(function(){
                $('.report-reasion').toggleClass("show-more");
                $('#show-more').html($('#show-more').html() == '<p class="text-primary mb-0 cusor-point text-dark">Thu gọn <i class="fa fa-angle-double-up ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>' ? '<p class="text-primary mb-0 cusor-point">Hiện thêm <i class="fa fa-angle-double-down ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>' : '<p class="text-primary mb-0 cusor-point text-dark">Thu gọn <i class="fa fa-angle-double-up ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>');
            })

            $('#action_select').change(function () {
                var id = $(this).data('id');
                // var created_by = $(this).data('created_by');
                title = this.value == 1 ? 'Chặn hiển thị' : 'Khôi phục hiển thị';
                Swal.fire({
                    title: title,
                    text: "Sau khi xác nhận sẽ tiến hành " + title.toLowerCase() +" sự kiện",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'Quay lại',
                    confirmButtonText: 'Đồng ý'
                }).then((result) => {
                    if (result.isConfirmed) {
                      this.value == 1 ?  window.location.href = "{{route('admin.event.report.block_display', '')}}/" + id : window.location.href = "{{route('admin.event.report.unblock_display', '')}}/" + id;;
                    }
                });
            })

        })

    </script>

    <script type="text/javascript">
        // Chặn hiển thị
        $('.block_display').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Chặn hiển thị',
                text: "Sau khi xác nhận sẽ tiến hành chặn hiển thị sự kiện",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.event.report.block_display', '')}}/" + id;
                }
            });
        });
        // Khôi phục hiển thị
        $('.unblock_display').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Khôi phục hiển thị',
                text: "Sau khi xác nhận sẽ tiến hành khôi phục hiển thị sự kiện",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.event.report.unblock_display', '')}}/" + id;
                }
            });
        });
        // Cấm tài khoản
        $('.forbidden').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Cấm tài khoản',
                text: "Sau khi xác nhận sẽ tiến hành cấm tài khoản ",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.report.event.forbidden', '')}}/" + id;
                }
            });
        });
        // Chặn tài khoản
        $('.block_account').click(function () {
            var id = $(this).data('id');
            var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Chặn hiển thị sự kiện',
                text: "Sau khi xác nhận sẽ chặn hiển thị sự kiện",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.report.event.block-account', '')}}/" + id;
                }
            });
        });
        //Bỏ Chặn tài khoản
        $('.unblock_account').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Bỏ chặn tài khoản',
                text: "Xác nhận bỏ chặn tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.report.event.unblock-account', '')}}/" + id;
                }
            });
        });
        // Xóa tài khoản
        $('.delete_user').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Xóa tài khoản',
                text: "Sau khi xác nhận sẽ tiến hành xóa tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.report.event.delete', '')}}/" + id;
                }
            });
        });
        // khôi phục hiển thị hàng loạt
        $('.list_unblock_display').click(function () {
            Swal.fire({
                title: 'Khôi phục hiển thị',
                text: "Sau khi đồng ý sẽ khôi phục hiển thị sự kiện",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("unblock_display");
                    $('#trash_list').submit();

                }
            });
        });
        // Chặn tài khoản
        $('.list_locked').click(function () {
            Swal.fire({
                title: 'Chặn tài khoản',
                text: "Sau khi đồng ý sẽ chặn tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("list_locked");
                    $('#trash_list').submit();

                }
            });
        });
        // Bỏ cấm tài khoản
        $('.unforbidden').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Bỏ cấm tài khoản',
                text: "Sau khi xác nhận sẽ tiến hành bỏ cấm tài khoản ",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.report.event.unforbidden', '')}}/" + id;
                }
            });
        });
        // Xóa tài khoản
        $('.list_deleted').click(function () {
            Swal.fire({
                title: 'Xóa tài khoản',
                text: "Sau khi đồng ý sẽ xóa tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("list_deleted");
                    $('#trash_list').submit();

                }
            });
        });
        // Khôi phục tài khoản
        $('.undelete_account').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Khôi phục tài khoản',
                text: "Sau khi xác nhận sẽ tiến hành khôi phục tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.report.event.restore', '')}}/" + id;
                }
            });
        });

        $('.list_block_display').click(function () {
            Swal.fire({
                title: 'Chặn hiển thị',
                text: "Sau khi đồng ý sẽ chặn hiển thị sự kiện",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("block_display");
                    $('#trash_list').submit();

                }
            });
        });
    </script>
    <script type="text/javascript">
        function submitPaginate(event){
            const uri = window.location.toString();
            const exist = uri.indexOf('?')
            const existItems = uri.indexOf('?items')
            const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
            exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() : window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
        }
        $("#filterForm").on("submit",function(e){
            $(this).append('<input type="hidden" name="items" value="'+ $('#paginateNumber').val() + '" /> ');
        });
    </script>
@endsection

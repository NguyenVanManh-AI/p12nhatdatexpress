@extends('Admin.Layouts.Master')
@section('Title', 'Báo cáo | Bình luận dự án')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-event.css") }}">
    <style type="text/css">
        .dropdown-toggle::after{
            margin-top: 8px !important;
            float: right !important;
        }
        .content .table th, .content .table td {
            font-size: 14px!important;
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
                                <input class="form-control required" type="text" name="keyword" placeholder="Nhập từ khóa (Tài khoản, số điện thoại)" value="{{ app('request')->input('keyword') }}">
                            </div>
                        </div>
                        <div class="search-reponsive col-12 col-sm-12 col-md-7 col-lg-7 pl-0">
                            <div class="row m-0">
                                <div class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pr-0">
                                    <div class="search-reponsive ">
                                        <select name="report_content" class="form-control select2" style="width: 100%;height: 34px !important;">
                                            <option selected="selected" value="">Nội dung báo cáo</option>
                                            @foreach ($report_group as $item)
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
                                <th>Bình luận</th>
                                <th style="min-width: 250px">Nội dung báo cáo</th>
                                <th>Thời gian</th>
                                <th class="dropdown" style="min-width: 180px">
                                    Tình trạng
                                </th>
                                <th scope="col" style="width: 17%;min-width: 170px">Cài đặt</th>
                            </tr>
                            </thead>
                            <tbody>
                            <form action="{{route('admin.comment.project.list_action')}}" id="list_action" method="post">
                                @csrf
                                <input type="hidden" name="action" id="action_list" value="">
                                <input type="hidden" name="action_method" id="action_method" value="">
                                @forelse($list as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox" value="{{$item->id}}" class="select-item checkbox" name="select_item[]" />
                                            <input type="hidden" hidden name="select_item_created[{{$item->id}}]" value="">
                                        </td>
                                        <td>{{$item->id}}</td>
                                        <td>
                                            <div class="text-left text-bold"><a href="javascript:{}">{{$item->comment_content}}</a></div>
                                            <div class="text-left text-gray">
                                                <p class="mb-0">Người bình luận: {{data_get($item->user_detail, 'fullname')}}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="content-report">
                                                <div class="list-report mb-2">
                                                    @foreach($item->report_comment_project->groupBy('report_type') as $i)
                                                        <div class="item mb-2">{{$i[0]->report_group->content}}<span class="text-gray">({{count($i)}})</span></div>
                                                    @endforeach
                                                </div>
                                                @if(count($item->report_comment_project->groupBy('report_type')) >3)
                                                    <span class="view-more">Hiện thêm <span>»</span></span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            {{date('d/m/Y',$item->report_comment_project->first()->report_time)}}
                                        </td>
                                        <td>
                                            {{-- @include('admin.common.partials._item-status', [
                                                'item' => $item,
                                                'label' => 'Tin rao'
                                            ]) --}}
                                            @include('Admin.User.partials._user-status', [
                                                'user' => $item->user,
                                                'label' => 'Tài khoản'
                                            ])
                                        </td>
                                        <td>
                                            @if($check_role == 1  ||key_exists(2, $check_role))
                                                <div style="display: flex;flex-direction: column;text-align: start">

                                                    <div class="ml-2 mb-1">
                                                        <i class="icon-setup fa fa-history"></i>
                                                        <a class="text-primary report_false" data-id="{{$item->id}}" data-created_by="" style="cursor:pointer">Báo cáo sai</a>
                                                    </div>
                                                    @if($item->is_deleted ==0)
                                                        <div class=" ml-2 mb-1">
                                                            <i class="icon-setup fas fa-eye-slash"></i>
                                                            <a href="javascript:{}" class="block_display  text-red mb-2" data-id="{{$item->id}}"> Chặn hiển thị</a>
                                                        </div>
                                                    @endif
                                                    {{--                                @endif--}}
                                                    {{--                                @if($check_role == 1  ||key_exists(2, $check_role))--}}
                                                    @if($item->is_deleted == 1)
                                                        <div class=" ml-2 mb-1">
                                                            <i class="fas fa-sync-alt"></i>
                                                            <a href="javascript:{}" class=" unblock_display edit mb-2" data-id="{{$item->id}}"> Khôi phục hiển thị</a>
                                                        </div>
                                                    @endif
                                                    @if($item->user)
                                                    @if(!$item->user->isBlocked())
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
                                                    @if(!$item->user->isForbidden())
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
                                                    @if($item->user->is_deleted == 0)
                                                        <div class="ml-2 mb-1">
                                                            <i class="icon-setup fas fa-times"></i>
                                                            <a class="text-red delete_account" data-id="{{$item->id}}" data-created_by="" style="cursor:pointer">Xóa tài khoản</a>
                                                        </div>
                                                    @else
                                                        <div class="ml-2 mb-1">
                                                            <i class="icon-setup fas fa-undo-alt"></i>
                                                            <a class="text-primary undelete_account" data-id="{{$item->id}}" data-created_by="" style="cursor:pointer">Khôi phục tài khoản</a>
                                                        </div>
                                                    @endif
                                                    @endif
                                                    <div class="ml-2 mb-1">
                                                        <i class="icon-setup fas fa-bell"></i>
                                                        <a href="#" class="create" data-toggle="modal" data-target="#modalCreateNotify"
                                                           data-user-id="{{$item->user->id}}" data-user-username="{{$item->user->username}}">
                                                            Tạo thông báo
                                                        </a> <!-- <span class="count">2</span> -->
                                                    </div>
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
                                            <a class="dropdown-item list_report_false" type="button" href="javascript:{}">
                                                <i class="fa fa-history bg-primary p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px;"></i>Báo cáo sai
                                            </a>
                                            <a class="dropdown-item list_block_display" type="button" href="javascript:{}">
                                                <i class="fas fa-eye-slash bg-red p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px"></i>Chặn hiển thị
                                            </a>
                                            <a class="dropdown-item list_unblock_display" type="button" href="javascript:{}">
                                                <i class="fas fa-sync-alt bg-primary p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px"></i>Khôi phục hiển thị
                                            </a>
                                            <a class="dropdown-item list_locked" type="button" href="javascript:{}">
                                                <i class="fas fa-lock bg-red p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px"></i>Chặn tài khoản
                                            </a>
                                            <a class="dropdown-item list_unlocked" type="button" href="javascript:{}">
                                                <i class="fas fa-undo-alt bg-primary p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px"></i>Bỏ chặn tài khoản
                                                <input type="hidden" name="action" value="trash">
                                            </a>
                                            <a class="dropdown-item list_forbidden" type="button" href="javascript:{}">
                                                <i class="fas fa-ban  bg-red p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px"></i>Cấm tài khoản
                                                <input type="hidden" name="action" value="trash">
                                            </a>
                                            <a class="dropdown-item list_unforbidden" type="button" href="javascript:{}">
                                                <i class="fas fa-undo-alt  bg-primary p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px"></i>Bỏ cấm tài khoản
                                                <input type="hidden" name="action" value="trash">
                                            </a>
                                            <a class="dropdown-item list_deleted" type="button" href="javascript:{}">
                                                <i class="fas fa-times  bg-red p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px"></i>Xóa tài khoản
                                                <input type="hidden" name="action" value="trash">
                                            </a>
                                            <a class="dropdown-item list_restore" type="button" href="javascript:{}">
                                                <i class="fas fa-undo-alt bg-primary p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px"></i>Khôi phục tài khoản
                                                <input type="hidden" name="action" value="trash">
                                            </a>
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


        })
    </script>
    <script type="text/javascript">
        // báo cáo sai
        $('.report_false').click(function () {
            var id = $(this).data('id');
            var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Báo cáo sai',
                text: "Sau khi xác nhận sẽ thay đổi trạng thái",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.comment.project.report-false', '')}}/" + id;
                }
            });
        })
        // chặn hiển thị
        $('.block_display').click(function () {
            var id = $(this).data('id');
            var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Chặn hiển thị dự án',
                text: "Sau khi xác nhận sẽ chặn hiển thị dự án",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.comment.project.block', '')}}/" + id;
                }
            });
        });
        // Khôi phục hiển thị
        $('.unblock_display').click(function () {
            var id = $(this).data('id');
            var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Chặn hiển thị dự án',
                text: "Sau khi xác nhận sẽ chặn hiển thị dự án",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.comment.project.unblock', '')}}/" + id;
                }
            });
        });
        // Chặn tài khoản
        $('.block_account').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Chặn tài khoản',
                text: "Tài khoản sẽ bị chặn 7 ngày",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.comment.project.block-account', '')}}/" + id;
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
                    window.location.href = "{{route('admin.comment.project.unblock-account', '')}}/" + id;
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
                    window.location.href = "{{route('admin.comment.project.forbidden', '')}}/" + id;
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
                    window.location.href = "{{route('admin.comment.project.unforbidden', '')}}/" + id;
                }
            });
        });
        // Xóa tài khoản
        $('.delete_account').click(function () {
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
                    window.location.href = "{{route('admin.comment.project.delete', '')}}/" + id;
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
                    window.location.href = "{{route('admin.comment.project.restore', '')}}/" + id;
                }
            });
        });

        // báo cáo sai hàng loạt
        $('.list_report_false').click(function () {
            Swal.fire({
                title: 'Báo cáo sai',
                text: "Xác nhận báo cáo sai",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("report_false");
                    $('#list_action').submit();
                }
            });
        });

        $('.list_block_display').click(function () {
            Swal.fire({
                title: 'Chặn hiển thị',
                text: "Sau khi đồng ý sẽ chặn hiển thị tin rao",
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
                    $('#list_action').submit();

                }
            });
        });
        // khôi phục hiển thị hàng loạt
        $('.list_unblock_display').click(function () {
            Swal.fire({
                title: 'Khôi phục hiển thị',
                text: "Sau khi đồng ý sẽ khôi phục hiển thị tin rao",
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
                    $('#list_action').submit();

                }
            });
        });
        // Chặn tài khoản
        $('.list_locked').click(function () {
            Swal.fire({
                title: 'Chặn tài khoản',
                text: "Sau khi đồng ý sẽ chặn tài khoản 7 ngày",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("block_account");
                    $('#list_action').submit();

                }
            });
        });
        // bỏ Chặn tài khoản
        $('.list_unlocked').click(function () {
            Swal.fire({
                title: 'Bỏ chặn tài khoản',
                text: "Sau khi đồng ý sẽ bỏ chặn tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("unblock_account");
                    $('#list_action').submit();

                }
            });
        });
        // Cấm tài khoản
        $('.list_forbidden').click(function () {
            Swal.fire({
                title: 'Cấm tài khoản',
                text: "Sau khi đồng ý sẽ cấm tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("forbidden");
                    $('#list_action').submit();

                }
            });
        });
        //bỏ Cấm tài khoản
        $('.list_unforbidden').click(function () {
            Swal.fire({
                title: 'Bỏ cấm tài khoản',
                text: "Sau khi đồng ý sẽ bỏ cấm tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("unforbidden");
                    $('#list_action').submit();

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
                    $('#action_method').val("delete");
                    $('#list_action').submit();

                }
            });
        });
        // khôi phục tài khoản
        $('.list_restore').click(function () {
            Swal.fire({
                title: 'Khôi phục tài khoản',
                text: "Sau khi đồng ý sẽ khôi phục tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("restore");
                    $('#list_action').submit();
                }
            });
        }); $('.create-noti').click(async function (){
            var id= $(this).data('id');
            const { value: formValues } = await Swal.fire({
                title: 'Tạo thông báo đến người dùng',
                html:
                    '<div class="row">'+
                    '<div class="col-12">'+
                    '<label class="swal2-input-label">Tiêu đề</label>' +
                    '<input id="mailbox-title" name="title" class="swal2-input">' +
                    ' </div>'+
                    '<div class="col-12">'+
                    '<label class="swal2-input-label">Nội dung</label>' +
                    '<textarea id="mailbox-content" style="width: 282px" name="content" class="swal2-textarea"></textarea>'+
                    ' </div>'+
                    ' </div>'
                ,
                focusConfirm: false,
                preConfirm: () => {
                    var title = document.getElementById('mailbox-title').value;
                    var content = document.getElementById('mailbox-content').value;
                    $.ajax({
                        url: "{{route('admin.project.comment.create_noti')}}",
                        type:"POST",
                        data:{
                            id : id,
                            mail_title:title,
                            mail_content:content,
                            _token: "{{csrf_token()}}"
                        },
                        success:function(response){
                            console.log(response);
                            if(response) {
                                toastr.success('Tạo thông báo thành công');
                            }
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON);
                        }
                    });
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

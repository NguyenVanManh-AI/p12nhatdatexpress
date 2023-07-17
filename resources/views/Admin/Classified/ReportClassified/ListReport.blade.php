@extends('Admin.Layouts.Master')
@section('Content')
    <section class="content">
        <div class="container-fluid">
            <!-- Filter -->
            <div class="filter block-dashed">
                <h3 class="title">Bộ lọc</h3>
                <form action="{{route('admin.reportclassified.list')}}" method="get" enctype="multipart/form" class="form-filter">
                    <div class="form-row">
                        <div class="col-md-3 form-group">
                            <select id="group_id" name="group_id" class="custom-select">
                                <option value="">Chuyên mục</option>
                                <option {{(isset($_GET['group_id'])&& $_GET['group_id']==2)?"selected":""}} value="2">Nhà đất bán</option>
                                <option {{(isset($_GET['group_id'])&& $_GET['group_id']==10)?"selected":""}} value="10">Nhà đất cho thuê</option>
                                <option {{(isset($_GET['group_id'])&& $_GET['group_id']==18)?"selected":""}} value="18">Cần mua - cần thuê</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <select id="group_child" name="group_child" class="custom-select">
                                <option value="">Mô hình</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col-md-4 form-group">
                                    <select name="content_report" class="custom-select">
                                        <option value="">Nội dung báo cáo</option>
                                        @foreach($content_report as $item)
                                        <option {{isset($_GET['content_report'])&&$_GET['content_report']==$item->id?"selected":""}} value="{{$item->id}}">{{$item->content}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <input type="date" name="start_day" value="{{isset($_GET['start_day'])&&$_GET['start_day']!=""?$_GET['start_day']:""}}" class="form-control start_day"  placeholder="Từ ngày" >
                                </div>
                                <div class="col-md-4 form-group">
                                    <input type="date" name="end_day" value="{{isset($_GET['end_day'])&&$_GET['end_day']!=""?$_GET['end_day']:""}}" class="form-control end_day" placeholder="Đến ngày" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3 form-group">
                            <select name="classified_status" class="custom-select">
                                <option value="">Tình trạng</option>
                                <option {{isset($_GET['classified_status'])&&$_GET['classified_status']=="1"?"selected":""}} value="1">Xóa tài khoản</option>
                                <option {{isset($_GET['classified_status'])&&$_GET['classified_status']=="2"?"selected":""}} value="2">Cấm tài khoản</option>
                                <option {{isset($_GET['classified_status'])&&$_GET['classified_status']=="3"?"selected":""}} value="3">Chặn tài khoản</option>
                                <option {{isset($_GET['classified_status'])&&$_GET['classified_status']=="4"?"selected":""}} value="4">Chặn hiển thị</option>
                                <option {{isset($_GET['classified_status'])&&$_GET['classified_status']=="5"?"selected":""}} value="5">Hiển thị</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <input type="text" name="classified_id" value="{{isset($_GET['classified_id'])&&$_GET['classified_id']!=""?$_GET['classified_id']:""}}" class="form-control" placeholder="Mã tin">
                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col-md-8 form-group">
                                    <input type="text" name="keyword" class="form-control" value="{{isset($_GET['keyword'])&&$_GET['keyword']!=""?$_GET['keyword']:""}}" placeholder="Nhập từ khóa (Tiêu đề, số điện thoại)">
                                </div>
                                <div class="col-md-4 form-group">
                                    <button class="btn bg-blue-light w-100"><i class="fas fa-search"></i> Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- / (Filter) -->
            <!-- Main row -->
            <table class="table">
                <thead>
                <tr>
                    <th scope="row" class="active">
                        <input type="checkbox" class="select-all checkbox" name="select-all" />
                    </th>
                    <th>STT</th>
                    <th>Tiêu đề</th>
                    <th style="min-width: 250px">Nội dung báo cáo</th>
                    <th>Thời gian</th>
                    <th class="dropdown" style="min-width: 190px">
                       Tình trạng
                    </th>
                    <th>Cài đặt</th>
                </tr>
                </thead>
                <tbody>
                <form action="{{route('admin.reportclassified.list_action')}}" id="trash_list" method="post">
                    @csrf
                    <input type="hidden" name="action" id="action_list" value="">
                    <input type="hidden" name="action_method" id="action_method" value="">
                @foreach($classified as $item)
                <tr>
                    <td>
                        <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}" />
                        <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->confirmed_by)}}" /></td>
                    </td>
                    <td>{{$item->id}}</td>
                    <td>
                        <h3 class="title mb-3 text-left"><a href="javascript:{}">{{$item->classified_name}}</a></h3>
                        <div class="text-left text-gray">
                            <p class="mb-0">Người đăng: {{$item->fullname}}</p>
                            <p class="mb-0">Mã tin: {{$item->id}}</p>
                        </div>
                    </td>
                    <td>
                        <div class="content-report">
                            <div class="list-report mb-2">
                                @if(isset($item->report))
                                    @foreach($item->report as $report)
                                <div class="item mb-2">{{$report->report_content}}<span class="text-gray">({{$report->count}})</span></div>
                                    @endforeach
                               @endif
                            </div>
                            @if($item->report->count()>3)
                            <span class="view-more">Hiện thêm <span>»</span></span>
                            @endif
                        </div>
                    </td>
                    <td>{{date('d/m/Y H:i',$item->report->first()->report_time)}}</td>
                    <td>
                        @if($item->user_deleted ==1)
                            Xóa tài khoản
                        @elseif($item->is_forbidden ==1)
                            Cấm tài khoản
                        @elseif($item->lock_time > time())
                            Chặn tài khoản
                         @elseif($item->is_show == 1)
                            Chặn hiển thị
                        @else
                            Hiển thị
                         @endif
                    </td>
                    <td>
                        @if($check_role == 1  ||key_exists(2, $check_role))
                            @if($item->is_show ==0)
                            <a href="javascript:{}" class="setting-item block_display block-display text-red mb-2" data-id="{{$item->id}}"><i class="fas fa-eye-slash"></i> Chặn hiển thị</a>
                            @endif
                        @endif
                        @if($check_role == 1  ||key_exists(2, $check_role))
                            @if($item->is_show == 1)
                            <a href="javascript:{}" class="setting-item unblock_display edit mb-2" data-id="{{$item->id}}"><i class="fas fa-sync-alt"></i> Khôi phục hiển thị</a>
                            @endif
                        @endif
                        @if($check_role == 1  ||key_exists(2, $check_role))
                            @if($item->lock_time < time())
                            <a href="javascript:{}" class="setting-item block locked text-red mb-2" data-id="{{$item->user_id}}"><i class="fas fa-lock"></i> Chặn tài khoản</a>
                            @endif
                        @endif
                        @if($check_role == 1  ||key_exists(2, $check_role))
                            @if($item->is_forbidden==0)
                            <a href="javascript:{}" class="setting-item forbidden ban text-red mb-2" data-id="{{$item->user_id}}"><i class="fas fa-ban"></i> Cấm tài khoản</a>
                            @endif
                        @endif
                         @if($check_role == 1  ||key_exists(2, $check_role))
                            @if($item->user_deleted==0)
                             <a href="javascript:{}" class="setting-item  delete delete_user text-red mb-2"  data-id="{{$item->user_id}}"><i class="fas fa-times"></i> Xóa tài khoản</a>
                            @endif
                        @endif
                        <a href="javascript:{}" class="setting-item create" data-toggle="modal" data-target="#modalCreateNotify"><i class="fas fa-bell"></i> Tạo thông báo</a>
                    </td>
                </tr>
                @endforeach
                </form>
                </tbody>
            </table>
            <div class="table-bottom d-flex align-items-center justify-content-between mb-4">
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
                                    <a class="dropdown-item list_locked" type="button" href="javascript:{}">
                                        <i class="fas fa-lock bg-red p-1 mr-2 rounded"
                                           style="color: white !important;font-size: 15px"></i>Chặn tài khoản
                                        <input type="hidden" name="action" value="trash">
                                    </a>
                                <a class="dropdown-item list_forbidden" type="button" href="javascript:{}">
                                    <i class="fas fa-ban  bg-red p-1 mr-2 rounded"
                                       style="color: white !important;font-size: 15px"></i>Cấm tài khoản
                                    <input type="hidden" name="action" value="trash">
                                </a>
                                <a class="dropdown-item list_deleted" type="button" href="javascript:{}">
                                    <i class="fas fa-times  bg-red p-1 mr-2 rounded"
                                       style="color: white !important;font-size: 15px"></i>Xóa tài khoản
                                    <input type="hidden" name="action" value="trash">
                                </a>
                                @else
                                    <p class="text-center">Không đủ quyền</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="display d-flex align-items-center mr-4">
                        <span>Hiển thị:</span>
                        <form method="get" id="paginateform" action="{{route('admin.reportclassified.list')}}">
                            <select class="custom-select" id="paginateNumber" name="items" >
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
                </div>
                <div class="text-right d-flex">
                    <div class="count-item">Tổng cộng: {{$classified->total()}} items</div>
                    @if($classified)
                        {{ $classified->render('Admin.Layouts.Pagination') }}
                    @endif
                </div>
            </div>            <!-- /Main row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('Style')
    <style>
        .content-wrapper>.content {
            padding-top: 25px;
        }
        .content-wrapper>.content {
            padding: 0 0.5rem;
        }
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
        .content-wrapper>.content .filter .form-row {
            margin-right: -15px;
            margin-left: -15px;
        }
        .content-wrapper>.content .filter .form-row>.col, .content-wrapper>.content .filter .form-row>[class*=col-] {
            padding-left: 15px;
            padding-right: 15px;
        }
        .btn:not(:disabled):not(.disabled) {
            cursor: pointer;
        }
        .form-filter button {
            background-color: #00aeef;
            color: #fff;
            text-align: center;
            margin-right: 20px;
            letter-spacing: 0.4px;
            font-weight: 700;
            border-radius: unset;
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
        .table th .nav-link {
            color: #fff;
        }
        .dropdown-table .nav-link {
            position: relative;
        }
        .dropdown-table .nav-link::after {
            content: '\f0d7';
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
        }
        .content .table th, .content .table td {
            border: 1px solid #b7b7b7;
            text-align: center;
            font-size: 14px;
            color: #0d0d0d;
            vertical-align: middle;
            position: relative;
        }
        .bg-orange-light {
            background-color: #fdc689 !important;
            color: #fff;
        }
        .custom-select, .form-control {
            font-size: 14px;
        }
        .bg-green-light {
            background-color: #70c97c !important;
            color: #fff;
        }
        .bg-gray-medium {
            background-color: #b6c2cb !important;
            color: #fff;
        }
        .bg-red-light {
            background-color: #f98383 !important;
            color: #fff;
        }
        .table td .title a {
            color: #21337f;
            text-transform: uppercase;
            font-size: 1rem;
            font-weight: 500;
        }
        .table td .code-title {
            color: #0073cc;
            font-size: 12px;
            font-weight: 500;
        }
        .table td ul.info {
            list-style: none;
            margin-bottom: 0;
            padding-left: 0;
        }
        .table td ul.info li {
            font-size: 12px;
            color: #4a4a4a;
            margin-bottom: 5px;
        }
        .table td ul.info li i {
            width: 18px;
            margin-right: 5px;
        }
        .table td .info-desc {
            max-width: 400px;
            margin: auto;
            color: #1b1b1b;
            height: 105px;

        }
        .info-maxline{
            /*max line*/
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp:5; /* number of lines to show */
            line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .table td .list-image {
            width: 120px;
            margin: auto;
        }
        .table td .list-image .image-large {
            margin-bottom: 2px;
        }
        .table td .list-image img {
            width: 100%;
        }
        .table td .list-image .image-small .item:first-child {
            padding-right: 1px;
        }

        .table td .list-image .image-small .item {
            flex: 0 0 50%;
            max-width: 50%;
        }
        .table td .info-desc {
            max-width: 400px;
            margin: auto;
            color: #1b1b1b;
            height: 105px;
        }
        .table td .view-more {
            text-decoration: underline;
            /*float: right;*/
        }

        .table td .view-more {
            cursor: pointer;
        }
        .text-blue-light {
            color: #00bff3 !important;
        }
        .content .table td .setting-item.delete {
            color: #ff0000;
        }
        .content .table td .setting-item {
            display: block;
            color: #0090ff;
            font-size: 14px;
            text-align: left;
            min-width: 150px;
        }
        .content .table td .setting-item .count {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background-color: #ff0000;
            color: #fff;
            font-size: 12px;
            margin-left: 5px;
            float: right;
        }

        .table td .list-image .image-small .item:last-child {
            padding-left: 1px;
        }
        .table td .list-image .image-small .item {
            flex: 0 0 50%;
            max-width: 50%;
        }
        .table td .list-image .image-small {
            display: flex;
            align-items: center;
        }
        .manipulation .custom-select {
            background-color: #347ab6;
            color: #fff;
        }

        .table-bottom .custom-select {
            height: 32px;
        }
        .custom-select, .form-control {
            font-size: 14px;
        }
        .table-bottom .display {
            width: 125px;
        }
        .table-bottom .display span {
            flex: 0 0 60px;
            max-width: 60px;
            font-size: 14px;
        }
        .table-bottom .view-trash a {
            font-size: 1rem;
            color: #0076c1;
            text-decoration: underline;
        }
        .table-bottom .view-trash a i {
            color: #000;
            margin-right: 10px;
        }
        .table-bottom .count-trash {
            display: inline-block;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background-color: #ff0000;
            color: #fff;
            font-size: 11px;
            text-align: center;
            line-height: 15px;
            margin-left: 5px;
        }
        .table-bottom .count-item {
            background-color: #eeeeee;
            font-size: 14px;
            padding: 7px 10px;
            border: 1px solid #dddddd;
            border-radius: 3px 0 0 3px;
            color: #004d79;
            height: 38px;
        }
        .content .table th, .content .table td {
            border: 1px solid #b7b7b7;
            text-align: center;
            font-size: 14px;
            color: #0d0d0d;
            vertical-align: middle;
            position: relative;
        }
        .table td .content-report .list-report {
            height: 100px;
            overflow: hidden;
        }
        .table td .content-report .list-report .item {
            border: 1px solid #7cc576;
            background-color: #ffffff;
            border-radius: 6px;
            padding: 5px 10px;
            line-height: 1;
        }
        .table td .content-report .view-more {
            color: #00b4ff;
        }

        .table td .view-more {
            cursor: pointer;
        }
        .table td .content-report.show .view-more {
            color: #afafaf;
        }
        .table td .content-report .view-more>span {
            display: inline-block;
            transform: rotate(
                90deg
            );
            font-size: 16px;
        }
        .table td .content-report.show .view-more span {
            transform: rotate(
                270deg
            );
        }
        .table td .content-report.show .list-report {
            height: auto;
        }
        .table tr.active {
            background-color: #eefff2;
        }
    </style>
@endsection
@section('Script')
    <script src="js/table.js" type="text/javascript"></script>
    <script>
        // Chặn hiển thị
        $('.block_display').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Chặn hiển thị',
                text: "Sau khi xác nhận sẽ tiến hành chặn hiển thị tin rao",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/classified-report/block-display/" + id;

                }
            });
        });
        // Khôi phục hiển thị
        $('.unblock_display').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Khôi phục hiển thị',
                text: "Sau khi xác nhận sẽ tiến hành khôi phục hiển thị tin rao",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/classified-report/unblock-display/" + id;

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
                    window.location.href = "/admin/classified-report/forbidden/" + id;

                }
            });
        });
        // Chặn tài khoản
        $('.locked').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Chặn tài khoản',
                text: "Sau khi xác nhận sẽ tiến hành chặn trong 7 ngày",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/classified-report/locked/" + id;

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
                    window.location.href = "/admin/classified-report/delete-user/" + id;

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
                    $('#trash_list').submit();

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
                    $('#action_method').val("list_forbidden");
                    $('#trash_list').submit();

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
    </script>
    <script>
        $(".view-more").on("click", function() {
            $(this).parent(".content-report").toggleClass("show");
            if ($(this).parent(".content-report").hasClass("show")) {
                $(this).html("Thu gọn <span>&#187;</span>");
            } else {
                $(this).html("Hiện thêm <span>&#187;</span>");
            }
        });
    </script>
{{--    Select mô hình phần tìm kiếm--}}
    <script>
        $('#group_id').change(function (){
            if($('#group_id').val()!=""){
                var url = "{{route('param.get_child')}}";
                var group_id = $('#group_id').val();
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    data: {
                        group_id: group_id
                    },
                    success: function (data) {
                        $('#group_child').html('');
                        console.log(data['group_child']);
                        $('#group_child').append(data['group_child']);
                    }
                });
            }
            else{
                $('#group_child').html('<option selected disabled>Mô hình</option>');
            }
        });
        @if(isset($_GET['group_id'])&& $_GET['group_id']!="")
        var url = "{{route('param.get_child')}}";
        var group_id =  {{$_GET['group_id']}};
        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            data: {
                group_id: group_id
            },
            success: function (data) {
                $('#group_child').html('');
                console.log(data['group_child']);
                $('#group_child').append(data['group_child']);
                @if(isset($_GET['group_child'])&& $_GET['group_child']!="")
                $('#group_child').val("{{$_GET['group_child']}}").change();
                @endif
            }
        });
        @endif
    </script>

    <script>
        // phân trang và validate day
        $('#paginateNumber').change(function (e) {
            $('#paginateform').submit();
        });
        $(document).ready(function(){
            @if(isset($_GET['start_day'])&&$_GET['start_day']!="" )
            $('.end_day').attr('min','{{$_GET['start_day']}}');
            @endif
            @if(isset($_GET['end_day'])&&$_GET['end_day']!="" )
            $('.start_day').attr('max','{{$_GET['end_day']}}');
            @endif
            $('.start_day').change(function (){
                $('.end_day').attr('min',$('.start_day').val());
            });
            $('.end_day').change(function (){
                $('.start_day').attr('max',$('.end_day').val());
            });
        });
    </script>
@endsection

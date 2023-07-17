@extends('Admin.Layouts.Master')
@section('Title', 'Báo cáo | Nội dung dự án')
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
    <section class="content">
        <div class="container-fluid">
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
                    <th scope="col" style="width: 16%;min-width: 170px">Cài đặt</th>
                </tr>
                </thead>
                <tbody>
                <form action="{{route('admin.report.project.list_action')}}" id="list_action" method="post">
                    @csrf
                    <input type="hidden" name="action" id="action_list" value="">
                    <input type="hidden" name="action_method" id="action_method" value="">
                    @foreach($list as $item)
                        <tr>
                            <td>
                                <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}" />
                                <input type="hidden" class="checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->confirmed_by)}}" /></td>
                            </td>
                            <td>{{$item->id}}</td>
                            <td>
                                <h3 class="title mb-3 text-left"><a href="javascript:{}">{{$item->project_name}}</a></h3>
                            </td>
                            <td>
                                <div class="content-report">
                                    <div class="list-report mb-2">
                                            @foreach($item->report_project->groupBy('report_type') as $i)
                                                <div class="item mb-2">{{$i[0]->report_group->content}}<span class="text-gray">({{count($i)}})</span></div>
                                            @endforeach
                                    </div>
                                    @if($item->report_project->groupBy('report_type')->count()>3)
                                        <span class="view-more">Hiện thêm <span>»</span></span>
                                    @endif
                                </div>
                            </td>
                            <td>{{date('d/m/Y H:i',$item->report_project->first()->report_time)}}</td>
                            <td>
                                @if($item->is_deleted == 1)
                                    <p class="text-danger">Dự án bị xóa</p>
                                @elseif($item->is_show == 0)
                                  <p class="text-danger">Chặn hiển thị</p>
                                @else
                                    <p class="text-success">Hiển thị</p>
                                @endif
                            </td>
                            <td>
                                @if($check_role == 1  ||key_exists(2, $check_role))
                                <div class="flex-column ">
                                    @if($item->is_show ==1||$item->is_deleted ==0)
                                    <div class="mb-1 text-left">
                                        <i class="icon-setup fa fa-history mr-1 "></i>
                                        <a class="text-primary report_fail" data-id="{{$item->id}}" data-created_by="" style="cursor:pointer">Báo cáo sai</a>
                                    </div>
                                    @endif
                                    @if($item->is_show ==1)
                                    <div class=" mb-1 text-left">
                                        <i class="icon-setup fas fa-eye-slash mr-1"></i>
                                        <a href="javascript:{}" class=" block_display block-display text-red " data-id="{{$item->id}}"> Chặn hiển thị</a>
                                    </div>
                                    @endif
                                    @if($item->is_show == 0)
                                    <div class="mb-1 text-left">
                                        <i style="color: black" class="icon-setup fas fa-sync-alt mr-1"></i>
                                        <a href="javascript:{}" class="unblock_display " data-id="{{$item->id}}"> Khôi phục hiển thị</a>
                                    </div>
                                    @endif
                                   @if($item->is_deleted == 0)
                                    <div class="mb-1 text-left">
                                        <i style="color: black" class="fa fa-times ml-1"></i>
                                        <a href="javascript:{}" class=" delete_project text-red ml-3" data-id="{{$item->id}}"> Xóa dự án</a>
                                    </div>
                                        @else
                                            <div class="mb-1 text-left">
                                                <i style="color: black" class="fa fa-undo-alt"></i>
                                                <a href="javascript:{}" class="restore_project  text-primary ml-3" data-id="{{$item->id}}"> Khôi phục dự án</a>
                                            </div>
                                        @endif
                                </div>
                                @endif
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
                                <a class="dropdown-item list_deleted" type="button" href="javascript:{}">
                                    <i class="fas fa-times  bg-red p-1 mr-2 rounded"
                                       style="color: white !important;font-size: 15px"></i>Xóa dự án
                                    <input type="hidden" name="action" value="trash">
                                </a>
                                <a class="dropdown-item list_restore" type="button" href="javascript:{}">
                                    <i class="fas fa-undo-alt bg-primary p-1 mr-2 rounded"
                                       style="color: white !important;font-size: 15px"></i>Khôi phục dự án
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
                        <form method="get" id="paginateform" action="{{route('admin.report.project.list')}}">
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
                    <div class="count-item">Tổng cộng: {{$list->total()}} items</div>
                    @if($list)
                        {{ $list->render('Admin.Layouts.Pagination') }}
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
    <script src="{{asset('system/js/table.js')}}" type="text/javascript"></script>
    <script>
        // Chặn hiển thị
        $('.block_display').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Chặn hiển thị',
                text: "Sau khi xác nhận sẽ tiến hành chặn hiển thị dự án",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/report/project/block/" + id;

                }
            });
        });
        // Khôi phục hiển thị
        $('.unblock_display').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Khôi phục hiển thị',
                text: "Sau khi xác nhận sẽ tiến hành khôi phục hiển thị dự án",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/report/project/unblock/" + id;

                }
            });
        });
        // Báo cáo sai
        $('.report_fail').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
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
                    window.location.href = "/admin/report/project/report-false/" + id;
                }
            });
        });
        // Xóa dự án
        $('.delete_project').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Xóa dự án',
                text: "Sau khi xóa thì không thể hoàn tác",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/report/project/delete/" + id;
                }
            });
        });
        // Khôi phục dự án
        $('.restore_project').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Khôi phục dự án',
                text: "Sau khi đồng ý sẽ tiến hành khôi phục",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/report/project/restore/" + id;
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

        // Xóa tài khoản
        $('.list_deleted').click(function () {
            Swal.fire({
                title: 'Xóa dự án',
                text: "Sau khi đồng ý sẽ xóa dự án",
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
                title: 'Khôi phục dự án',
                text: "Sau khi đồng ý sẽ khôi phục dự án",
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

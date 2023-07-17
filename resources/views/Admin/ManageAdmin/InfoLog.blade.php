@extends('Admin.Layouts.Master')

@section('Title', 'Thông tin tài khoản | Tài khoản quản trị')

@section('Content')
    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="user-info text-center my-4">
                <div class="avatar mb-4">
                    <img src="{{asset("/system/img/avatar-admin/$admin->image_url")}}" alt="" id="imgAvatar" class="elevation-2">
                    <form action="{{route('admin.manage.updateavatar', [$admin->id, \Crypt::encryptString($admin->created_by)])}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="is_reload" value="1">
                        <div class="edit-avatar upload-avatar">
                            <i class="fas fa-pencil-alt" style="cursor: pointer"></i>
                            <input type="file" name="file" id="inputAvatar" accept="image/*">
                        </div>
                    </form>
                </div>
                <div class="user-name mb-2">{{$admin->admin_fullname}}</div>
                <div class="user-role mb-2">
                    {{ $admin->admin_type == 1 ? "Quản trị cao cấp" : $admin->role_name }}
                </div>
                <div class="date-join">Ngày tham gia: <span class="text-green">{{\Carbon\Carbon::parse($admin->created_at)->format('d/m/Y')}}</span></div>
            </div>

            <h3 class="table-title mt-3 font-weight-bold">Danh sách hoạt động</h3>

            <table class="table">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Thời gian</th>
                    <th>Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @forelse($logs as $key => $item)
                    <tr>
                        <td> {{ ($logs->currentpage()-1) * $logs->perpage() + $key + 1 }}</td>
                        <td width="300px">{{getHumanTimeWithPeriod($item->log_time)}}</td>
                        <td>{{$item->log_content }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Chưa có dữ liệu</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="table-bottom d-flex align-items-center justify-content-between mb-4">
                <div class="text-left d-flex align-items-center">
                    <div class="manipulation d-flex mr-4">
                        <img src="image/manipulation.png" alt="" id="btnTop">
                    </div>

                    <div class="display d-flex align-items-center mr-4">
                        <span>Hiển thị:</span>
                        <form method="get" action="">
                            <select name="items" class="custom-select" onchange="this.form.submit()">
                                <option {{(isset($_GET['items'])&& $_GET['items']==10)?"selected":""}}  class=""
                                        value="10">10
                                </option>
                                <option {{(isset($_GET['items'])&& $_GET['items']==20)?"selected":""}} value="20">20
                                </option>
                                <option {{(isset($_GET['items'])&& $_GET['items']==30)?"selected":""}} value="30">30
                                </option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="text-right d-flex">
                    <div class="count-item">Tổng cộng: @empty($logs) {{0}} @else {{$logs->total()}} @endempty items</div>
                    @if($logs)
                        {{ $logs->render('Admin.Layouts.Pagination') }}
                    @endif
                </div>
            </div>

            <section>
                <h5 class="mt-4 mb-2">
                    Lịch sử 
                </h5>

                <div class="table table-responsive table-bordered table-hover">
                    <table class="table">
                        <thead class="thead-main">
                            <tr>
                                <th>#</th>
                                <th>Bảng</th>
                                <th>ID bảng</th>
                                <th>Thuộc tính</th>
                                <th>Thao tác</th>
                                {{-- <th>Thực hiện bởi</th> --}}
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histories as $history)
                                <tr>
                                    <td>
                                        {{ ($histories->currentPage() - 1) * $histories->perPage() + $loop->index + 1 }}
                                    </td>
                                    <td>
                                        {{ $history->historyable()->getRelated()->getTable() }}
                                        {{-- {{ app($history->historyable_type)->getTable() }} --}}
                                    </td>
                                    <td>
                                        {{ $history->historyable_id }}
                                    </td>
                                    <td>
                                        @foreach ($history->attributes as $column => $value)
                                            @if($loop->index < 2)
                                                <p>
                                                    <strong>
                                                        {{ $column }}:
                                                    </strong>
                                                    {!! $value !!}
                                                </p>
                                            @elseif($loop->index == 2)
                                                ...
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $history->getActionLabel() }}
                                    </td>
                                    {{-- <td>
                                        @if($history->admin)
                                            <p>
                                                <strong>
                                                    Admin:
                                                </strong>
                                                {{ data_get($history->admin, 'admin_fullname') }}
                                            </p>
                                        @endif
                                        @if($history->user)
                                            <p>
                                                <strong>
                                                    User:
                                                </strong>
                                                {{ data_get($history->user, 'detail.fullname') }}
                                            </p>
                                        @endif
                                    </td> --}}
                                    <td>
                                        {{ $history->created_at ? $history->created_at->format('d-m-Y H:i') : '' }}
                                    </td>
                                    <td>
                                        <div class="flex-end">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-info mb-2 mr-2"
                                                data-toggle="modal"
                                                data-target="#manage-admin-history-attributes-{{ $history->id }}"
                                                title="Xem thuộc tính">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @include('Admin.ManageAdmin.partials._log-attributes', [
                                            'history' => $history  
                                        ])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- /Main row -->
                <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
                    <div class="text-left d-flex align-items-center">
                        <div class="manipulation d-flex mr-4">
                            <a href="javascript:void(0);" class="mr-2">
                            <img src="{{ asset('/system/image/manipulation.png') }}" class="js-go-to-top cursor-pointer py-1"
                                title="Về đầu trang" alt="">
                            </a>
                        </div>
                        <div class="display d-flex align-items-center mr-4">
                            <span class="mr-2">Hiển thị:</span>
                            <form method="get" id="paginateform" action="">
                                <select class="custom-select" id="paginateNumber" name="histories_items" onchange="this.form.submit()">
                                    <option @if (request()->histories_items == 10) {{ 'selected' }} @endif value="10">
                                        10
                                    </option>
                                    <option @if (request()->histories_items == 20) {{ 'selected' }} @endif value="20">
                                        20
                                    </option>
                                    <option @if (request()->histories_items == 30) {{ 'selected' }} @endif value="30">
                                        30
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="count-item">Tổng cộng: {{ $histories->total() }} mục</div>
                        @if ($histories)
                            {{ $histories->render('Admin.Layouts.Pagination') }}
                        @endif
                    </div>
                </div>
            </section>
        </div><!-- /.container-fluid -->
    </section>

@endsection

@section('Style')
    <style>
        .content .user-info .avatar {
            position: relative;
            display: inline-block;
        }
        .content .user-info .avatar img {
            width: 125px;
            height: 125px;
            border-radius: 50%;
            object-fit:cover; /*this property does the magic*/
            border: 5px solid #e9f2f4;
        }
        .content .user-info .avatar .edit-avatar {
            position: absolute;
            top: 0;
            right: 0;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
            text-align: center;
            background-color: #e9ebf2;
            border: 1px solid #d7d7d7;
            color: #70747f;
            cursor: pointer;
        }
        .content .user-info .user-name {
            font-size: 24px;
            color: #0d0d0d;
            line-height: 1;
            font-weight: 700;
        }
        .content .user-info .date-join, .content .user-info .user-role {
            font-size: 18px;
            color: #676767;
            line-height: 1;
        }
        .content .user-info .date-join, .content .user-info .user-role {
            font-size: 18px;
            color: #676767;
            line-height: 1;
        }
        .small-box {
            background-color: #eeeff4;
        }
        .small-box {
            border-radius: 0.25rem;
            box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%);
            display: block;
            margin-bottom: 20px;
            position: relative;
        }
        .content .bg-black {
            background-color: #282828 !important;
        }
        .small-box>.small-box-footer {
            background-color: #c3c3cf;
            color: #000;
            margin-bottom: 0;
            font-weight: 500;
            height: 40px;
            line-height: 40px;
        }
        .content .bg-blue-blue {
            background-color: #246fb2 !important;
            color: #fff !important;
        }
        .small-box>.inner {
            height: 95px;
            padding-top: 25px;
            position: relative;
        }

        .small-box>.inner {
            padding: 10px;
        }
        .small-box>.inner h3.large {
            font-size: 2.25rem;
        }
        .small-box>.inner h3 {
            text-align: center;
            font-size: 1.625rem;
        }
        .analytics{
            position: relative;
        }
        .analytics i.fa-sort-down{
            position: absolute;
            /*top: 2px;*/
            top: 0;
            left: 0;
        }
        .analytics i.fa-sort-up{
            position: absolute;
            /*top: 9px;*/
            top: 25%;
            left: 0;
        }
        .small-box>.inner p.sort-down a {
            color: var(--red);
            padding-left: 12px;
        }
        .small-box>.inner p.sort-up a {
            color: var(--green);
            padding-left: 12px;
        }
        .table-title {
            text-align: center;
            text-transform: uppercase;
            color: #282828;
            font-size: 20px;
            font-weight: 500;
            line-height: 24px;
            margin-bottom: 15px;
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
        .table .dropdown .nav-link::after {
            content: '\f0d7';
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
        }
        .table th .nav-link {
            color: #fff;
        }
        .table .dropdown .nav-link {
            position: relative;
        }
        .table .table td .title {
            display: block;
            text-align: left;
        }
        .table td a.title {
            color: #21337f;
            font-size: 1rem;
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

        .upload-avatar {
            position: relative;
            cursor: pointer;
        }
        .upload-avatar input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /*Add is-line to wrapper to support a read more inline with the content.*/
        .read-more.is-inline,
        .read-more.is-inline p,
        .read-more.is-inline + span  {
            display: inline;
        }

        .read-more.is-inline + span {
            margin-left: 0.25em
        }

        /*Time line*/
        .message-item {
            margin-bottom: 25px;
            margin-left: 40px;
            position: relative;
        }
        .message-item .message-inner {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 3px;
            padding: 10px;
            position: relative;
        }
        .message-item .message-inner:before {
            border-right: 10px solid #ddd;
            border-style: solid;
            border-width: 10px;
            color: rgba(0,0,0,0);
            content: "";
            display: block;
            height: 0;
            position: absolute;
            left: -20px;
            top: 6px;
            width: 0;
        }
        .message-item .message-inner:after {
            border-right: 10px solid #fff;
            border-style: solid;
            border-width: 10px;
            color: rgba(0,0,0,0);
            content: "";
            display: block;
            height: 0;
            position: absolute;
            left: -18px;
            top: 6px;
            width: 0;
        }
        .message-item:before {
            background: #fff;
            border-radius: 2px;
            bottom: -30px;
            box-shadow: 0 0 3px rgba(0,0,0,0.2);
            content: "";
            height: 100%;
            left: -30px;
            position: absolute;
            width: 3px;
        }
        .message-item:after {
            background: #fff;
            border: 2px solid #ccc;
            border-radius: 50%;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            content: "";
            height: 15px;
            left: -36px;
            position: absolute;
            top: 10px;
            width: 15px;
        }
        .clearfix:before, .clearfix:after {
            content: " ";
            display: table;
        }
        .message-item .message-head {
            border-bottom: 1px solid #eee;
            margin-bottom: 8px;
            padding-bottom: 8px;
        }
        .message-item .message-head .avatar {
            margin-right: 20px;
        }
        .message-item .message-head .user-detail {
            overflow: hidden;
        }
        .message-item .message-head .user-detail h5 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }
        .message-item .message-head .post-meta {
            float: left;
            padding: 0 15px 0 0;
        }
        .message-item .message-head .post-meta >div {
            color: #333;
            font-weight: bold;
            text-align: right;
        }
        .post-meta > div {
            color: #777;
            font-size: 12px;
            line-height: 22px;
        }
        .message-item .message-head .post-meta >div {
            color: #333;
            font-weight: bold;
            text-align: right;
        }
        .post-meta > div {
            color: #777;
            font-size: 12px;
            line-height: 22px;
        }
        .modal-detail img {
            min-height: 40px;
            max-height: 40px;
            border-radius: 50%;
            width: 40px;
            object-fit: cover;
        }
    </style>
@endsection

@section('Script')
    <script src="js/readmore.js"></script>
    <script>
        $(document).ready( () => {
            $('.read-more__link').hide()

            const inputAvatar = document.getElementById('inputAvatar')
            const previewAvatar = document.getElementById('imgAvatar')
            inputAvatar.onchange = evt => {
                const [file] = inputAvatar.files
                if (file) {
                    previewAvatar.src = URL.createObjectURL(file)
                }

                $(evt.currentTarget).parents('form').submit()
            }
        })
    </script>
@endsection

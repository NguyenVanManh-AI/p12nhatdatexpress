@extends('Admin.Layouts.Master')
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
                <div class="user-role mb-2">Chăm sóc khách hàng</div>
                <div class="date-join">Ngày tham gia: <span class="text-green">{{\Carbon\Carbon::parse($admin->created_at)->format('d/m/Y')}}</span></div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="small-box">
                        <p class="small-box-footer bg-black font-weight-bold">Tổng hội thoại</p>
                        <div class="inner bg-blue-blue">
                            <h3 class="large">{{$history->total()}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box">
                        <p class="small-box-footer font-weight-bold">Hội thoại trong tháng</p>
                        <div class="inner">
                            <h3>{{$conversion_of_month_count}}</h3>
                            <div class="d-flex align-items-center analytics">
                                <p @class(['m-0', 'sort-up' => $percent_month > 0, 'sort-down' => $percent_month <= 0])>
                                    <a href="javascript:{}"><i @class(['fas', 'icon-custom', 'fa-sort-up' => $percent_month > 0 , 'fa-sort-down' => $percent_month <= 0])></i>
                                        {{$percent_month > 0 ? "Tăng" : "Giảm"}} {{abs($percent_month)}}% </a> so với tháng trước</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box">
                        <p class="small-box-footer font-weight-bold">Hội thoại trong tuần</p>
                        <div class="inner">
                            <h3>{{$conversion_of_week_count}}</h3>
                            <div class="d-flex align-items-center analytics">
                                <p @class(['m-0', 'sort-up' => $percent_week > 0, 'sort-down' => $percent_week <= 0])>
                                    <a href="javascript:{}"><i @class(['fas', 'fa-sort-up' => $percent_week > 0 , 'fa-sort-down' => $percent_week <= 0])></i>
                                        {{$percent_week > 0 ? "Tăng" : "Giảm"}} {{abs($percent_week)}}% </a> so với tuần trước
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="table-title mt-3 font-weight-bold">Danh sách hội thoại</h3>

            <table class="table">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên tài khoản</th>
                    <th>Thời gian tạo</th>
                    <th>Phản hồi</th>
                    <th>Nội dung</th>
                    <th>Đánh giá</th>
                </tr>
                </thead>
                <tbody>
                @foreach($history as $item)
                    <tr>
                        <td>{{$item['id']}}</td>
                        <td><a href="#" class="title">{{$item['user_detail']['fullname']}}</a></td>
                        <td>
                            <span>{{date('H:i', $item['created_at'])}}</span>
                            <p>{{date('d/m/Y', $item['created_at'])}}</p>
                        </td>
                        <td>
                            @if($item['respontime'] != null)
                                <span>{{date('H:i', $item['respontime'])}}</span>
                                <p>{{date('d/m/Y', $item['respontime'])}}</p>
                            @else
                                <p>Chưa phản hồi</p>
                            @endif
                        </td>
                        <td width="40%">
                            <div class="readmore js-read-more" data-rm-words="10" data-readmore="Xem hội thoại" >
                                @foreach($item['chat_message'] as $message)
                                    {{$message['chat_message'] }} <br />
{{--                                    @break($loop->index == 2)--}}
                                @endforeach
                            </div>
                            <a href="#" data-toggle="modal" data-target="#detail-conversion-{{$item['id']}}">Xem hội thoại</a>
{{--@dd($history);--}}
                            <!-- Modal -->
                            <div class="modal fade" id="detail-conversion-{{$item['id']}}" tabindex="-1" role="dialog" aria-labelledby="detail-conversion-title" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết hội thoại</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="container modal-detail">
                                                <div class="qa-message-list" id="wallmessages">

                                                    @foreach($item['chat_message'] as $message)

                                                    <div class="message-item" id="m16">
                                                        <div class="message-inner">
                                                            <div class="message-head clearfix">
                                                                <div class="avatar pull-left">
                                                                    <a href="javascript:{}">
                                                                        <img src="{{$message['type'] == 1 ? asset("/system/img/avatar-admin/$admin->image_url") : asset($item['user_detail']['image_url'])}}">
                                                                    </a>
                                                                </div>
                                                                <div class="user-detail">
{{--                                                                    <h5 class="handle">{{$admin->admin_fullname}}</h5>--}}
                                                                    <div class="post-meta">
                                                                        <div class="asker-meta">
                                                                            <span class="qa-message-what"></span>
                                                                            <span class="qa-message-when">
												                                <span class="qa-message-when-data">{{$message['chat_time']}}</span>
											                                </span>
                                                                            <span class="qa-message-who">
                                                                                <span class="qa-message-who-pad">gửi từ </span>
                                                                                <span class="qa-message-who-data">
                                                                                    @if($message['type'] == 1)
                                                                                        <a href="javascript:{}">{{$admin->admin_fullname}}</a>
                                                                                    @else
                                                                                        <a href="javascript:{}">{{$item['user_detail']['fullname']}}</a>
                                                                                    @endif
                                                                                </span>
											                                </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="qa-message-content">
                                                                {{$message['chat_message']}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @endforeach

                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                        <td>
                            <span>
                                @for($i = 1; $i <= $item['rating']; $i++)
                                <span class="fa fa-star text-orange"></span>
                                @endfor
                                @for($i = 5 -  $item['rating']; $i > 0; $i--)
                                    <span class="far fa-star text-gray"></span>
                                @endfor
                            </span>
                        </td>
                    </tr>
                @endforeach
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

{{--                    <div class="view-trash">--}}
{{--                        <a href="#"><i class="far fa-trash-alt"></i> Xem thùng rác</a>--}}
{{--                        <span class="count-trash">1</span>--}}
{{--                    </div>--}}
                </div>

                <div class="text-right d-flex">
                    <div class="count-item">Tổng cộng: @empty($history) {{0}} @else {{$history->total()}} @endempty items</div>
                    @if($history)
                        {{ $history->render('Admin.Layouts.Pagination') }}
                    @endif
                </div>
            </div>            <!-- /Main row -->
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

        .modal{
            left: 100px
        }
        .modal-dialog{
            min-width: 80vw;
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

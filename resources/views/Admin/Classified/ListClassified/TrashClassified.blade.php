@extends('Admin.Layouts.Master')
@section('Title', 'Thùng rác | Tin rao')
@section('Content')
    <div class="row m-0 p-3">
        <ol class="breadcrumb">
            @if($check_role == 1  ||key_exists(4, $check_role))
                <li class="add px-2 pt-1">
                    <a href="{{route('admin.classified.list')}}">
                        <i class="fa fa-th-list mr-1"></i>Danh sách
                    </a>
                </li>
            @endif
        </ol>
    </div>
    <section class="content">
        <div class="container-fluid">
            <h4 class="text-bold text-center m-4">QUẢN LÝ THÙNG RÁC</h4>

            <table class="table">
                <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Tình trạng</th>
                    <th>Hình ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Nội dung</th>
                    <th>Cài đặt</th>
                </tr>
                </thead>
                <tbody>
                <form action="{{route('admin.classified.trash_list')}}" id="trash_list" method="post">
                    @csrf
                    <input type="hidden" name="action" id="action_list" value="">
                    @foreach($classified as $item)

                        <tr>
                            <td><input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}" />
                                <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->confirmed_by)}}" /></td>
                            <td class="
                        @if($item->confirmed_status == 0)
                            {{"bg-orange-light"}}
                            @endif
                            @if($item->confirmed_status == 1)
                            {{"bg-green-light"}}
                            @endif
                            @if($item->confirmed_status == 2)
                            {{"bg-red-light"}}
                            @endif
                            @if($item->expired_date < time())
                            {{"bg-gray-medium"}}
                            @endif
                                ">
                                @if($check_role == 1  ||key_exists(2, $check_role))
                                    <select style="width: 130px" data-id="{{$item->id}}" data-created_by="{{Crypt::encryptString($item->confirmed_by)}}" data-status="{{$item->confirmed_status}}"  class="custom-select change_status_classified">
                                        <option disabled {{($item->confirmed_status == 0)?"selected":""}} {{($item->expired_date < time())?"disabled":""}} value="0">Chờ duyệt</option>
                                        <option disabled {{($item->confirmed_status == 1)?"selected":""}} {{($item->expired_date < time())?"disabled":""}} value="1">Đã duyệt</option>
                                        <option disabled {{($item->expired_date < time())?"selected":""}} disabled value="3">Hết hạn</option>
                                        <option disabled {{($item->confirmed_status ==2)?"selected":""}} {{($item->expired_date < time())?"disabled":""}} value="2">Không duyệt</option>
                                    </select>
                                @endif
                            </td>
                            <td>
                                <div class="list-image">
                                    <div class="image-large">
                                        <img src="{{isset(json_decode($item->image_perspective)[0])?json_decode($item->image_perspective)[0]:""}}" alt="">
                                    </div>
                                    <div class="image-small">
                                        <div class="item">
                                            <img src="{{isset(json_decode($item->image_perspective)[1])?json_decode($item->image_perspective)[1]:""}}" alt="">
                                        </div>
                                        <div class="item">
                                            <img src="{{isset(json_decode($item->image_perspective)[2])?json_decode($item->image_perspective)[2]:""}}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-left title"><a href="#">{{$item->classified_name}}</a></div>
                                <div class="text-left code-title mb-3">Mã tin: {{$item->classified_code}}</div>
                                <ul class="info text-left">
                                    <li><i class="far fa-clock"></i> Đăng ngày: {{date('d/m/Y',$item->created_at) }}</li>
                                    <li><i class="fas fa-signal"></i> Tổng lượt xem: {{$item->num_view}}</li>
                                    <li><i class="far fa-eye"></i> Lượt xem trong ngày: {{$item->num_view_today}}</li>
                                </ul>
                            </td>
                            <td>
                                <div class="info-desc text-justify">
                                    <div class="info-maxline">
                                        {{$item->classified_description}}
                                    </div>
                                    <span  data-toggle="modal" data-target="#content-classfied{{$item->id}}" class="view-more text-blue-light">Xem thêm</span>
                                </div>
                                {{--Contetn Modal--}}
                                <div class="modal fade" tabindex="-1" id="content-classfied{{$item->id}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title font-weight-bold text-uppercase">Nội dung tin rao</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-justify">
                                                    {{$item->classified_description}}
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                {{--                                        <button type="button" class="btn btn-secondary" >Hủy</button>--}}
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--End Modal--}}
                            </td>
                            <td>
{{--                                @if($check_role == 1  ||key_exists(2, $check_role))--}}
{{--                                    @if($item->hightlight_end < strtotime(\Carbon\Carbon::now())|| $item->vip_end < strtotime(\Carbon\Carbon::now()))--}}
{{--                                        <a type="button"  data-toggle="dropdown"  id="dropdownMenuButtonda{{$item->id}}" aria-expanded="true"  class="setting-item delete mb-2 dropdown" data-id="{{$item->id}}" style="margin-left: -10px"><img src="/system/image/icon-hot.png" alt=""> Nâng cấp tin</a>--}}
{{--                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonda{{$item->id}}" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(773px, 190px, 0px);" x-out-of-boundaries="">--}}
{{--                                            @if($item->hightlight_end < strtotime(\Carbon\Carbon::now()))--}}
{{--                                                <a style="cursor: pointer" class="dropdown-item  highlight_classified" data-id="{{$item->id}}" data-created_by="{{Crypt::encryptString($item->confirmed_by)}}">Nổi bật</a>--}}
{{--                                            @endif--}}
{{--                                            @if($item->vip_end < strtotime(\Carbon\Carbon::now()))--}}
{{--                                                <a style="cursor: pointer" class="dropdown-item  vip_classified" data-id="{{$item->id}}" data-created_by="{{Crypt::encryptString($item->confirmed_by)}}">Vip</a>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                @endif--}}
{{--                                @if($check_role == 1  ||key_exists(2, $check_role))--}}
{{--                                    <a href="javascript:{}" class="refresh_classified setting-item edit mb-2" data-id="{{$item->id}}" data-created_by="{{Crypt::encryptString($item->confirmed_by)}}" style="margin-left: 3px"><i style="margin-right: 3px" class="fas fa-redo-alt"></i> Làm mới tin</a>--}}
{{--                                @endif--}}
{{--                                @if($check_role == 1  ||key_exists(2, $check_role))--}}
{{--                                    <a href="#" class="setting-item edit mb-2"  style="margin-left: 3px" ><i style="margin-right: 3px" class="fas fa-cog"></i> Chỉnh sửa</a>--}}
{{--                                @endif--}}
                                @if($check_role == 1  ||key_exists(5, $check_role))
                                    <a style="margin-left: 3px;cursor: pointer"    data-id="{{$item->id}}" data-created_by="{{Crypt::encryptString($item->confirmed_by)}}" class="setting-item delete restore_classified mb-2 text-primary"><i style="margin-right: 6px;" class="fas fa-undo-alt text-dark"></i> Khôi phục</a>
                                @endif
                                <x-admin.force-delete-button
                                    :check-role="$check_role"
                                    id="{{ $item->id }}"
                                    url="{{ route('admin.classified.force-delete-multiple') }}"
                                />

{{--                                @if($check_role == 1  ||key_exists(2, $check_role))--}}
{{--                                    <a style="margin-left: 3px" href="#" class="setting-item edit"><i style="margin-right: 3px" class="fas fa-comment-dots"></i> Tạo thông báo <span class="count">2</span></a>--}}
{{--                                @endif--}}
                            </td>
                        </tr>
                    @endforeach
                </form>
                </tbody>
            </table>
            <!-- /Main row -->
        </div>

        <form action="" class="force-delete-item-form d-none" method="POST">
            @csrf
            <input type="hidden" name="ids">
        </form>

        <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
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
                            @if($check_role == 1  ||key_exists(5, $check_role))
                                <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">
                                    <i class="fas fa-undo-alt bg-primary p-1 mr-2 rounded"
                                       style="color: white !important;font-size: 15px"></i>Khôi phục
                                    <input type="hidden" name="action" value="restore">
                                </a>
                            @else
                                <p>Không đủ quyền</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="display d-flex align-items-center mr-4">
                    <span>Hiển thị:</span>
                    <form method="get" id="paginateform" action="{{route('admin.classified.list')}}">
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
                @if($check_role == 1  ||key_exists(8, $check_role))
                    <div class="view-trash">
                        <a href="{{route('admin.classified.list')}}" class="btn btn-primary text-decoration-none text-white">Quay lại</a>
{{--                        <a href="{{route('admin.groupclassified.listtrash')}}" class=" text-primary"><i class="text-primary far fa-trash-alt"></i>--}}
{{--                            Xem thùng rác</a>--}}
{{--                        <span class="count-trash">{{$trash_count}}</span>--}}
                    </div>
                @endif
            </div>
            <div class="d-flex align-items-center">
                <div class="count-item">Tổng cộng: {{$classified->total()}} items</div>
                @if($classified)
                    {{ $classified->render('Admin.Layouts.Pagination') }}
                @endif
            </div>
        </div>
    </section>
@endsection

@section('Style')
    {{--    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">--}}

    <style>
        .breadcrumb {
            padding: 8px 15px;
            margin-bottom: 10px;
            list-style: none;
            background-color: #f5f5f5;
            border-radius: 4px;
            width: 100%
        }
        .breadcrumb>li {
            display: inline-block;
        }
        ol, ul {
            margin-top: 0;
            margin-bottom: 10px;
        }
        .breadcrumb>li>a{
            color: #337ab7;
        }
        .breadcrumb>li>a::hover{
            color:#23527c;
        }
        .breadcrumb .list-box{
            font-size: 85%;border-radius: 4px
        }
        .breadcrumb .list-box>a{
            color: black !important;
        }
        .breadcrumb .list-box>a>i{
            font-size: 90%;
        }
        .check.active{
            background: #e7e7e7;
            border-radius: 4px;
            color: black !important;
        }
        .breadcrumb .phay{
            color: #ccc
        }
        .breadcrumb .recye,.breadcrumb .add{
            font-size: 85%;
        }
        .breadcrumb .add >a>i{
            font-size: 90%
        }
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
    </style>
@endsection
@section('Script')
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
    <script>
        $('.restore_classified').click(function () {
            var id = $(this).data('id');
            var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Xác nhận khôi phục',
                text: "Sau khi xác nhận sẽ tiến hành khôi phục",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/classified/untrash-item/" + id+"/"+created_by;
                }
            });
        });
        $('.moveToTrash').click(function () {
            Swal.fire({
                title: 'Xác nhận khôi phục',
                text: "Sau khi đồng ý sẽ tiến hành khôi phục",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("restore");
                    $('#trash_list').submit();

                }
            });
        });
    </script>

@endsection

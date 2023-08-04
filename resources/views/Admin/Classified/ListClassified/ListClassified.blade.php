@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách | Tin rao')
@section('Content')
    <section class="content">
        <div class="container-fluid">
            <!-- Filter -->
            <div class="filter block-dashed">
                <h3 class="title">Bộ lọc</h3>
                <form action="{{route('admin.classified.list')}}" method="get" enctype="multipart/form" class="form-filter">
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
                                    <select name="classified_type" class="custom-select">
                                        <option selected value="">Loại tin</option>
                                        <option {{(isset($_GET['classified_type'])&& $_GET['classified_type']=="0")?"selected":""}} value="0">Thường</option>
                                        <option {{(isset($_GET['classified_type'])&& $_GET['classified_type']==1)?"selected":""}} value="1">Vip</option>
                                        <option {{(isset($_GET['classified_type'])&& $_GET['classified_type']==2)?"selected":""}} value="2">Nổi bật</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <input  type="date" class="form-control start_day" value="{{(isset($_GET['start_day'])&& $_GET['start_day']!="")?$_GET['start_day']:""}}" name="start_day" placeholder="Từ ngày" >
                                </div>
                                <div class="col-md-4 form-group">
                                    <input type="date" class="form-control end_day" name="end_day" value="{{(isset($_GET['end_day'])&& $_GET['end_day']!="")?$_GET['end_day']:""}}" placeholder="Đến ngày" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3 form-group">
                            <select  name="classified_status" class="custom-select">
                                <option {{(isset($_GET['classified_status'])&& $_GET['classified_status']=="")?"selected":""}} value="">Tình trạng</option>
                                <option {{(isset($_GET['classified_status'])&& $_GET['classified_status']=="0")?"selected":""}} value="0">Chờ duyệt</option>
                                <option {{(isset($_GET['classified_status'])&& $_GET['classified_status']==1)?"selected":""}} value="1">Đã duyệt</option>
                                <option {{(isset($_GET['classified_status'])&& $_GET['classified_status']==2)?"selected":""}} value="2">Không duyệt</option>
                                <option {{(isset($_GET['classified_status'])&& $_GET['classified_status']==3)?"selected":""}} value="3">Hết hạn</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <input type="text" name="classified_id" value="{{(isset($_GET['classified_id'])&& $_GET['classified_id']!="")?$_GET['classified_id']:""}}" class="form-control" placeholder="Mã tin">
                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col-md-8 form-group">
                                    <input type="text" name="keyword" class="form-control" value="{{(isset($_GET['keyword'])&& $_GET['keyword']!="")?$_GET['keyword']:""}}" placeholder="Nhập từ khóa (Tiêu đề)">
                                </div>
                                <div class="col-md-4 form-group">
                                    <button class="btn bg-blue-light w-100"><i class="fas fa-search"></i> Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th><input type="checkbox" class="select-all checkbox" name="select-all" /></th>
                    <th>Tình trạng</th>
                    <th>Hình ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Nội dung</th>
                    <th>Người đăng</th>
                    <th>Cài đặt</th>
                </tr>
                </thead>
                <tbody>
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
                        @if($item->confirmed_status == 2 || $item->confirmed_status == 3)
                                    {{"bg-red-light"}}
                        @endif
                        @if($item->expired_date < time())
                                {{"bg-gray-medium"}}
                        @endif
                        ">
                        @if($check_role == 1  ||key_exists(2, $check_role))
                        <select style="width: 130px" data-id="{{$item->id}}" data-created_by="{{Crypt::encryptString($item->confirmed_by)}}" data-status="{{$item->confirmed_status}}"  class="custom-select change_status_classified">
                            <option {{($item->confirmed_status == 0)?"selected":""}} {{($item->expired_date < time())?"disabled":""}} value="0">Chờ duyệt</option>
                            <option {{($item->confirmed_status == 1)?"selected":""}} {{($item->expired_date < time())?"disabled":""}} value="1">Đã duyệt</option>
                            <option {{($item->expired_date < time())?"selected":""}} disabled value="3">Hết hạn</option>
                            <option {{($item->confirmed_status ==2)?"selected":""}} {{($item->expired_date < time())?"disabled":""}} value="2">Không duyệt</option>
                            <option {{($item->confirmed_status ==3)?"selected":""}} disabled value="3">Chứa từ cấm</option>
                        </select>
                        @endif
                    </td>
                    <td>
                        <div class="list-image">
                            <div class="image-large">
                                <img src="{{ isset(json_decode($item->image_perspective)[0]) ? asset(json_decode($item->image_perspective)[0]) : '' }}" alt="">
                            </div>
                            <div class="image-small">
                                <div class="item">
                                    <img src="{{ isset(json_decode($item->image_perspective)[1]) ? asset(json_decode($item->image_perspective)[1]) : '' }}" alt="">
                                </div>
                                <div class="item">
                                    <img src="{{ isset(json_decode($item->image_perspective)[2]) ? asset(json_decode($item->image_perspective)[2]) : '' }}" alt="">
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-left title">
                            @if($item->isHighLight())
                                <img src="{{ asset('user/images/icon/new.gif') }}" class="small-hot-icon mr-1" alt="">
                            @endif
                            <a
                                href="{{ $item->getShowUrl() ?: 'javascript:void(0);' }}"
                                class="{{ $item->isVip() || $item->isHighLight() ? 'link-red-flat' : '' }}"
                                target="{{ $item->isShow() ? '_blank' : '' }}"
                            >
                                {{ strlen($item->classified_name) > 40 ? substr($item->classified_name, 0, 39) . '...' : $item->classified_name }}
                            </a>
                        </div>
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
                              {!!strlen(strip_tags($item->classified_description))>300? substr(strip_tags($item->classified_description),0,299):$item->classified_description!!}
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
                                        <span class="text-justify text-break">
                                            {!!$item->classified_description!!}
                                       </span>
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
                        <span>
                            {{ $item->getName() }}
                        </span>
                    </td>
                    <td>
{{--                        @if($check_role == 1  ||key_exists(2, $check_role))--}}
{{--                        @if($item->hightlight_end < strtotime(\Carbon\Carbon::now())|| $item->vip_end < strtotime(\Carbon\Carbon::now()))--}}
{{--                        <a type="button"  data-toggle="dropdown"  id="dropdownMenuButtonda{{$item->id}}" aria-expanded="true"  class="setting-item delete mb-2 dropdown" data-id="{{$item->id}}" style="margin-left: -10px"><img src="/system/image/icon-hot.png" alt=""> Nâng cấp tin</a>--}}
{{--                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonda{{$item->id}}" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(773px, 190px, 0px);" x-out-of-boundaries="">--}}
{{--                            @if($item->hightlight_end < strtotime(\Carbon\Carbon::now()))--}}
{{--                            <a style="cursor: pointer" class="dropdown-item  highlight_classified" data-id="{{$item->id}}" data-created_by="{{Crypt::encryptString($item->confirmed_by)}}">Nổi bật</a>--}}
{{--                            @endif--}}
{{--                            @if($item->vip_end < strtotime(\Carbon\Carbon::now()))--}}
{{--                            <a style="cursor: pointer" class="dropdown-item  vip_classified" data-id="{{$item->id}}" data-created_by="{{Crypt::encryptString($item->confirmed_by)}}">Vip</a>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                        @endif--}}
{{--                        @endif--}}
{{--                        @if($check_role == 1  ||key_exists(2, $check_role))--}}
{{--                        <a href="javascript:{}" class="refresh_classified setting-item edit mb-2" data-id="{{$item->id}}" data-created_by="{{Crypt::encryptString($item->confirmed_by)}}" style="margin-left: 3px"><i style="margin-right: 3px" class="fas fa-redo-alt"></i> Làm mới tin</a>--}}
{{--                        @endif--}}
                        @if($check_role == 1  ||key_exists(2, $check_role))
                        <a href="{{route('admin.classified.edit',$item->id)}}" class="setting-item edit mb-2"  style="margin-left: 3px" ><i style="margin-right: 3px" class="fas fa-cog"></i> Chỉnh sửa</a>
                        @endif

                        <x-admin.delete-button
                            :check-role="$check_role"
                            url="{{ route('admin.classified.delete-multiple', ['ids' => $item->id]) }}"
                        />

                        @if($check_role == 1  ||key_exists(2, $check_role))

                            <a style="margin-left: 3px;cursor: pointer" href="{{route('admin.classified.toggle_classified', [$item->id, Crypt::encryptString($item->confirmed_by)])}}" class="setting-item toggle_classified mb-2">
                                @if($item->is_show == 1)
                                    <i style="margin-right: 6px;" class="fas fa-eye-slash"></i> Ẩn tin
                                @else
                                    <i style="margin-right: 6px;" class="fas fa-eye"></i> Hiện tin
                                @endif
                            </a>
                        @endif
                        @if($check_role == 1  ||key_exists(2, $check_role))

                        @if($item->user_id)
                        <a style="margin-left: 3px" href="#" class="setting-item edit" data-toggle="modal" data-target="#modalCreateNotify"
                           data-user-id="{{$item->user_id}}" data-user-username="{{$item->username}}">
                            <i style="margin-right: 3px" class="fas fa-comment-dots"></i> Tạo thông báo
                        </a> <!-- <span class="count">2</span> -->
                        @endif

                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <x-admin.table-footer
            :check-role="$check_role"
            :lists="$classified"
            :count-trash="$trash_count"
            view-trash-url="{{ route('admin.classified.trash') }}"
            delete-url="{{ route('admin.classified.delete-multiple') }}"
        />
    </section>
@endsection

@section('Style')
{{--    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">--}}
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
        .table tr.active {
            background-color: #eefff2;
        }
        .is_vip{
            font-weight: 550 !important;
            color: #ff0000 !important;
        }
    </style>
@endsection
@section('Script')
    <script src="js/table.js" type="text/javascript"></script>
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
                $('#group_child').append(data['group_child']);
                @if(isset($_GET['group_child'])&& $_GET['group_child']!="")
                $('#group_child').val("{{$_GET['group_child']}}").change();
                @endif
            }
        });
    @endif
</script>
<script>
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
    $('.delete_classified').click(function () {
        var id = $(this).data('id');
        var created_by = $(this).data('created_by');
        Swal.fire({
            title: 'Xác nhận xóa tin',
            text: "Sau khi xác nhận thì không thể hoàn tác",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/admin/classified/trash-item/" + id+"/"+created_by;
            }
        });
    });
    $('.change_status_classified').change(function () {
    var status =$(this).val();
    var old_status = $(this).data('status');
    var id = $(this).data('id');
    var created_by = $(this).data('created_by');
    Swal.fire({
        title: 'Xác nhận trạng thái',
        text: "Sau khi xác nhận thì không thể hoàn tác",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Quay lại',
        confirmButtonText: 'Đồng ý'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/admin/classified/change-status/"+status+"/"+id+"/"+created_by;
        }
        else {
            window.location.reload();
        }
    });
});

    $('.toggle_classified').click(function (evt) {
        evt.preventDefault()
        let url = evt.currentTarget.href;
        let message = evt.currentTarget.textInner || evt.currentTarget.textContent
        message = message.trim().toLowerCase()
        console.log(message)
        Swal.fire({
            title: "Bạn có chắc chắn " + message,
            text: "Sau khi xác nhận sẽ tiến hành thay đổi",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;

            }
        });
    });
</script>
@endsection

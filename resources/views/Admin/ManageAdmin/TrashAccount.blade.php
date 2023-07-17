    @extends('Admin.Layouts.Master')
@section('Content')
    <section class="content">
        <div class="container-fluid">
            <table class="table  table-custom" id="table">
                <thead>
                <tr>
                    <th><input type="checkbox" class="select-all"></th>
                    <th>STT</th>
                    <th>Tên hiển thị</th>
                    <th>Email</th>
                    <th>Loại tài khoản</th>
                    <th>Ngày tham gia</th>
                    <th>Cài đặt</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($trash_account as $item )
                        @if($item->admin_type == 1 || ($item->admin_type != 2 || \Illuminate\Support\Facades\Auth::guard('admin')->user()->admin_type == 1))
                        <tr>
                            <td>
                                <input type="checkbox" class="select-item" value="{{$item->id}}" name="select_item[]">
                        <input type="hidden" class="select-item" value="{{\Crypt::encryptString($item->created_by)}}" name="select_item_created[{{$item->id}}]">
                                    {{-- <input type="text" value="{{$item->created_by}}"> --}}
                            </td>
                            <td>{{$item->id}}</td>
                            <td>
                                <div class="user-info text-center">
                                    <div class="avatar">
                                        <img src="/system/img/avatar-admin/{{$item->image_url}}" alt="">
                                       
                                    </div>
                                    <a href="javascript:{}" class="user-name d-block">{{$item->admin_fullname}}</a>
                                </div>
                            </td>
                            <td>{{$item->admin_email}}</td>
                            <td>{{$item->role_name == null ? ($item->admin_type == 1 ? 'Tài khoản quản trị cao cấp' : '') : $item->role_name}}</td>
                            <td>{{date("d/m/Y",$item->created_at)}}</td>
                            <td>
                                {{-- <a href="#" class="setting-item edit mb-2"><i class="fas fa-cog"></i> Chỉnh sửa</a> --}}
                                 @if($check_role == 1  ||key_exists(6, $check_role))
                                <a class="setting-item text-primary delete mb-2" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}"><i
                                        class="fas fa-undo-alt text-black mr-1" style="color: #0d0d0d!important"></i> Khôi phục</a>
                                 @endif

                                <x-admin.force-delete-button
                                    :check-role="$check_role"
                                    id="{{ $item->id }}"
                                    url="{{ route('admin.manage.force-delete-multiple') }}"
                                />
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <form action="" class="force-delete-item-form d-none" method="POST">
                @csrf
                <input type="hidden" name="ids">
            </form>

            <div class="table-bottom d-flex align-items-center justify-content-between mb-4">
                <div class="text-left d-flex align-items-center">
                    <div class="manipulation d-flex mr-4">
                        <img src="image/manipulation.png" alt="" id="btnTop">
                        <div class="btn-group ml-1">
                            <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown"
                                    aria-expanded="false" data-flip="false" aria-haspopup="true">
                                Thao tác
                            </button>
                            <div class="dropdown-menu">
                                @if($check_role == 1 ||key_exists(6, $check_role))
                                <a class="dropdown-item unToTrash" type="button" href="javascript:{}">
                                    <i class="fas fa-undo-alt bg-primary p-1 mr-2 rounded"
                                       style="color: white !important;font-size: 15px"></i>Khôi phục
                                       <input type="hidden" name="action" value="restore">
                                </a>
                            
                                @else
                                <p class="dropdown-item m-0 disabled">
                                    Bạn không có quyền
                                </p>
                                @endif
                            </div>
                        </div>
                        </form>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mx-4">
                        <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                        <form method="get" action="{{route('admin.manage.trashaccount')}}">
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
                    
                    <div>
                        @if($check_role == 1  ||key_exists(4, $check_role))
                        <a href="{{route('admin.manage.accounts')}}" class="btn btn-primary">
                            Quay lại
                        </a>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="count-item">Tổng cộng: @empty($trash_account) {{0}} @else {{$trash_account->total()}} @endempty items</div>
                    @if($trash_account)
                        {{ $trash_account->render('Admin.Layouts.Pagination') }}
                    @endif
                </div>

            </div>
        </div>
        <!-- /Main row -->
    </section>
@endsection
@section('Style')
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

        .upload-avatar {
            position: relative;
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

        .bg-blue-light {
            background-color: #00bff3 !important;
            color: #fff !important;
        }

        .bg-blue-light:hover {
            text-decoration: underline;
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

        .content .table td .setting-item {
            display: block;
            color: #0090ff;
            font-size: 14px;
            text-align: left;
            min-width: 150px;
        }

        .content .table .setting-item:hover {
            text-decoration: none;
            cursor: pointer;

        }

        .content .table td .setting-item.delete {
            color: #ff0000;
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

        .content .user-info .avatar {
            position: relative;
            display: inline-block;
        }

        .content .table .user-info .avatar img {
            width: 60px;
            height: 60px;
            border-width: 3px;
        }

        .content .user-info .avatar img {
            width: 125px;
            height: 125px;
            border-radius: 50%;
            border: 5px solid #e9f2f4;
        }

        .content .table .user-info .avatar .edit-avatar {
            width: 20px;
            height: 20px;
            font-size: 10px;
            line-height: 20px;
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

        .preview {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 200px;
        }

        .preview img {
            display: block;
            width: 100%;
            height: auto;
        }

        .dropdown-custom {
            background-color: #337ab7;
            border-color: #2e6da4;
            color: #fff;
        }

        .dropdown-custom:hover {
            color: white;
            background-color: #286090;
            border-color: #204d74;
        }
    </style>

@endsection
@section('Script')
    <script src="js/table.js"></script>
    <script>
        $('.delete').click(function () {

            var id = $(this).data('id');
            var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Xác nhận khôi phục',
                text: "Nhấn đồng ý thì sẽ tiến hành khôi phục!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/manage-admin/undelete/" + id+"/"+created_by;

                }
            });
        });
        $('.unToTrash').click(function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận khôi phục',
                text: "Nhấn đồng ý thì sẽ tiến hành khôi phục !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {

                    $('#formtrash').submit();

                }
            });
        });
    </script>
@endsection

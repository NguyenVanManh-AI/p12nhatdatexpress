@extends('Admin.Layouts.Master')

@section('Title', 'Thùng rác danh sách tài khoản | Tài khoản quản trị')

@section('Content')
    <div class="row m-0 px-3 pt-3">
        <ol class="breadcrumb mt-1">
            <li class="recye px-2 pt-1  check">
                <a href="{{ route('admin.manage.accounts') }}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
        </ol>
    </div>
    <h4 class="text-center font-weight-bold mb-3 mt-2">QUẢN LÝ THÙNG RÁC TÀI KHOẢN</h4>
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
                                <div class="flex-column">
                                  <x-admin.restore-button
                                    :check-role="$check_role"
                                    url="{{ route('admin.manage.accounts.restore-multiple', ['ids' => $item->id]) }}"
                                  />
                
                                  <x-admin.force-delete-button
                                    :check-role="$check_role"
                                    url="{{ route('admin.manage.accounts.force-delete-multiple', ['ids' => $item->id]) }}"
                                  />
                                </div>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-admin.table-footer
            :check-role="$check_role"
            :lists="$trash_account"
            force-delete-url="{{ route('admin.manage.accounts.force-delete-multiple') }}"
            restore-url="{{ route('admin.manage.accounts.restore-multiple') }}"
        />
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
@endsection

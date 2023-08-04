@extends('Admin.Layouts.Master')
@section('Title', 'Thiết lập | Quản lý gói tin')
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
.form-filter .custom-select {
    color: #0c0c0c;
}
.bg-blue-light {
    background-color: #00bff3 !important;
    color: #fff !important;
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
.table tr.active {
    background-color: #eefff2;
}
 .add-package a {
    display: flex;
    align-items: center;
}

.text-blue-light {
    color: #00bff3 !important;
}
 .table td .setting-item {
    /* display: block; */
    color: #0090ff;
    font-size: 14px;
    text-align: left;
    min-width: 150px;
}
 .add-package a i {
    font-size: 24px;
    margin-right: 5px;
}
    </style>

@endsection
@section('Content')

    <section class="content">
        <div class="row m-0 p-3">
            <ol class="breadcrumb mt-1">
                @if($check_role == 1  ||key_exists(4, $check_role))
                <li class="list-box px-2 pt-1 active check">
                    <a href="{{route('admin.setup.list')}}">
                        <i class="fa fa-th-list mr-1"></i>Danh sách
                    </a>
                </li>
                @endif
            </ol>
        </div>

        <div class="container-fluid">

            <div class="table-contents">

            <table class="table">

                <thead>

                    <tr>

                        <th><input type="checkbox" class="select-all checkbox" name="select-all"/></th>

                        <th>STT</th>

                        <th>Gói tin</th>

                        <th>Giá gốc</th>

                        <th>Giá thực</th>

                        <th>Số tin đăng</th>

                        <th>Số tin vip</th>

                        <th>Tin nổi bậc</th>

                        <th>HSD Tin Vip/Nổi bật</th>

                        <th>Cài đặt</th>

                    </tr>

                </thead>

                <tbody>
       @foreach ( $trash_setup as $item )
                    <tr>
                        <td class="active">
                            <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}"/>
                            <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}" />

                        </td>

                        <td>{{$item->id}}</td>

                        <td>{{$item->package_name}}</td>

                        <td> {{number_format( $item->price,0,'',',')}} VNĐ</td>

                        <td> {{number_format( $item->discount_price,0,'',',')}} VNĐ</td>

                        <td>{{$item->classified_nomal_amount}}</td>

                        <td>{{$item->vip_amount}}</td>

                        <td>{{$item->highlight_amount}}</td>


                        <td>@if($item->highlight_duration == 24*60*60)
                                {{"1 ngày"}}
                            @elseif($item->highlight_duration == 7*24*60*60)
                                {{"1 tuần"}}
                            @elseif($item->highlight_duration == 30*24*60*60)
                                {{"1 tháng"}}
                            @elseif($item->highlight_duration == 365*24*60*60)
                                {{"1 năm"}}
                            @endif
                        </td>

                        <td>
                            <div class="flex-column">
                                <x-admin.restore-button
                                  :check-role="$check_role"
                                  url="{{ route('admin.setup.restore-multiple', ['ids' => $item->id]) }}"
                                />
              
                                <x-admin.force-delete-button
                                  :check-role="$check_role"
                                  url="{{ route('admin.setup.force-delete-multiple', ['ids' => $item->id]) }}"
                                />
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <x-admin.table-footer
                :check-role="$check_role"
                :lists="$trash_setup"
                force-delete-url="{{ route('admin.setup.force-delete-multiple') }}"
                restore-url="{{ route('admin.setup.restore-multiple') }}"
            />
        </div>
    </section>
@endsection
@section('Script')
<script src="js/table.js"></script>
@endsection

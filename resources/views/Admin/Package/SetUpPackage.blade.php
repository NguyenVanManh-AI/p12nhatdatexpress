@extends('Admin.Layouts.Master')

@section('Title', 'Thiết lập | Quản lý gói tin')

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
.content .table td .setting-item {
    display: block;
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

        <div class="container-fluid">

            <!-- Filter -->

            <div class="filter block-dashed">

                <h3 class="title">Bộ lọc</h3>

                <form action="{{route('admin.setup.list')}}" method="get" enctype="multipart/form" class="form-filter">

                    <div class="form-row">

                        <div class="col-md-12">

                            <div class="form-row">

                                <div class="col-md-12 form-group">

                                    <select name="package_type" class="custom-select">

                                        <option value="">Loại gói tin</option>

                                        <option {{(isset($_GET['package_type'])&&$_GET['package_type']=='D')?"selected":""}} value="D">Mặc định</option>

                                        <option {{(isset($_GET['package_type'])&&$_GET['package_type']=='V')?"selected":""}} value="V">Gói vip</option>

                                        <option {{(isset($_GET['package_type'])&&$_GET['package_type']=='A')?"selected":""}} value="A">Gói nâng cao</option>

                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="text-center form-group">

                        <button type="submit" class="btn bg-blue-light"><i class="fas fa-search"></i> Tìm kiếm</button>

                    </div>

                </form>

            </div>

            <!-- / (Filter) -->
            @if($check_role == 1  ||key_exists(1, $check_role))

            <div class="add-package mb-3">

                <a href="{{route('admin.setup.add')}}" class="text-blue-light" ><i class="fas fa-plus-circle"></i> Thêm gói tin</a>

            </div>
            @endif


            <!-- Main row -->
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

                        <th>Tin nổi bật</th>

                        <th>Thời gian nổi bật </th>
                        <th>Cài đặt</th>

                    </tr>

                </thead>

                <tbody>
             @foreach ( $list_setup as $item )

                    <tr>
                        <td class="active">
                            <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}"/>
                            <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}" />
                        </td>

                        <td>{{$item->id}}</td>
                        <td class="pr-0">
                            <p class="d-flex justify-content-start align-items-center mb-0">
                                {{$item->package_name}}
                                @if($item->best_seller)
                                    <img src="{{asset('frontend/images/unnamed.gif')}}" alt="logo" class="mx-auto best_sale">
                                @endif
                            </p>
                        </td>

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
                            @if($check_role == 1  ||key_exists(2, $check_role))
                                <div class="mb-2 ml-2">
                                    <span class="icon-small-size mr-1 text-dark">
                                        <i class="fas fa-cog"></i>
                                    </span>
                                    <a href="{{route('admin.setup.edit',[$item->id,\Crypt::encryptString($item->created_by)])}}" class="text-primary ">Chỉnh sửa</a>
                                </div>
                            @endif

                            <x-admin.delete-button
                                :check-role="$check_role"
                                url="{{ route('admin.setup.delete-multiple', ['ids' => $item->id]) }}"
                            />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <x-admin.table-footer
                :check-role="$check_role"
                :lists="$list_setup"
                :count-trash="$count_trash"
                view-trash-url="{{ route('admin.setup.trash') }}"
                delete-url="{{ route('admin.setup.delete-multiple') }}"
            />
        </div>
    </section>
@endsection
@section('Script')
<script src="js/table.js"></script>
@endsection

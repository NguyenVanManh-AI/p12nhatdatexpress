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

                            {{-- <a href="#" class="setting-item edit mb-2" data-toggle="modal" data-target="#modalUpdateSettingPackage"><i class="fas fa-cog"></i> Chỉnh sửa</a> --}}
                            @if($check_role == 1  ||key_exists(6, $check_role))

                            <div class="text-left mb-2">
                                <i class="fas fa-undo-alt text-black"></i>
                                <a class="setting-item delete text-primary" style="cursor: pointer" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">
                                    Khôi phục</a>
                            </div>
                            @endif

                            <x-admin.force-delete-button
                                :check-role="$check_role"
                                id="{{ $item->id }}"
                                url="{{ route('admin.setup.force-delete-multiple') }}"
                            />
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            <form action="" class="force-delete-item-form d-none" method="POST">
                @csrf
                <input type="hidden" name="ids">
            </form>

            <div class="d-flex align-items-center justify-content-between my-4">
                <div class="d-flex align-items-center">
                    <div class="d-flex">
                        <img src="image/manipulation.png" alt="" id="btnTop">
                        <div class="btn-group ml-1">
                            <!-- data-flip="false" -->
                            <button type="button" class="btn dropdown-toggle dropdown-custom"
                                    data-toggle="dropdown" aria-expanded="true" data-flip="false"
                                    aria-haspopup="true">
                                Thao tác
                            </button>
                            <div class="dropdown-menu">

                                @if($check_role == 1  ||key_exists(6, $check_role))
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
                            </form>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mx-4">
                        <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                        <form action="{{route('admin.setup.trash')}}" method="GET">
                            <label class="select-custom2">
                                <select id="paginateNumber" name="items" onchange="this.form.submit()">
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
                            </label>
                        </form>
                    </div>
                    <div>
                        @if($check_role == 1  ||key_exists(4, $check_role))
                        <a href="{{route('admin.setup.list')}}" class="btn btn-primary">
                            Quay lại
                        </a>
                        @endif
                    </div>
                    {{-- <div class="d-flex flex-row align-items-center view-trash">
                        <i class="far fa-trash-alt mr-2"></i>
                        <div class="link-custom">
                            <a href="#"><span style="color: #347ab6">Xem thùng rác</span>
                                <span class="badge badge-pill badge-danger trashnum"
                                      style="font-weight: 500">{{2}}</span>
                            </a>
                        </div>
                    </div> --}}
                </div>
                <div class="d-flex align-items-center">
                    <div class="count-item">Tổng cộng: {{$trash_setup->total()}} items</div>
                    <div>
                        @if($trash_setup)
                 {{ $trash_setup->render('Admin.Layouts.Pagination') }}
                        @endif
                    </div>
                </div>
            </div>


            <!-- /Main row -->

        </div><!-- /.container-fluid -->

    </section>




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
                window.location.href = "/admin/setup/untrash-setup/" + id+"/"+created_by;
            }
        });
    });
    $('.unToTrash').click(function () {
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

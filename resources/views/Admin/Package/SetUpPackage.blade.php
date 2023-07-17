@extends('Admin.Layouts.Master')

@section('Title', 'Quản lý gói tin')

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

             <form id="formtrash" action="{{route('admin.setup.trashlist')}}" method="POST">
                @csrf
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
                            <a href="{{route('admin.setup.edit',[$item->id,\Crypt::encryptString($item->created_by)])}}" class="setting-item edit mb-2" >
                                <i class="fas fa-cog" style="width: 25px"></i> Chỉnh sửa
                            </a>
                            @endif
                            @if($check_role == 1  ||key_exists(5, $check_role))
                            <a href="javascrip:{}" class="setting-item delete text-red" style="margin-left: 2px" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">
                                <i class="fas fa-times" style="width: 25px"></i> Xóa
                            </a>
                            @endif

                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

            </div>

            <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
                <div class="text-left d-flex align-items-center">
                    <div class="manipulation d-flex mr-4">
                        <img src="image/manipulation.png" alt="" id="btnTop">
                        <div class="btn-group ml-1">
                            <button type="button" class="btn dropdown-toggle dropdown-custom"
                                    data-toggle="dropdown"
                                    aria-expanded="false" data-flip="false" aria-haspopup="true">
                                Thao tác
                            </button>
                            <div class="dropdown-menu">
                                 @if($check_role == 1  ||key_exists(6, $check_role))
                                <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">
                                    <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"
                                       style="color: white !important;font-size: 15px"></i>Thùng rác
                                    <input type="hidden" name="action" value="trash">
                                </a>
                                 @else
                                <p class="dropdown-item m-0 disabled">
                                    Bạn không có quyền
                                </p>
                                 @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mx-4">
                        <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                        <form action="{{route('admin.setup.list')}}" method="GET">
                            <label class="select-custom2">
                                <select id="paginateNumber" name="items" onchange="submitPaginate(event, this)">
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
                                </select>
                            </label>
                        </form>
                    </div>
                    <div class="view-trash">
                        <a href="{{route('admin.setup.trash')}}"><i class="far fa-trash-alt"></i> Xem thùng
                            rác</a>
                        <span class="count-trash">{{$count_trash}}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center" >
                    <div class="count-item" >Tổng cộng: @empty($list_setup) {{0}} @else {{$list_setup->total()}} @endempty items</div>
                    <div class="count-item count-item-reponsive" style="display: none">@empty($list_setup) {{0}} @else {{$list_setup->total()}} @endempty items</div>
                    @if($list_setup)
                        {{ $list_setup->render('Admin.Layouts.Pagination') }}
                    @endif
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
            title: 'Xác nhận xóa',
            text: "Sau khi xóa sẽ chuyển vào thùng rác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/admin/setup/delete/" + id +"/"+created_by;
            }
        });
    });
    $('.moveToTrash').click(function () {
        // var id = $(this).data('id');
        Swal.fire({
            title: 'Xác nhận xóa',
            text: "Sau khi xóa sẽ chuyển vào thùng rác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
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

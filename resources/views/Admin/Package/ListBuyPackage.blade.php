@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách mua gói tin | Quản lý gói tin')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-event.css")}}">
<style>
    .content-wrapper {
    background-color: #fff;
}
.content-wrapper {
    height: 100%;
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
.bg-orange-light {
    background-color: #fdc689 !important;
    color: #fff;
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
.table td a.email {
    color: #0d0d0d;
    font-weight: 500;
}
.table td a.name {
    color: #21337f;
    font-weight: 500;
}
.table tr.active {
    background-color: #eefff2;
}
</style>
@endsection
@section('Content')
    <section class="content">
        <div class="container-fluid">
            <!-- Filter -->

            <div class="filter block-dashed">
                <h3 class="title">Bộ lọc</h3>

                <form action="{{route('admin.package.list')}}" method="get" enctype="multipart/form" class="form-filter">
                    <div class="form-row">
                        <div class="col-md-4 form-group">
                            <input type="text" class="form-control" value="{{(isset($_GET['keyword'])&& $_GET['keyword']!="")?$_GET['keyword']:''}}" name="keyword" placeholder="Nhập từ khóa (Tên tài khoản, sdt, mã giao dịch)">
                        </div>

                        <div class="col-md-8">
                            <div class="form-row">
                                <div class="col-md-6  form-group">
                                    <select name="package_status" class="custom-select">
                                        <option selected value="">Tình trạng </option>
                                        <option {{ request()->package_status == '0' ? 'selected' : ''}} value="0">Chờ duyệt</option>
                                        <option {{ request()->package_status == '1' ? 'selected' : ''}} value="1">Đã duyệt</option>
                                        <option {{ request()->package_status == '2' ? 'selected' : ''}} value="2">Không duyệt</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <input type="date" class="form-control start_day" value="{{ request()->start_day }}" name="start_day" placeholder="Từ ngày" >
                                </div>
                                <div class="col-md-3 form-group">
                                    <input type="date" class="form-control end_day" value="{{ request()->end_day }}" name="end_day" placeholder="Đến ngày" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 form-group">
                            <select name="classified_package" class="custom-select">
                                <option value="">Gói tin</option>
                                    @foreach ($package as $item)
                                        <option {{ request()->classified_package == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->package_name }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <select name="payment" class="custom-select">
                                <option value="">Hình thức thanh toán  </option>
                                @foreach ($payment as $item)
                                    <option  {{ request()->payment == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->payment_name }}</option>
                                @endforeach
                                <option {{ request()->payment == 'coin' ? 'selected' : '' }} value="coin">Coin</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-center form-group">
                        <button class="btn bg-blue-light"><i class="fas fa-search"></i> Tìm kiếm</button>
                    </div>
                </form>
            </div>

            <!-- / (Filter) -->

            <!-- Main row -->

            <div class="table-contents">
            <table class="table">
                <thead>
                    <tr>
                        {{-- <th> <input type="checkbox" class="select-all checkbox" name="select-all"/></th> --}}
                        <th>STT</th>
                        <th class="">Tình trạng
                        </th>
                        <th>Tài khoản</th>
                        <th>Mã thanh toán</th>
                        <th>Gói tin</th>
                        <th>Số tiền</th>
                        <th>Ngày mua</th>
                        <th>Hình thức TT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list_buy as $item)
                    <tr>
                        {{-- <td><input type="checkbox" class="select-item checkbox" name="select_item[]" value=""/></td> --}}
                        <td>{{$item->id}}</td>
                        <td class="@if($item->deposit_id) @if($item->deposit_status==0)
                            {{"bg-orange-light"}}
                            @elseif($item->deposit_status==1)
                            {{"bg-green-light"}}
                            @else
                            {{-- @elseif($item->deposit_status==2) --}}
                            {{"bg-red-light"}}
                            @endif
                            @endif
                        ">
                            @if ($item->deposit_id)
                                <select class="custom-select change_status" data-id="{{ $item->deposit_id }}"  >
                                    <option {{ $item->deposit_status == '0' ? 'selected' : '' }} {{ ($item->deposit_status == '1' || $item->deposit_status == '2') ? 'disabled' : '' }} value="0">Chờ duyệt</option>
                                    <option {{ $item->deposit_status == '1' ? 'selected' : '' }} value="1">Đã duyệt</option>
                                    <option {{ $item->deposit_status == '2' ? 'selected' : '' }} value="2">Không duyệt</option>
                                </select>
                            @endif
                        </td>
                        <td>
                            <p class="mb-1"><a class="name" href="#">{{$item->fullname}}</a></p>

                            <p class="mb-0"><a class="email" href="mailto:{{$item->email}}">{{$item->email}}</a></p>

                        </td>
                        <td>
                            {{$item->deposit_code}}
                        </td>
                        <td>
                            {{$item->package_name}}
                        </td>
                        <td>
                            @if($item->coin_amount)
                                {{ number_format($item->coin_amount,0,'',',') }} Coin
                            @else
                                {{ number_format($item->deposit_amount,0,'',',') }} VNĐ
                            @endif
                        </td>

                        <td>{{date('d/m/Y H:i',$item->created_at)}}</td>

                        <td>
                            @if($item->coin_amount)
                                Coin
                            @else
                                {{$item->payment_name}}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <!-- /Main row -->
            <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
                <div class="text-left d-flex align-items-center">

                    <div class="d-flex align-items-center justify-content-between mx-4">
                        <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                        <form action="{{route('admin.package.list')}}" method="GET">
                            <label class="select-custom2">
                                <select id="paginateNumber" name="items" onchange="submitPaginate(event, this)">
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
                                </select>
                            </label>
                        </form>
                    </div>
                </div>
                <div class="d-flex align-items-center" >
                    <div class="count-item" >Tổng cộng: @empty($list_buy) {{0}} @else {{$list_buy->total()}} @endempty items</div>
                    <div class="count-item count-item-reponsive" style="display: none">@empty($list_buy) {{0}} @else {{$list_buy->total()}} @endempty items</div>
                    @if($list_buy)
                        {{ $list_buy->render('Admin.Layouts.Pagination') }}
                    @endif
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@section('Script')
<script src="js/table.js"></script>
<script>
 $('.change_status').change(function () {
    var status =$(this).val();
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
            // window.location.href = "/admin/package/change-status/"+status+"/"+id;
            window.location.href = "/admin/package/change-status/"+status+"/"+id;
        }
        else {
            window.location.reload();
        }
    });
});


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
@endsection
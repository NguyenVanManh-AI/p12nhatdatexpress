@extends('Admin.Layouts.Master')
@section('Content')

<section class="content">
{{--    <div class="row m-0 p-3">--}}
{{--        <ol class="breadcrumb mt-1">--}}
{{--            --}}{{-- @if($check_role == 1  ||key_exists(4, $check_role)) --}}
{{--            <li class="list-box px-2 pt-1 active check">--}}
{{--                <a href="{{route('admin.coin.list')}}">--}}
{{--                    <i class="fa fa-th-list mr-1"></i>Danh sách--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            --}}{{-- @endif --}}

{{--            --}}{{-- @if($check_role == 1  ||key_exists(1, $check_role))     --}}
{{--            <li class="ml-2 phay">--}}
{{--                /--}}
{{--            </li>--}}
{{--            <li class="add px-2 pt-1 ml-1 check">--}}
{{--                <a href="{{route('admin.coin.add')}}">--}}
{{--                    <i class="fa fa-edit mr-1"></i>Thêm--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            --}}{{-- @endif --}}

{{--        </ol>--}}
{{--    </div>--}}

    <div class="container-fluid">

        <!-- Filter -->

        <div class="filter block-dashed">

            <h3 class="title">Bộ lọc</h3>

            <form action="" method="get" enctype="multipart/form" class="form-filter">

                <div class="form-row">

                    <div class="col-md-3 form-group">

                        <input type="text" class="form-control" value="{{(isset($_GET['keyword'])&& $_GET['keyword']!="")?$_GET['keyword']:''}}" name="keyword" placeholder="Nhập từ khóa (Tên tài khoản, sdt, mã giao dịch)">

                    </div>

                    <div class="col-md-9">

                        <div class="form-row">

                            <div class="col-md-4 form-group">

                                <input type="text" name="money" value="{{(isset($_GET['money'])&& $_GET['money']!="")?$_GET['money']:''}}" class="form-control" placeholder="Số tiền nạp">

                            </div>

                            <div class="col-md-4 form-group">

                                <input type="date" class="form-control start_day" value="{{(isset($_GET['start_day'])&& $_GET['start_day']!="")?$_GET['start_day']:''}}" name="start_day" placeholder="Từ ngày" >

                            </div>

                            <div class="col-md-4 form-group">

                                <input type="date" class="form-control end_day" value="{{(isset($_GET['end_day'])&& $_GET['end_day']!="")?$_GET['end_day']:''}}" name="end_day" placeholder="Đến ngày" >

                            </div>

                        </div>

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

                    <th><input type="checkbox" class="select-all"></th>

                    <th>STT</th>


                    <th>Tình Trạng</th>

                    <th>Tài khoản nạp</th>

                    <th>Mã giao dịch</th>

                    <th>Số tiền</th>

                    <th>Mã ưu đãi</th>

                    <th>Ngày nạp</th>

                    <th>Cài đặt</th>

                </tr>

            </thead>

            <tbody>
                <form id="formtrash" action="{{route('admin.coin.trashlist')}}" method="POST">
                @csrf
                @foreach ($list_buy as $item )
                        <tr>
                            <td class="active">
                                <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}"/>
                                <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->confirm_by)}}" />
                            </td>
                            <td>{{$item->id}}</td>

                            <td class="@if($item->deposit_status==0)
                                {{"bg-orange-light"}}
                                @elseif($item->deposit_status==1)
                                {{"bg-green-light"}}
                                @else
                                {{-- @elseif($item->deposit_status==2) --}}
                                {{"bg-red-light"}}
                                @endif
                            ">

                                <select name="" class="custom-select change_status" data-created_by="{{Crypt::encryptString($item->confirm_by)}}" data-id="{{$item->deposit_id}}"  >
                                    <option {{($item->deposit_status == "0")?"selected":""}} {{($item->deposit_status == "1"||$item->deposit_status == "2")?"disabled":""}} value="0">Chờ duyệt</option>
                                    <option {{($item->deposit_status == "1")?"selected":""}} {{($item->deposit_status != "0")?"disabled":""}} value="1">Đã duyệt</option>
                                    <option {{($item->deposit_status == "2")?"selected":""}} {{($item->deposit_status != "0")?"disabled":""}} value="2">Không duyệt</option>
                                </select>

                            </td>

                            <td>
                                <p class="mb-1"><a class="name" href="#">{{$item->fullname}}</a></p>
                                <p class="mb-1"><a class="name" href="mailto:{{$item->email}}">{{$item->email}}</a></p>
                                <p class="mb-0"><a class="name" href="tel:{{$item->phone_number}}">{{$item->phone_number}}</a></p>
                            </td>

                            <td>{{$item->deposit_code}}</td>
                            <td>{{number_format($item->deposit_amount,0,'','.')}} VNĐ</td>
                            <td>+{{$item->voucher_discount_percent}}%</td>
                            <td>{{date('d/m/Y H:i',$item->created_at)}}</td>
                            <td>
                                @if($check_role == 1  ||key_exists(5, $check_role))
                                    <a href="javascript:{}" class="setting-item delete text-red d-inline" data-created_by="{{Crypt::encryptString($item->confirm_by)}}"  data-id="{{$item->id}}" ><i class="fas fa-times"></i> Xóa</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
            </tbody>

        </table>

        </div>
    {{-- phần cuối --}}
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
                @if($check_role == 1  ||key_exists(5, $check_role))
                        <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">
                            <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"
                               style="color: white !important;font-size: 15px"></i>Thùng rác
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
        </form>
            <div class="d-flex align-items-center justify-content-between mx-4">
                <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                <form action="{{route('admin.coin.list')}}" method="GET">
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
            @if($check_role == 1  ||key_exists(8, $check_role))
            <div class="d-flex flex-row align-items-center view-trash">
                <i class="far fa-trash-alt mr-2"></i>
                <div class="link-custom">

                    <a href="{{route('admin.coin.trash')}}"><span style="color: #347ab6">Xem thùng rác</span>
                        <span class="badge badge-pill badge-danger trashnum"
                              style="font-weight: 500">{{ $trashCount }}</span>
                    </a>

                </div>
            </div>
            @endif
        </div>
        <div class="d-flex align-items-center">
            <div class="count-item">Tổng cộng: {{$list_buy->total()}} items</div>
            @if($list_buy)
     {{ $list_buy->render('Admin.Layouts.Pagination') }}
            @endif
        </div>
    </div>

    </div><!-- /.container-fluid -->

</section>

@endsection
@section('Style')
<link rel="stylesheet" href="{{asset('system/css/frontend/main.css')}}">
<link rel="stylesheet" href="{{asset('system/css/frontend/plusb.css')}}">
<style>

</style>
@endsection
@section('Script')
<script src="{{asset('system/js/table.js')}}"></script>
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
                window.location.href = "/admin/coin/delete/" + id +"/"+created_by;
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
               window.location.href = "/admin/coin/change-status/"+status+"/"+id+"/"+created_by;
           }
           else {
               window.location.reload();
           }
       });
   });


   </script>
@endsection

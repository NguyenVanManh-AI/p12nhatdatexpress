@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách nạp | Nạp Express coin')
@section('Content')

<section class="content">
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
                                <x-admin.delete-button
                                    :check-role="$check_role"
                                    url="{{ route('admin.coin.delete-multiple', ['ids' => $item->id]) }}"
                                />
                            </td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>

        <x-admin.table-footer
            :check-role="$check_role"
            :lists="$list_buy"
            :count-trash="$trashCount"
            view-trash-url="{{ route('admin.coin.trash') }}"
            delete-url="{{ route('admin.coin.delete-multiple') }}"
        />
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

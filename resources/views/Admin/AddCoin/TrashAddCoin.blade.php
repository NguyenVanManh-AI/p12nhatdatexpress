@extends('Admin.Layouts.Master')
@section('Content')

<section class="content">

    <div class="container-fluid">
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
          @foreach ($list_buy as $item )
                <tr>
                    <td class="active">
                        <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}"/>
                        <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->confirm_by)}}"  />

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

                        <select name="" class="custom-select change_status" data-created_by="{{Crypt::encryptString($item->confirm_by)}}" data-id="{{$item->deposit_id}}" data-id="{{$item->deposit_id}}"  >

                            <option {{($item->deposit_status == "0")?"selected":""}} {{($item->deposit_status == "1"||$item->deposit_status == "2")?"disabled":""}} value="0">Chờ duyệt</option>

                            <option {{($item->deposit_status == "1")?"selected":""}} value="1">Đã duyệt</option>

                            <option {{($item->deposit_status == "2")?"selected":""}} value="2">Không duyệt</option>

                        </select>

                    </td>

                    <td>

                        <p class="mb-1"><a class="name" href="#">{{$item->fullname}}</a></p>

                        <p class="mb-0"><a class="email" href="mailto:{{$item->email}}">{{$item->email}}</a></p>

                    </td>

                    <td>

                        {{$item->deposit_code}}

                    </td>

                    <td>

                        {{number_format($item->deposit_amount,0,'','.')}} VNĐ 
                    </td>

                    <td>+{{$item->voucher_discount_percent}}%</td>

                    <td>{{date('d/m/Y H:i',$item->created_at)}}</td>


                    <td>
                        @if($check_role == 1  ||key_exists(6, $check_role))
                        
                        <a href="javascript:{}" class="setting-item delete text-primary" data-created_by="{{Crypt::encryptString($item->confirm_by)}}" data-id="{{$item->id}}" ><i class="fas fa-undo-alt"></i> Khôi phục</a>
                        @endif

                        <x-admin.force-delete-button
                            :check-role="$check_role"
                            id="{{ $item->id }}"
                            url="{{ route('admin.coin.force-delete-multiple') }}"
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

         {{-- phan trang --}}

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
                                <i class="fas fa-undo-alt bg-primary     p-1 mr-2 rounded"
                                   style="color: white !important;font-size: 15px"></i>Khôi phục
                                   {{-- <input type="hidden" name="action" value="restore"> --}}
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
                    <form action="{{route('admin.coin.trash')}}" method="GET">
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
                    <a href="{{route('admin.coin.list')}}" class="btn btn-primary">
                        Quay lại
                    </a>
                    @endif
                </div>
               
            </div>
            <div class="d-flex align-items-center">
                <div class="count-item">Tổng cộng: {{$list_buy->total()}} items</div>
                <div>
                    @if($list_buy)
             {{ $list_buy->render('Admin.Layouts.Pagination') }}
                    @endif
                </div>
            </div>
        </div>

        <!-- /Main row -->

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
                window.location.href = "/admin/coin/untrash-coin/" + id+"/"+created_by;
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
                $('#formaction').val('restore');
                // alert($('#formaction').val('restore'));
                $('#formtrash').submit();

            }
        });
    });
</script>
@endsection
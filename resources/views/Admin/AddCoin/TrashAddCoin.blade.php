@extends('Admin.Layouts.Master')

@section('Title', 'Thùng rác - Danh sách nạp | Nạp Express coin')

@section('Content')

<div class="row m-0 px-3 pt-3">
  <ol class="breadcrumb mt-1">
    <li class="recye px-2 pt-1  check">
      <a href="{{route('admin.coin.list')}}">
        <i class="fa fa-th-list mr-1"></i>Danh sách
      </a>
    </li>
  </ol>
</div> 
<h4 class="text-center font-weight-bold mb-3 mt-2 text-uppercase">Quản lý thùng rác danh sách nạp</h4>

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
                        <div class="flex-column">
                            <x-admin.restore-button
                              :check-role="$check_role"
                              url="{{ route('admin.coin.restore-multiple', ['ids' => $item->id]) }}"
                            />
                            <x-admin.force-delete-button
                              :check-role="$check_role"
                              url="{{ route('admin.coin.force-delete-multiple', ['ids' => $item->id]) }}"
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
            :lists="$list_buy"
            force-delete-url="{{ route('admin.coin.force-delete-multiple') }}"
            restore-url="{{ route('admin.coin.restore-multiple') }}"
        />
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
@endsection

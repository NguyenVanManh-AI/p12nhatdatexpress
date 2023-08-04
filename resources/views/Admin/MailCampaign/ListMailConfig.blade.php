@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách mail cấu hình | Chiên dịch email')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
<style type="text/css">
  #success{background: green;}
</style>
@endsection
@section('Content')
{{-- css reponsive panagetion --}}
<style type="text/css">
  @media only screen and (max-width: 779px) {
    .box-panage{
      display:inline !important;
    }
  }
  .box_input{
    height: 50px !important;
  }
</style>
<!-- Content Header (Page header) -->
<div class="col-sm-12 mbup30">
  <div class="row m-0 px-2 pt-3">
    <ol class="breadcrumb mt-1">
      <li class="list-box px-2 pt-1 active check">
        <i class="fa fa-th-list mr-1"></i>Danh sách
      </li>
      {{-- kiểm tra phân quyền thùng rác --}}
      @if($check_role == 1  ||key_exists(5, $check_role))
      <li class="phay ml-2 " style="margin-top: 2px !important">
        /
      </li>
      <li class="recye px-2 pt-1 ml-1">
        <a href="{{route('admin.mail-campaign.configs.trash')}}">
          Thùng rác
        </a>
      </li>
      @endif
      {{-- kiểm tra phân quyền thêm --}}
      @if($check_role == 1  ||key_exists(1, $check_role))
      <li class="ml-2 phay">
        /
      </li>   
      <li class="add px-2 pt-1 ml-1 check">
        <a href="{{route('admin.email-campaign.add-mail-config')}}">
          <i class="fa fa-edit mr-1"></i>Thêm
        </a>
      </li>
      @endif
    </ol>
  </div>
</div><!-- /.col -->

<!-- Filter -->
<div class="container-fluid px-3 mt-2">
  <form method="get" action="">
    <div class="row m-0">
      {{-- Tìm kiếm theo từ khóa --}}
      <div class="col-12 col-sm-12 col-md-3 col-lg-3 box_input px-0 mb-2">
       <div class="input-reponsive-search ">
         <input class="form-control required" type="" name="keyword" placeholder="Nhập từ khóa" value="{{ app('request')->input('keyword') }}">
       </div>
     </div>
     <div class="search-reponsive  col-12 col-sm-12 col-md-9 col-lg-9 pl-0">
      <div class="row m-0">
        <div id="from_date_box" class=" search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pr-2 pl-4 ">
          <div  style="position: relative">
            {{-- Tìm kiếm theo từ ngày --}}
            @if(app('request')->input('from_date') == "")
            <div id="from_date_text"  style="position: absolute;width: 60%;height: 38px;padding: 1px;">
              <div class="bg-white"><span class="ml-2" style="line-height: 36px;">Từ ngày</span></div> 
            </div>
            @endif
            <input id="handleDateFrom" class="start_day form-control float-left" name="from_date" type="date" placeholder="Từ ngày" value="{{ app('request')->input('from_date') }}" >
          </div>
        </div>
        <div id="to_date_box" class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pl-3 pr-2 pb-3">
          <div  style="position: relative">
            {{-- Tìm kiếm theo đến ngày --}}
            @if(app('request')->input('to_date') == "")
            <div id="to_date_text" style="position: absolute;width: 60%;height: 38px;padding: 1px;">
              <div class="bg-white"><span class="ml-2" style="line-height: 36px;">Đến ngày</span></div>         
            </div>
            @endif
            <input id="handleDateTo" class="end_day form-control float-right" name="to_date" type="date" placeholder="Đến ngày" value="{{ app('request')->input('to_date') }}" >
            <div id="appendDateError"></div>
          </div>
        </div>
        <script type="text/javascript">
          {{-- Ẩn hiện từ ngày - đến ngày trong ô input --}}
          $('#from_date_box').click(function(){
            $('#from_date_text').hide();
          })
          $('#to_date_box').click(function(){
            $('#to_date_text').hide();
          })
        </script>
        {{-- submit tìm kiếm --}}
        <div id="to_date_box" class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pl-3 pr-2 pb-3">
          <button class=" search-button btn btn-primary w-100" style="height: 37px;"><i class="fa fa-search mr-2 ml-0" aria-hidden="true"></i>Tìm kiếm</button>                      
        </div>
      </div>
    </div>
  </div>
</form>
</div>
<!-- ./Filter -->

<div class="content-header" style="margin-top: -10px">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12 text-center">
        <h1 class="m-0 font-weight-bold">DANH SÁCH MAIL CẤU HÌNH</h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content" style="overflow-x: hidden">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered text-center table-hover table-custom " id="table" style="min-width: 1050px">
              <thead>
                <tr>
                  <th scope="row" class="active" width="3%">
                    {{-- chọn tất cả --}}
                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                  </th>
                  <th scope="col" style="width: 4%">ID</th>
                  <th scope="col" style="width: 55%">Email</th>    
                  <th scope="col" style="width: 25%">Đã gửi</th>
                  <th scope="col" width="22%">Thao tác</th>
                </tr>
              </thead>
              <tbody>
                {{-- lặp lấy ra danh sách --}}
                @forelse($list as $item)
                <tr>
                  <td class="active">
                    {{-- chọn nhiều dòng --}}
                    <input type="checkbox" class="select-item" value="{{$item->id}}" name="select_item[]">
                    <input type="hidden" class="select-item" value="{{\Crypt::encryptString($item->created_by)}}" name="select_item_created[{{$item->id}}]">
                  </td>
                  {{-- id mẫu mail --}}
                  <td >{{$item->id}}</td>
                  <td>
                    {{-- tiêu đề mail --}}
                    {{$item->mail_username}}
                  </td>
                  <td>
                    {{-- ngày tạo --}}
                    @php
                    $countSend = 0;
                    @endphp
                    @foreach($getCountSend as $count)
                    @if($item->id == $count->admin_mail_config_id)
                    @php
                    $countSend++;
                    @endphp
                    @endif
                    @endforeach 
                    {{$countSend}}
                  </td>
                  <td class="text-left">
                    <div>
                      @if($check_role == 1  ||key_exists(2, $check_role))
                        <div class="ml-2 mb-2">
                          <span class="icon-small-size mr-1 text-dark">
                            <i class="fas fa-cog"></i>
                          </span>
                          <a href="{{route('admin.email-campaign.edit-mail-config',[$item->id,\Crypt::encryptString($item->created_by)])}}" >Chỉnh sửa</a>
                        </div>
                      @endif

                      <x-admin.delete-button
                        :check-role="$check_role"
                        url="{{ route('admin.mail-campaign.configs.delete-multiple', ['ids' => $item->id]) }}"
                      />
                    </div>
                  </td>
              </tr>
              @empty
              <td colspan="9">Chưa có dữ liệu</td>
              @endforelse

            </tbody>
          </table>
      </div>
      <x-admin.table-footer
        :check-role="$check_role"
        :lists="$list"
        :count-trash="$trash_num"
        view-trash-url="{{ route('admin.mail-campaign.configs.trash') }}"
        delete-url="{{ route('admin.mail-campaign.configs.delete-multiple') }}"
      />
    </div>
  </div>
</div>
</div>
</section>
<!-- /.content -->
@endsection

@section('Script')
<script src="js/table.js" type="text/javascript"></script>
<script>
  //kiểm tra đến ngày phải lớn hơn từ ngày
  setMinMaxDate('#handleDateFrom', '#handleDateTo')
  function setMinMaxDate(inputElementStart, inputElementEnd){
    //lấy ra value của ngày đến
    if ($(inputElementStart).val()) $(inputElementEnd).attr('min',$(inputElementStart).val());
    //lấy ra value của ngày đến
    if ($(inputElementEnd).val()) $(inputElementStart).attr('max',$(inputElementEnd).val());
    //Khi thay đổi thì set min
    $(inputElementStart).change(function (){
      $(inputElementEnd).attr('min',$(inputElementStart).val());
    });
    //Khi thay đổi thì set max
    $(inputElementEnd).change(function (){
      $(inputElementStart).attr('max',$(inputElementStart).val());
    });
  }
</script>
{{-- nếu lỗi trả về lớn hơn 0 --}}
@if(count($errors) > 0)
{{-- lặp và hiển thị ra giao diện các lỗi đó --}}
@foreach($errors->all() as $error)
toastr.error("{{ $error }}");
@endforeach
@endif
@if(Session::get('themthanhcong') =="true")
  <script type="text/javascript">
   toastr.success('Thêm thành công');
  </script>
  {{Session::forget('themthanhcong')}}
@endif
@if(Session::get('suathanhcong') =="true")
  <script type="text/javascript">
   toastr.success('Sửa thành công');
  </script>
  {{Session::forget('suathanhcong')}}
@endif

@endsection

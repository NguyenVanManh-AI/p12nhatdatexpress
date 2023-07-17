@extends('Admin.Layouts.Master')
@section('Title', 'Sửa mã khuyến mãi | Mã khuyến mãi')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel='stylesheet' href='https://cdn.jsdelivr.net/select2/3.4.8/select2.css'>
@endsection
@section('Content')
<section>

 <div class="row m-0 px-3 pt-3">

  <ol class="breadcrumb mt-1">
    <li class="recye px-2 pt-1  check">
      <a href="{{route('admin.promotion.list-promotion')}}">
        <i class="fa fa-th-list mr-1"></i>Danh sách
      </a>
    </li>
    {{-- @if($check_role == 1  ||key_exists(5, $check_role))
    <li class="phay ml-2 mt-2">
      /
    </li>
    <li class="recye px-2 pt-1 ml-1">
      <a href="{{route('admin.promotion.trash')}}">
        Thùng rác
      </a>
    </li>
    @endif
    @if($check_role == 1  ||key_exists(1, $check_role))
    <li class="ml-2 phay mt-2">
      /
    </li>
    <li class="add px-2 pt-1 ml-1 check">
      <a href="{{route('admin.promotion.add-promotion')}}">
        <i class="fa fa-edit mr-1"></i>Thêm
      </a>
    </li>
    @endif --}}
  </ol>
</div>
  <h4 class="text-center font-weight-bold mt-2">SỬA MÃ KHUYẾN MÃI</h4>
<form action="{{route('admin.promotion.post-edit-promotion',[$promotion->id,\Crypt::encryptString($promotion->created_by)])}}" method="post" id="add-promotion">
  @csrf
  <div class="row m-0 p-3">

    <div class="col-12 p-0 ">
     <label>Kiểu khuyến mãi</label>
     <select class="form-control" id="exampleFormControlSelect1" name="promotion_type">
      @if($promotion->promotion_type == 0)
      <option value="0" selected="selected">-% Thanh toán bằng express coin</option>
      <option value="1">+% Nạp tiền</option>
      @else
      <option value="0" >-% Thanh toán bằng express coin</option>
      <option value="1" selected="selected">+% Nạp tiền</option>
      @endif
    </select>
  </div>
  @if($promotion->is_all ==0)
  <div class="col-12 p-0" style="margin-top: -15px">
    <div class="row m-0">
      <div class="col-6 p-0">
        <div class="support-vay">
         <label class="form-control-409 mr-2">
          <input  type="checkbox"   name="is_all" >
        </label>
        <label>Áp dụng cho tất cả thành viên</label>
      </div>

    </div>

  </div>
</div>
@else
<div class="col-12 p-0" style="margin-top: -15px;display: none">
  <div class="row m-0">
    <div class="col-6 p-0">
      <div class="support-vay">
       <label class="form-control-409 mr-2">
        <input  type="checkbox"   name="is_all" checked="">
      </label>
      <label>Áp dụng cho tất cả thành viên</label>
    </div>

  </div>

</div>
</div>
@endif


<div class="add-category col-12 p-0 mtdow10">

  <div class="row m-0 mt-3">
    <div class="col-12 col-md-6 col-lg-6 p-0 pr-3 pdx0i mb-3 mt20u" >
      <div class="">
        <label>Số lần dùng tối đa</label>
        @if($errors->has('num_use'))
        <small class="text-danger mt-1 float-right" style="font-size: 90%">
          {{$errors->first('num_use')}}
        </small>
        @endif
        <input class="form-control required" type="number" name="num_use" value="{{$promotion->num_use}}">
      </div>
      <span class="text-example">Mã khuyến mãi này sẽ có thể được dùng tối đa bao nhiêu lần?</span>
    </div>
    <div class="col-12 col-md-6 col-lg-6 p-0 pl-3 pdx0i mb-3">
      <div class="">
        <label>Phần trăm áp dụng</label>
        @if($errors->has('value'))
        <small class="text-danger mt-1 float-right" style="font-size: 90%">
          {{$errors->first('value')}}
        </small>
        @endif
        <div class="d-flex">
          <div class="w-100">
            <input class="form-control required" type="number" name="value" value="{{$promotion->value}}">
          </div>
          <div class="promotion-pertion text-center font-weight-bold">
            %
          </div>
        </div>

      </div>

    </div>
    <div class="col-12 col-md-6 col-lg-6 p-0 pr-3 mt-2 pdx0i mb-3">
      <div class="">
        <label>Thời gian bắt đầu hiển thị</label>
        <div class="search-reponsive col-12 p-0">
         <input id="handleDateFrom"  name="date_from" class="start_day form-control float-left" type="datetime-local" value="{{\Carbon\Carbon::parse($promotion->date_from)->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d\Th:i')}}">
       </div>
     </div>
     <span class="text-example">Để trống hiện tức thì</span>
   </div>
   <div class="col-12 col-md-6 col-lg-6 p-0 pl-3 mt-2 pdx0i mb-3">
    <div class="">
      <label>Thời gian kết thúc hiển thị</label>
      <input id="handleDateTo" name="date_to" class="end_day form-control float-left" type="datetime-local" placeholder="Ngày bắt đầu"  value="{{\Carbon\Carbon::parse($promotion->date_to)->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d\Th:i')}}">
      <div id="appendDateError"></div>
    </div>

    <span class="text-example" >Để trống là 10 năm</span>
  </div>
  <div class="col-12 text-center">
    <button type="submit" class="btn btn-primary" style="border-radius: 0;">Hoàn tất</button>
  </div>
</div>

</div>
</div>
</form>
</section>
<!-- /.content -->
@endsection
@section('Script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('system/js/validate/add-promotion.validate.js') }}"></script>
<script src="js/table.js"></script>

<script type="text/javascript">

 $(document).ready(function(){
  $("#s2id_autogen1").keyup(function(){
    $('#checkall').removeAttr('Checked','Checked');
    $('#checkprivate').removeAttr('Checked','Checked');
  });

  $("#s2id_autogen1").click(function(){
   if($('#select2-search-choice div').text()!= null){
    $('#checkall').removeAttr('Checked','Checked');
    $('#checkprivate').removeAttr('Checked','Checked');
  }
});

});
setMinMaxDate('#handleDateFrom', '#handleDateTo')
</script>

@endsection

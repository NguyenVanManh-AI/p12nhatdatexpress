@extends('Admin.Layouts.Master')
@section('Title', 'Thêm mã khuyến mãi | Mã khuyến mãi')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel='stylesheet' href='https://cdn.jsdelivr.net/select2/3.4.8/select2.css'>
<style type="text/css">
    .chosen-container-multi .chosen-choices{
        padding:  4px 5px 8px 8px !important;
        height: 38px !important;
        background-image: none !important;
    }
</style>
@endsection
@section('Content')
<!-- content -->
<section>

    <div class="row m-0 px-3 pt-3">

        <ol class="breadcrumb mt-1">
            <li class="recye px-2 pt-1  check ">
                <a href="{{route('admin.promotion.list-promotion')}}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
            @if($check_role == 1  ||key_exists(5, $check_role))
                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 pt-1 ml-1">
                    <a href="{{route('admin.promotion.trash')}}">
                        Thùng rác
                    </a>
                </li>
            @endif
            @if($check_role == 1  ||key_exists(1, $check_role))
                <li class="ml-2 phay mr-2 ">
                    /
                </li>
                <li class="add px-2 pt-1 ml-1 check active">

                    <i class="fa fa-edit mr-1"></i>Thêm

                </li>
            @endif
        </ol>
    </div>
    <h4 class="text-center font-weight-bold mt-2">THÊM MÃ KHUYẾN MÃI</h4>
    <form action="{{route('admin.promotion.post-add-promotion')}}" method="post" id="add-promotion">
        @csrf
        <div class="row m-0 p-3">

            <div class="col-12 p-0">
                <label>Số lượng mã tạo ra</label>
                @if($errors->has('quanlity_code'))
                    <small class="text-danger mt-1 float-right" style="font-size: 90%">
                        {{$errors->first('quanlity_code')}}
                    </small>
                @endif
                <input class="form-control required" type="number" name="quanlity_code" value="1">
                <span class="promotion-warring">Lưu ý: khi dùng chức năng này! Hệ thống sẽ tự động tạo ra 1 lượng lớn(số nhập vào) mã số tương ứng với cấu hình bên dưới chỉ khác nhau về mã số!</span>
            </div>
            <div class="col-12 p-0 mt-2">
                <label>Kiểu khuyến mãi</label>
                <select class="form-control" id="exampleFormControlSelect1" name="promotion_type">
                    <option value="0">-% Thanh toán bằng express coin</option>
                    <option value="1">+% Nạp tiền</option>
                </select>
            </div>
            <div class="col-12 p-0" style="margin-top: -15px">
                <div class="row m-0">
                    <div class="col-6 p-0">
                        <div class="support-vay">
                            <label class="form-control-409 mr-2">
                                <input id="checkall" type="radio" value="is_all" name="radio_button" checked="">
                            </label>
                            <label>Áp dụng cho tất cả thành viên</label>
                        </div>

                    </div>
                    <div class="col-6 p-0">
                        <div class="support-vay">
                            <label class="form-control-409 mr-2">
                                <input id="checkprivate" type="radio" value="is_private" name="radio_button">
                            </label>
                            <label>Áp dụng cho khuyến mãi nhận tại trang</label>
                        </div>

                    </div>

                </div>
            </div>

            <div class="col-12 p-0 mt-3">
                <label>Thành viên áp dụng</label>
                <div class="form-group select-tags">
                    <select name="list_users[]" class="chosen" data-order="true" id="multiselect" multiple="true"
                            style="height: 38px">
                        @foreach ($list_users as $item )
                            <option value="{{$item->id}}">[ID: {{$item->id}}] {{$item->username}}</option>
                        @endforeach
                    </select>
                    <span class="promotion-warring">Lưu ý: khi dùng chức năng này! Hệ thống sẽ tạo ra số mã theo số lượng thành viên mà không phụ thuộc vào số lượng mã nhập bên trên, mã sẽ áp dụng cho từng đối tượng cụ thể!</span>
                </div>
            </div>
            <div class="add-category col-12 p-0 mtdow10">
                <div class="row m-0 mt-3">
                    <div class="col-12 col-md-6 col-lg-6 p-0 pr-3 pdx0i mb-3 mt20u">
                        <div class="">
                            <label>Số lần dùng tối đa</label>
                            @if($errors->has('num_use'))
                                <small class="text-danger mt-1 float-right" style="font-size: 90%">
                                    {{$errors->first('num_use')}}
                                </small>
                            @endif
                            <input class="form-control required" type="number" name="num_use" value="1">
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
                                    <input class="form-control required" type="number" name="value" value="10">
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
                                <div style="position: relative">
                                    <input id="handleDateFrom" name="date_from"
                                           class="start_day form-control float-left" type="datetime-local">
                                </div>
                            </div>
                        </div>
                        <span class="text-example">Để trống hiện tức thì</span>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 p-0 pl-3 mt-2 pdx0i mb-3">
                        <div class="">
                            <label>Thời gian kết thúc hiển thị</label>
                            <div style="position: relative">
                                <input id="handleDateTo" name="date_to" class="end_day form-control float-left"
                                       type="datetime-local" placeholder="Ngày bắt đầu">

                            </div>

                            <div id="appendDateError"></div>
                        </div>
                        <span class="text-example">Để trống là 10 năm</span>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary float-right" style="border-radius: 0;">Hoàn tất
                        </button>
                    </div>
                    <div class="col-6">
                        <div class="btn btn-secondary" style="border-radius: 0;" onclick="resetForm()">Làm lại</div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</section>
<!-- /.content -->
@endsection
@section('Script')
<script src='https://cdn.jsdelivr.net/select2/3.4.8/select2.min.js'></script>
<script type="text/javascript">
  $("#e1").select2({
    placeholder: "Select2 input",
    allowClear: true,

  // match strings that begins with (instead of contains):
  matcher: function(term, text) {
    return text.toUpperCase().indexOf(term.toUpperCase())==0;
  }
});
</script>
<script src="js/table.js"></script>

<script type="text/javascript">
  $('#multiselect').change(function(){
    if(this.value != ""){
     $('#checkall').attr('checked', false);
     $("#checkall").attr("disabled", true);
     $('#checkprivate').attr('checked', false);
     $("#checkprivate").attr("disabled", true);
   }else{
    $('#checkall').prop('checked', true);
    $("#checkall").attr("disabled", false);

    $("#checkprivate").attr("disabled", false);
    console.log("checked")
  }
})

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" integrity="sha512-0nkKORjFgcyxv3HbE4rzFUlENUMNqic/EzDIeYCgsKa/nwqr2B91Vu/tNAu4Q0cBuG4Xe/D1f/freEci/7GDRA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script type="text/javascript">
  $(".chosen").chosen({
    width: "300px",
    enable_search_threshold: 10
  }).change(function(event)
  {
    if(event.target == this)
    {
      var value = $(this).val();
      $("#result").text(value);
    }
  });
  $('.chosen-search-input').val("Nhập danh sách")
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('system/js/validate/add-promotion.validate.js') }}"></script>
@endsection

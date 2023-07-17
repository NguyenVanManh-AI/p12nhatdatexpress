@extends('Admin.Layouts.Master')
@section('Title', 'Gửi thư | Hòm thư')
@section('Style')
{{-- link css project chính --}}
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
{{-- link css toastr --}}
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
{{-- link js toastr --}}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
{{-- màu hiển thị thanh toastr --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel='stylesheet' href='https://cdn.jsdelivr.net/select2/3.4.8/select2.css'>

<style type="text/css">
  #error{background: red;}
</style>

@endsection
@section('Content')
<section>
  <style type="text/css">
    .chosen-container-multi .chosen-choices{
      padding:  4px 5px 8px 8px !important;
      height: 38px !important;
      background-image: none !important;
      border: 1px solid #ccc !important;
    }
    .chosen-container-active .chosen-choices{
      box-shadow: none !important
    }

  </style>

  <style type="text/css">

    .select2-container {
      height: auto;
    }
    .select2-container .select2-choice{
      height: 36px !important;
      border-radius: 0px;
      background-image: none;
      padding: 5px 0 0 8px;
      box-shadow: none !important;
      background: #fff !important;
      border: 0px;
    }
    .select2-container .select2-choice .select2-arrow{
      background: #fff;
      border-radius: 0;
      background-image: none;
      border: 0;
    }
    .select2-container .select2-choice .select2-arrow b {
      margin-top: 4px;
    }
    .select2-no-results {
     display: none !important;
   }
   .select2-container-multi.select2-container-active .select2-choices{
    box-shadow: none;
  }
  .disble-input,.disble-input-send-date{
    position: absolute;background: #ccc;width: 100%;height: 38px;z-index: 999;opacity: 0.3;display: none
  }
  .input-box{
    position: relative
  }
</style>

<div>
  <div class="col-sm-12 mbup30">
    <div class="row m-0 px-2 pt-3">
      <ol class="breadcrumb mt-1">
        <li class="recye px-2 pt-1 check">
          <a href="{{route('admin.mailbox.list')}}">
            <i class="fa fa-th-list mr-1"></i>Danh sách
          </a>
        </li>
        {{-- kiểm tra phân quyền thùng rác --}}
        @if($check_role == 1  ||key_exists(5, $check_role))
        <li class="phay ml-2 " style="margin-top: 2px !important">
          /
        </li>
        <li class="recye px-2 pt-1 ml-1">
          <a href="{{route('admin.mailbox.trash')}}">
            Thùng rác
          </a>
        </li>
        @endif
        {{-- kiểm tra phân quyền thêm --}}
        @if($check_role == 1  ||key_exists(1, $check_role))
        <li class="ml-2 phay">
          /
        </li>
        <li class="add px-2 pt-1 ml-1 check active">
          <i class="fa fa-edit mr-1"></i>Thêm
        </li>
        @endif
      </ol>
    </div>
  </div><!-- /.col -->
  <h4 class="text-center font-weight-bold mb-2 mt-2">GỬI THƯ</h4>
  <div class="px-2">
    @if($check_role == 1  || key_exists(1, $check_role))
    <form id="add-mail-box" method="post" action="{{route('admin.mail.post-add')}}">
      @csrf
      <div class="row m-0 px-2 pb-3">
        <div class="col-12 p-0">
          <div class="row m-0">
            <div id="campent-box" class="  col-12 col-sm-12 col-md-12 col-lg-12 p-0">
              <div class="w-100 p-0 mt-3 br1 mb-4" >
                <div class="col-12 p-0">
                  <div class="w-100 bar-box ">
                    <p class="text-center mb-0 font-weight-bold text-white">ĐỐI TƯỢNG NHẬN THƯ</p>
                  </div>
                </div>
                <div class="col-12  px-3 pt-2">
                  <label>Nhập ID hoặc tên thành viên <span class="required"></span></label>
                  <div class="form-group select-tags" style="margin-bottom: 5px">
                    <select name="list_users[]" class="chosen" data-order="true"  id="multiselect" multiple="true" style="height: 38px" >
                      @foreach ($list_users as $item )
                      <option value="{{$item->id}}">[ID: {{$item->id}}] {{$item->username}}</option>
                      @endforeach
                    </select>
                    <div id="chooseInput"></div>
                  </div>

                </div>

                <div class="col-12 py-2 ">
                  <div class="row m-0">
                  </div>
                </div>
              </div>
              <div class="w-100 p-0 br1 pb-2">
                <div class=" col-12 p-0">
                  <div class="w-100 bar-box">
                    <p class="text-center mb-0 font-weight-bold text-white">Soạn thư</p>
                  </div>
                </div>
                <div class="col-12  px-3 py-2">
                  <label>Nhập tiêu đề thư <span class="required"></span></label>
                  <input class="form-control required" type="text" name="title" placeholder="Nhập tiêu đề">
                </div>
                <div class="col-12  px-3 py-2">
                  <label>Nhập nội dung thư <span class="required"></span></label>
                 <textarea class="js-admin-tiny-textarea" style="width: 100%" name="content"></textarea>
                 <div id="editerInput"></div>
                </div>
              </div>

              <div class="row m-0 pb-3">
                <div class="col-6 mt-2">

                </div>
                <div class="col-12 mt-2 p-0 text-center">
                  <button type="submit" class="btn btn-primary" style="border-radius: 0;">Gửi thư</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    @endif

  </div>
</div>
</section>
<!-- /.content -->
@endsection
@section('Script')

<script type="text/javascript">
  $("#e1").select2({
    placeholder: "Select2 input",
    allowClear: true,
    matcher: function(term, text) {
      return text.toUpperCase().indexOf(term.toUpperCase())==0;
    }
  });
  $('.select2').select2()



</script>
<script type="text/javascript">
  var check =1;
  function notitication(){
    if(check ==1){
      toastr.error('Vui lòng kiểm tra các trường');
      check++;
    }
  }
</script>

{{-- editer --}}
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
<script src="{{ asset('system/js/validate/validate.validate.js') }}"></script>
@endsection

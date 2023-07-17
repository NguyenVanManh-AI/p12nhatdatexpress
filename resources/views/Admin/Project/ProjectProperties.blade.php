@extends("Admin.Layouts.Master")
@section('Title', 'Cấu hình dự án | Dự án')
@section('Content')
{{-- link css chính --}}
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
{{-- link css toastr --}}
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
{{-- link js toastr --}}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
{{-- màu hiển thị thanh toastr --}}
<style type="text/css">
  #error{background: red;}
</style>
<body>
  <div class="card-header border-bottom mt-3" style="border-bottom: 0px !important">
    <h5 class="m-0 text-center font-weight-bold" >CẤU HÌNH THUỘC TÍNH DỰ ÁN</h5>
  </div>
  <ul class="list-group list-group-flush" style="overflow-x: hidden;">
    <li class="list-group-item p-3">
      <div class="row m-0">
        <div class="col p-0">
          {{--kiểm tra phân quyền chức năng sửa --}}
          @if($check_role == 1  ||key_exists(2, $check_role))
          <form id="edit-properties" action="{{route('admin.project.post-project-properties')}}" method="post">
            @endif
            @csrf
            <div class="row m-0">
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                {{-- input tên dự án --}}
                <input class="form-control required mb-1" name="tenduan" value="{{($getProperties[0]->name)}}" > 
              </div>
               {{-- input giá bán --}}
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                <input class="form-control required mb-1" name="giaban" value="{{($getProperties[1]->name)}}"> 
              </div>
               {{-- input hướng nhà --}}
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                <input class="form-control required mb-1" name="huongnha" value="{{($getProperties[2]->name)}}"> 
              </div>
               {{-- input mô hình --}}
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                <input class="form-control required mb-1" name="mohinh" value="{{($getProperties[3]->name)}}"> 
              </div>
               {{-- input giá thuê --}}
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                <input class="form-control required mb-1" name="giathue" value="{{($getProperties[4]->name)}}"> 
              </div>
               {{-- input tên quy mô --}}
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                <input class="form-control required mb-1" name="quymo" value="{{($getProperties[5]->name)}}"> 
              </div>
               {{-- input tên chủ đầu tư --}}
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                <input class="form-control required mb-1" name="chudautu" value="{{($getProperties[6]->name)}}"> 
              </div>
               {{-- input diện tích --}}
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                <input class="form-control required mb-1" name="dientich" value="{{($getProperties[7]->name)}}"> 
              </div>
               {{-- input hỗ trợ vay --}}
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                <input class="form-control required mb-1" name="hotrovay" value="{{($getProperties[8]->name)}}"> 
              </div>
               {{-- input vị trí --}}
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                <input class="form-control required mb-1" name="vitri" value="{{($getProperties[9]->name)}}"> 
              </div>
               {{-- input pháp lý --}}
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                <input class="form-control required mb-1" name="phaply" value="{{($getProperties[10]->name)}}"> 
              </div>
               {{-- input đường --}}
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                <input class="form-control required mb-1" name="duong" value="{{($getProperties[11]->name)}}"> 
              </div>
               {{-- input tình trạng --}}
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                <input class="form-control required mb-1" name="tinhtrang" value="{{($getProperties[12]->name)}}"> 
              </div>
               {{-- input nội thất --}}
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                <input class="form-control required mb-1" name="noithat" value="{{($getProperties[13]->name)}}"> 
              </div>
               {{-- đăng ngay --}}
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                <input class="form-control required mb-1" name="dangngay" value="{{($getProperties[14]->name)}}"> 
              </div>
            </div>               
            <div class="text-center">
             {{--kiểm tra phân quyền chức năng sửa --}}
             @if($check_role == 1  ||key_exists(2, $check_role))
             <button type="submit" class="btn btn-success" onclick="appenHtml()" style="border-radius: 0;">Lưu</button>
             @endif
           </div>
           {{--kiểm tra phân quyền chức năng sửa --}}
           @if($check_role == 1  ||key_exists(2, $check_role))
         </form>
         @endif
       </div>
     </div>
   </ul>
 </body>
 @endsection
 @section('Style')

 @endsection
 @section('Script')
 <script>
    //hiển thị các thông báo lỗi
    @if(count($errors) > 0)
    toastr.error("Vui lòng kiểm tra các trường");
    @endif
  </script>
  <script type="text/javascript">
  // cấu hình thông báo toastr 
  $(document).ready(function() {
    toastr.options = {
      'closeButton': true,
      'debug': false,
      'newestOnTop': false,
      'progressBar': false,
      'positionClass': 'toast-top-right',
      'preventDuplicates': false,
      'showDuration': '1000',
      'hideDuration': '1000',
      'timeOut': '5000',
      'extendedTimeOut': '1000',
      'showEasing': 'swing',
      'hideEasing': 'linear',
      'showMethod': 'fadeIn',
      'hideMethod': 'fadeOut',
    }
  });
  var check =1;
  //hiển thị thông báo nếu validate không thành công
  function notitication(){
    if(check ==1){
      toastr.error('Vui lòng kiểm tra các trường');
      check++;
    } 
  }
</script>
{{-- token form laravel --}}
{{-- jquery validate --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- js validate --}}
<script src="{{ asset('system/js/validate/validate.validate.js') }}"></script>
@endsection

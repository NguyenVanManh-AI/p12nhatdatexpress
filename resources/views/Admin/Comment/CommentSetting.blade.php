@extends("Admin.Layouts.Master")
@section('Title', 'Thiết lập | Quản lý bình luận')
@section('Content')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
<body>

  <div class="card-header border-bottom mt-3" style="border-bottom: 0px !important">
    <h5 class="m-0 text-center font-weight-bold" >THIẾT LẬP</h5>
  </div>
  <ul class="list-group list-group-flush" style="overflow-x: hidden;">
    <li class="list-group-item p-3">
      <div class="row m-0">
        <div class="col p-0">
          @if($check_role == 1  ||key_exists(2, $check_role))
          <form id="setting-comment" action="{{route('admin.comment.post-comment-setting')}}" method="post">
            @csrf
            @endif
            
            <div class="row m-0">        
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2">
                <label>Số bình luận ngắt trang dự án </label>
                <input class="form-control required mb-1" type="number" name="ngattrangduan" value="{{$getProject->config_value}}" > 
              </div>
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2">
                <label>Số bình luận ngắt trang tin đăng </label>
                <input class="form-control required mb-1" type="number" name="ngattrangtindang" value="{{$getProject->config_value}}" > 
              </div>
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2">
                <label>Số bình luận ngắt trang bài viết </label>
                <input class="form-control required mb-1" type="number" name="ngattrangbaiviet" value="{{$getProject->config_value}}" > 
              </div>
            </div>    
            <div class="text-center">
              @if($check_role == 1  ||key_exists(2, $check_role))
              <button type="submit" class="btn btn-outline-success">Lưu</button>
              @endif
            </div>
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
    @if(count($errors) > 0)
    toastr.error("Vui lòng kiểm tra các trường");
    @endif
  </script>


  <script type="text/javascript">
    $('.set-active44').addClass('active');

  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" interity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="{{ asset('system/js/validate/validate.validate.js') }}"></script>

  @endsection

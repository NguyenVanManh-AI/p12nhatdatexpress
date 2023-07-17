
@extends("Admin.Layouts.Master")
@section('Title', 'Sửa cấp bậc | Quản lý cấp bậc')
@section('Content')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">

<body>
  <div class="col-sm-12 mbup30">
    <div class="row m-0 px-2 pt-3">
      <ol class="breadcrumb mt-1">
        <li class="recye px-2 pt-1 check">
          <a href="{{route('admin.rank.list')}}">
          <i class="fa fa-th-list mr-1"></i>Danh sách
        </a>
      </li>  
    </ol>
  </div>
</div>

  <div class="card-header border-bottom mt-1" style="border-bottom: 0px !important">
    <h4 class="m-0 text-center font-weight-bold" >SỬA CẤP BẬC</h4>
  </div>
  <ul class="list-group list-group-flush" style="overflow-x: hidden;">
    <li class="list-group-item p-3">
      <div class="row m-0">
        <div class="col p-0">
          <form id="add-rank-form" action="{{route('admin.rank.post-edit', [$item->id, $item->created_by])}}" method="post">
            @csrf
            <div class="row m-0">
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2">
                <label>Tiêu đề</label>
                <input class="form-control required mb-1" name="level_name" type="text" value="{{$item->level_name}}"> 
                @if($errors->has('level_name'))
                <small class="text-danger">
                    {{$errors->first('level_name')}}
                </small>
                @endif
              </div>
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2" >
                <label>Phần trăm ưu đãi</label>
                <input class="form-control required mb-1" name="percent_special" type="number"value="{{$item->percent_special}}"> 
                @if($errors->has('percent_special'))
                <small class="text-danger">
                    {{$errors->first('percent_special')}}
                </small>
                @endif
              </div>
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2"  >
                <label>Số tin tối thiểu</label>
                <input class="form-control required mb-1" name="classified_min_quantity" type="number" value="{{$item->classified_min_quantity}}"> 
                @if($errors->has('classified_min_quantity'))
                <small class="text-danger">
                    {{$errors->first('classified_min_quantity')}}
                </small>
                @endif
              </div>
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2" >
                <label>Số tin tối đa</label>
                <input class="form-control required mb-1" name="classified_max_quantity" type="number" value="{{$item->classified_max_quantity}}"> 
                @if($errors->has('classified_max_quantity'))
                <small class="text-danger">
                    {{$errors->first('classified_max_quantity')}}
                </small>
                @endif
              </div>
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2" >
                <label>Số tiền nạp tối thiểu</label>
                <input class="form-control required mb-1" name="deposit_min_amount" id="typeNumber" type="text" value="{{$item->deposit_min_amount}}"> 
                @if($errors->has('deposit_min_amount'))
                <small class="text-danger">
                    {{$errors->first('deposit_min_amount')}}
                </small>
                @endif
              </div>
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2" >
                <label>Số tiền nạp tối đa</label>
                <input class="form-control required mb-1" name="deposit_max_amount" id="typeNumber2" type="text" value="{{$item->deposit_max_amount}}"> 
                @if($errors->has('deposit_max_amount'))
                <small class="text-danger">
                    {{$errors->first('deposit_max_amount')}}
                </small>
                @endif
              </div>   
              <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2">

                <div class="form-group">
                  <label for="image_url">Khung ảnh</label>
                  <div class="mb-2" style="width: 100%;height: 200px;background-image: url('../system/images/icons/png-background.jpg');background-size: 100%">
                    <img id="image_priview" src="{{$item->image_url}}" height="100%" style="object-fit: cover">
                  </div>
                  <div class="input-group">
                    <div style="position: relative;width: 100%">
                      <div class="input-group-prepend clear-input" style="cursor: pointer;height: 38px;position: absolute" onclick="resetValue()">
                        <label class="input-group-text" for="validatedInputGroupSelect">
                          <img src="images/icons/icon_clear.png" >
                        </label>
                      </div>
                      <div class="input-group-prepend" data-toggle="modal" data-target="#exampleModal" style="cursor: pointer;height: 38px;position: absolute;left: calc(100% - 100px);" >
                        <label class="input-group-text" style="cursor: pointer;border-radius: none !important; ">
                          <span style="font-weight:300">Chọn hình</span>
                        </label>
                      </div>
                      <input onchange="getval()" class="form-control pl-5" id="image_url" name="image_url"  style="width: 100%;border-radius: .25rem" value="{{$item->image_url}}">
                    </div>
                  </div>
                  @if($errors->has('image_url'))
                  <small class="text-danger">
                    {{$errors->first('image_url')}}
                  </small>
                  @endif
                </div>
              </div>
              <script type="text/javascript">
                function resetValue(){
                  $('#image_url').val("");
                }
                function getval(){
                  $("#image_priview").attr("src",$('#image_url').val());
                }
              </script>

              
              <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document" style="max-width:80% !important;">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Chọn ảnh</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <iframe id="filemanager" src="/responsive_filemanager/filemanager/dialog.php?type=1&field_id=image_url" style="width: 100%;height: 60vh"></iframe>
                    </div>             
                  </div>
                </div>
              </div>
            </div>    
            <div class="text-center">
              @if($check_role == 1  ||key_exists(2, $check_role))
              <button type="submit" class="btn btn-outline-success">Lưu</button>
              @endif
            </div>
          </form>
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  {{-- <script src="{{ asset('system/js/validate/validate.validate.js') }}"></script> --}}
  <script>
    var formatter = new Intl.NumberFormat('it-IT', {
      style: 'currency',
      currency: 'VND',
    });
    document.querySelector('#typeNumber').addEventListener('change', (e)=>{
      if(isNaN(e.target.value)){
          e.target.value = ''
      }else{
        e.target.value = formatter.format(e.target.value);
      }
    })
    document.querySelector('#typeNumber2').addEventListener('change', (e)=>{
      if(isNaN(e.target.value)){
          e.target.value = ''
      }else{
        e.target.value = formatter.format(e.target.value);
      }
    })

    $(document).ready(function(){
      $('#typeNumber').val(formatter.format( $('#typeNumber').val()));
      $('#typeNumber2').val(formatter.format( $('#typeNumber2').val()));
    });
    </script>
  @endsection

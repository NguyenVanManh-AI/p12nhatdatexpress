
@extends("Admin.Layouts.Master")
@section('Title', 'Thêm viền thủ công | Quản lý cấp bậc')
@section('Content')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel='stylesheet' href='https://cdn.jsdelivr.net/select2/3.4.8/select2.css'>
<body>
  <div class="card-header border-bottom mt-1" style="border-bottom: 0px !important">
    <h4 class="m-0 text-center font-weight-bold" >THÊM VIỀN THỦ CÔNG</h4>
  </div>
  <ul class="list-group list-group-flush" style="overflow-x: hidden;">
    <li class="list-group-item p-3">
      <div class="row m-0">
        <div class="col p-0">
          <form id="add-border-form" action="{{route('admin.rank.post-add-border')}}" method="post">
            @csrf
            <div class="row m-0">
              <div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6 mb-2">
                 <label>Tài khoản áp dụng</label>
                 <div class="form-group select-tags">
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
                  <select name="list_users[]" class="chosen" data-order="true"  id="multiselect" multiple="true" style="height: 38px" >
                    @foreach ($list_users as $item )
                    <option value="{{$item->id}}">[ID: {{$item->id}}] {{$item->username}}</option>
                    @endforeach
                  </select>
                  <div id="errorInput"></div> 

                </div>
            </div>             
            <div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6 mb-2">
              <div class="form-group">
                <label for="image_url">Khung ảnh</label>
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
                    <input class="form-control pl-5" id="image_url" name="image_url"  style="width: 100%;border-radius: .25rem">
                  </div>
                </div>
                @if($errors->has('image_url'))
                <small class="text-danger error-message-custom">
                  {{$errors->first('image_url')}}
                </small>
                @endif
              </div>
            </div>
            <script type="text/javascript">
              function resetValue(){
                $('#image_url').val("");
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
            @if($check_role == 1  ||key_exists(1, $check_role))
            <button type="submit" class="btn btn-outline-success">Hoàn tất</button>
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

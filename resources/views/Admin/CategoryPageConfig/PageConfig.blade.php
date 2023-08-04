
@extends("Admin.Layouts.Master")

@section('Title', 'Cấu hình trang chuyên mục | Trang chuyên mục')

@section('Content')
  <div class="card-header border-bottom mt-3" style="border-bottom: 0px !important">
    <h5 class="m-0 text-center font-weight-bold" >CẤU HÌNH TRANG CHUYÊN MỤC</h5>
  </div>
  <ul class="list-group list-group-flush" style="overflow-x: hidden;">
    <li class="list-group-item p-3">
      <div class="row m-0">
        <div class="col p-0">
          <form action="{{route('admin.category-page-config.post-page-config')}}" method="post" enctype="multipart/form-data">
            @csrf
              <div class="form-row">
                  <div class="form-group col-md-6">
                      <div class="box image-first-desktop">
                          <h3 class="box-header">Ảnh đầu trang trên Desktop</h3>
                          <div class="inner">
                              <div class="border-dashed">
                                  <div class="form-group text-center">
                                      <select name="group" id="image_banner" class="custom-select">
                                          <option value="">--- Chuyên mục ---</option>
                                      @foreach($group as $item)
                                          <option value="{{$item->id}}" data-image="{{$item->image_banner}}">{{$item->group_name}}</option>
                                              @if($item->has('children'))
                                                  @foreach($item->children as $i)
                                                      <option value="{{$i->id}}"  data-image="{{$i->image_banner}}">--- {{$i->group_name}}</option>
                                                      @if($i->has('children'))
                                                          @foreach($i->children as $ii)
                                                              <option value="{{$ii->id}}"  data-image="{{$ii->image_banner}}">------ {{$ii->group_name}}</option>
                                                          @endforeach
                                                      @endif
                                                  @endforeach
                                              @endif
                                      @endforeach
                                      </select>
                                  </div>
                                  <div class="choose-image">
                                      <img src="{{asset('system/image/upload-file.png')}}" alt="">
                                      <span class="desc d-block">Kéo & Thả ảnh tại đây!</span>
                                      <span class="btn btn-upload">Tải ảnh lên</span>
                                      <input name="image_banner" type="file" accept="image/*" onchange="loadFile(event,'preview_banner','#image_banner_new','#image_banner_old')">
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="form-group col-md-6">
                      <div class="box image-first-mobile">
                          <h3 class="box-header">Ảnh đầu trang trên Mobile</h3>
                          <div class="inner">
                              <div class="border-dashed">
                                  <div class="form-group text-center">
                                      <select name="group_mobile" id="image_banner_mobile" class="custom-select">
                                          <option value="">--- Chuyên mục ---</option>
                                          @foreach($group as $item)
                                              <option value="{{$item->id}}"  data-image="{{$item->image_banner_mobile}}">{{$item->group_name}}</option>
                                              @if($item->has('children'))
                                                  @foreach($item->children as $i)
                                                      <option value="{{$i->id}}"  data-image="{{$i->image_banner_mobile}}">--- {{$i->group_name}}</option>
                                                      @if($i->has('children'))
                                                          @foreach($i->children as $ii)
                                                              <option value="{{$ii->id}}"  data-image="{{$ii->image_banner_mobile}}">------ {{$ii->group_name}}</option>
                                                          @endforeach
                                                      @endif
                                                  @endforeach
                                              @endif
                                          @endforeach
                                      </select>
                                  </div>
                                  <div class="choose-image">
                                      <img src="{{asset('system/image/upload-file.png')}}" alt="">
                                      <span class="desc d-block">Kéo & Thả ảnh tại đây!</span>
                                      <span class="btn btn-upload">Tải ảnh lên</span>
                                      <input name="image_banner_mobile" type="file" accept="image/*"  onchange="loadFile(event,'preview_banner_mobile','#image_banner_mobile_new','#image_banner_mobile_old')">
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="form-row mt-1">
                  <div class="form-group col-md-6">
                      <div id="image-first-desktop" class="contain-photo">
                            <div class="box-image " id="image_banner_old" style="display: none">
                                <img class="object-contain h-200px" src="" alt="">
{{--                              <span class="close"><i class="fas fa-times"></i></span>--}}
                            </div>

                          <div class="box-image " style="display: none" id="image_banner_new">
                            <img  id="preview_banner" class="object-contain h-200px">
                            <span class="close" data-id="#image_banner_new"><i class="fas fa-times"></i></span>
                          </div>
                      </div>
                  </div>
                  <div class="form-group col-md-6">
                      <div id="image-first-mobile" class="contain-photo">
                            <div class="box-image " id="image_banner_mobile_old" style="display: none">
                                <img class="object-contain h-200px" src="" alt="">
                              {{--                              <span class="close"><i class="fas fa-times"></i></span>--}}
                            </div>

                          <div class="box-image " style="display: none" id="image_banner_mobile_new">
                              <img id="preview_banner_mobile" class="object-contain h-200px">
                              <span class="close" data-id="#image_banner_mobile_new"><i class="fas fa-times"></i></span>
                          </div>
                      </div>
                  </div>
              </div>
            <div class="form-row m-0">
              <div class="form-group col-md-12">
                <label for="feFirstName">Văn bản giới thiệu chuyên mục cuối trang</label>
                @if($errors->has('info_category'))
                <small class="text-danger mt-1 float-right" style="font-size: 90%">
                  {{$errors->first('info_category')}}
                </small>
                @endif

                <textarea spellcheck="false" id="text_category" name="info_category" class="form-control" style="display: none"></textarea>
                <div  style="width: 100%;height: auto;">
                  <div class="p-3" spellcheck="false" id="appenHtml" contenteditable="true" name="info_category" style="width: 100%;height: 100%;outline: none;border:1px solid #ccc;border-radius: 3px;">
                   {!!($admin_config[1]->config_value)!!}
                 </div>
               </div>
            </div>
            <div class="form-group col-md-12">
              <label for="feFirstName">Văn bản giới thiệu vị trí cuối trang</label>
              @if($errors->has('info_location'))
              <small class="text-danger mt-1 float-right" style="font-size: 90%">
                {{$errors->first('info_location')}}
              </small>
              @endif
              <textarea spellcheck="false" id="info_location" name="info_location" class="form-control" style="display: none"></textarea>
              <div  style="width: 100%;height: auto;">
                <div class="p-3" spellcheck="false" id="appenHtml2" contenteditable="true" name="info_category" style="width: 100%;height: 100%;outline: none;border:1px solid #ccc;border-radius: 3px;">
                 {!!($admin_config[2]->config_value)!!}
               </div>
             </div>

             <script type="text/javascript">
                 function appenHtml(){
                  var htmlAppend = $('#appenHtml').html();
                  $('#text_category').val(htmlAppend);

                  var htmlAppend2 = $('#appenHtml2').html();
                  $('#info_location').val(htmlAppend2);
                }
              </script>

           </div>
           <div class="form-group col-md-4">
            <label for="feLastName">Danh sách tin rao</label>
            @if($errors->has('list_post'))
            <small class="text-danger mt-1 float-right" style="font-size: 90%">
              {{$errors->first('list_post')}}
            </small>
            @endif
            <input  class="form-control" name="list_post" placeholder=""
            value="{{$admin_config[0]->config_value}}">
            @if($errors->has('youtube'))
            <small id="passwordHelp" class="text-danger">
              {{$errors->first('youtube')}}
            </small>
            @endif
          </div>
        </div>

        @if($check_role == 1 || key_exists(2, $check_role))
        <div class="text-center">
          <button type="submit" class="btn btn-outline-success" onclick="appenHtml()">Lưu</button>
        </div>
        @endif
      </form>
    </div>
  </div>
</ul>
@endsection
@section('Style')
<style>
    .image-first-desktop .box-header {
        background-color: #0054a6!important;
    }
    .image-first-mobile .box-header {
        background-color: #f26c4f!important;
    }
    .box>.box-header {
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 700;
        text-align: center;
        color: #fff;
        line-height: 18px;
        padding: 10px;
        background-color: #333;
    }
    .box>.inner {
        padding: 8px;
        border: 1px solid #b7b7b7;
    }
    .border-dashed {
        border: 1px dashed #d7d7d7;
        padding: 15px;
    }
    .box .inner select {
        width: 210px;
        margin: auto;
    }
    .custom-select {
        -moz-appearance: none;
        -webkit-appearance: none;
        appearance: none;
        background: none;
        position: relative;
        background: #fff url(../images/caret-down.png) right 0.75rem center/8px 7px no-repeat;
    }
    .custom-select, .form-control {
        font-size: 14px;
        border-radius: unset;
    }
    .choose-image .desc {
        color: #64bbf0;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.4px;
    }
    .choose-image {
         border: none;
     }
     .choose-image {
        border: 1px solid #d7d7d7;
        height: 114px;
        text-align: center;
        position: relative;
    }
    .choose-image input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .choose-image .btn-upload {
        border-radius: 3px;
        background-color: #00a8ec;
        color: #fff;
        padding: 6px 8px;
        margin-top: 5px;
        line-height: 1;
    }
    .close{
        position: absolute;
        top: 3px ;
        right: 10px;
    }
</style>
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
<script>
    $('#image_banner').change(function(){
        var old_image = $(this).find(':selected').attr('data-image');
       // alert(old_image)
        $('#preview_banner').attr('src',null);
        $('#image_banner_new').hide();
        if(!old_image){
            $('#image_banner_old').hide();
        }
        else{
            $('#image_banner_old').show();
            $('#image_banner_old img').attr('src','{{asset('')}}'+old_image);
        }

    });
    $('#image_banner_mobile').change(function(){
        var old_image = $(this).find(':selected').attr('data-image');
        // alert(old_image)
        $('#preview_banner_mobile').attr('src',null);
        $('#image_banner_mobile_new').hide();
        if(!old_image){
            $('#image_banner_mobile_old').hide();
        }
        else{
            $('#image_banner_mobile_old').show();
            $('#image_banner_mobile_old img').attr('src','{{asset('')}}'+old_image);
        }

    });
</script>
<script>
    var loadFile = function(event,id,id_display,id_hide) {
        $(id_hide).hide();
        $(id_display).show();
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById(id);
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
    $('.close').click(function (){
        $($(this).data('id')).hide();
    });
</script>
@endsection

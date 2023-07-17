@extends('Admin.Layouts.Master')
@section('Title', 'Chỉnh sửa bài viêt | Hướng dẫn')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/css/selectize.default.min.css'>
@endsection
@section('Content')
    <section>
        <div class="row m-0 px-3 pt-3">
            <h4>Chỉnh sửa bài viết hướng dẫn</h4>
            <ol class="breadcrumb mt-1">
                @if($check_role == 1  ||key_exists(4, $check_role))    
                <li class="list-box px-2 pt-1 check">
                    <a href="{{route('admin.guide.list')}}">
                        <i class="fa fa-th-list mr-1"></i>Danh sách
                    </a>
                </li>
                @endif
                @if($check_role == 1  ||key_exists(8, $check_role))    
                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 pt-1 ml-1">
                    <a href="{{route('admin.guide.trash')}}">
                        Thùng rác
                    </a>
                </li>
                @endif
               
            </ol>
        </div>
  <form action="{{route('admin.guide.edit',[$guide->id,\Crypt::encryptString($guide->created_by)])}}" method="POST" enctype="multipart/form-data" >
    @csrf
        <div class="row m-0 px-2 pb-3">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                <label>Tiêu đề <span class="required"></span></label>
                <input class="form-control required" type="" name="guide_title" value="{{($guide->guide_title)}}"   >
                @if($errors->has('guide_title'))
                <small  class="text-danger">
                    {{$errors->first('guide_title')}}
                </small>
            @endif
            </div>
           
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2 ">
                <div class="row">
                    <div class="col-4">
                        <label>Ảnh đại diện <span class="required"></span></label><br>
                        <div class="d-flex">
                            <div class="button-choose-image text-center " style="width: 75%;border:1px solid #ccc">
                                <p>Chọn hình ảnh</p>
                                <input accept="image/*" type='file' id="imgInp" style="width: 100%" name='file'>
                            </div>
                        </div>    
                    </div>
                    <div class="col-8">
                        <div class="show-img-main">
                            <img class="ml d-none" id="blah" src="#" alt="your image"   />
                            <img class="ml d-block" id="old" src="{{asset($guide->image_url)}}" alt="your image" />
                            <span class="remove close" aria-label="Close">×</span>
                        </div>
                    </div>
                    {{-- <div class="col-6">
                        <img  class="ml-3" id="blah" src="#" alt="your image"  width="25%" style="display: none" />
                    </div> --}}
                    {{-- <div class="col-lg-2 col-md-6"> --}}
                        <label>&nbsp;</label>
                        <br />
                        {{-- <div class="form-row add-gallery justify-content-center" id="add-image" > --}}
                        {{-- <div class="show-img-main">
                            <img class="ml-3 d-none" id="blah" src="#" alt="your image"   />
                            <img class="ml-3 d-block" id="old" src="{{asset($guide->image_url)}}" alt="your image" />
                            <span style="display: none" class="remove close" aria-label="Close">×</span>
                        </div> --}}
                        {{-- </div> --}}
                    {{-- </div> --}}
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                <div class="support-vay2">
                    <label class="form-control-409 mr-2">
                        <input {{($guide->guide_type == "N")?"checked":""}} type="checkbox" name="checked_guide">
                    </label>
                    <p>Hiển thị ở mục hướng dẫn</p>
                </div>
            </div>
           
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
            </div>
            

            <div class="col-12  p-2">
                <label>Nội dung <span class="required"></span></label>
            <textarea class="js-admin-tiny-textarea"  name="guide_content" value="" style="width: 100%;height: 300px">{{($guide->guide_content)}}</textarea>
            @if($errors->has('guide_content'))
            <small  class="text-danger">
            {{$errors->first('guide_content')}}
            </small>
            @endif
            </div>
        
            <div class="col-6 mt-2">
                <button type="submit" class="btn btn-primary float-right" style="border-radius: 0;">Hoàn tất</button>
            </div>
            <div class="col-6 mt-2">
                <button type="reset" class="btn btn-secondary" style="border-radius: 0;">Làm lại</button>
            </div>
        </div>
    </form>
        </div>  





        </div>

   

    </section>
    <!-- /.content -->
@endsection
@section('Script')
    <script src="js/table.js"></script>
    {{-- load audio --}}
    <script type="text/javascript">
        function handleFiles(event) {
            var files = event.target.files;
            $("#src").attr("src", URL.createObjectURL(files[0]));
            document.getElementById("audio").load();
        }
        document.getElementById("upload").addEventListener("change", handleFiles, false);
    </script>




    {{-- input tag --}}
    <script type="text/javascript">
        $("#input-tags").selectize({
            delimiter: ",",
            persist: false,
            plugins: ["remove_button"],
            create: function(input) {
                return {
                    value: input,
                    text: input
                };
            }
        });
    </script>

    <script>
        @if(count($errors) > 0)
        toastr.error("Vui lòng kiểm tra các trường");
        @endif
       
      
    </script>

    <script type="text/javascript">
        $('#huongdan').addClass('active');
        $('#nav-huongdan').addClass('menu-is-opening menu-open');
    </script>
 <script type="text/javascript">
    imgInp.onchange = evt => {
        const [file] = imgInp.files
        if (file) {
            blah.src = URL.createObjectURL(file)
            $('#blah').removeClass('d-none');
            $('#old').addClass('d-none');
            $('#old').removeClass('d-block');
            $('.close').removeClass('d-none');
        }
    }
    $('.close').on('click',function(){
        $('#blah').addClass('d-none');
        $('#imgInp').val('');
        $('#old').addClass('d-none');
        $('#old').removeClass('d-block');
        $(this).addClass('d-none');
    });

</script>

@endsection
@section('Style')
<style>
.close {
    display: inline-block;
    font-size: 1rem;
    opacity: 1;
    line-height: 18px;
    text-align: center;
    width: 17px;
    height: 17px;
    border-radius: 50%;
    background-color: #919191;
    cursor: pointer;
    color: #fff;
    position: absolute;
    top: 27px;
    right: 15px;
    z-index: 1;}
    .ml-3, .mx-3 {
    margin-left: -1rem!important;
}
</style>
@endsection

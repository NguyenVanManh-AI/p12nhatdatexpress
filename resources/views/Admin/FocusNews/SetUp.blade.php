@extends('Admin.Layouts.Master')

@section('Title', 'Thiết lập | Tiêu điểm')

@section('Content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group w-100 p-2 my-2">
                            <label for="">{{$image_express->config_name}}</label>
                            {{-- <textarea name="{{$image_express->config_code}}" id="{{$image_express->config_code}}" class="js-admin-tiny-textarea">{{}}</textarea> --}}

                                <div style="margin-top:20px" class="text-center">
                                    <div id="preview-old" class="preview">
                                        <img style="width: 500px;" src="{{asset($image_express->config_value)}}" alt="preview image" class="img-fluid"/>
                                    </div>

                                    <div id="preview-new" class="preview">
                                        <img style="width: 500px;" id="previewImg" src="" alt="preview image" class="img-fluid"/>
                                    </div>

                                    <div class="upload-avatar btn bg-blue-light">
                                        <i class="fas fa-upload"></i> Tải lên ảnh quảng cáo
                                        <input  type="file" name="file" id="inputAvatar" accept="image/*">

                                    </div>
                                    {{-- <button type="submit" class="btn bg-success"><i class="fas fa-plus-circle"></i> Thêm</button> --}}
                                </div>
                            </div>

                        <div class="form-group w-100 p-2 my-2">
                            <label for="">{{$url_express->config_name}}</label>
                            <textarea name="{{$url_express->config_code}}" id="{{$url_express->config_code}}" class="form-control" rows="1">{{$url_express->config_value}}</textarea>
                        </div>

                        <div class="form-group w-100 p-2 my-2">
                            <label for="">{{$gg->config_name}} <span class="text-muted font-weight-normal">(Nếu bỏ trống sẽ hiển thị hình ảnh)</span></label>
                            <textarea name="{{$gg->config_code}}" id="{{$gg->config_code}}" class="form-control" rows="7">{{$gg->config_value}}</textarea>
                        </div>

                        <div class="col-12 d-flex justify-content-center mb-4">
                            <button class="btn btn-primary no-border no-radius">Lưu</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('Script')
    <script>
        document.getElementById('previewImg').style.display = 'none'
        $(document).ready( () => {
            const inputAvatar = document.getElementById('inputAvatar')
            const previewAvatar = document.getElementById('previewImg')
            $("#inputAvatar").change(function () {
                $('#preview-new').show();
                $('#preview-old').hide();
                var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
                if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) === -1) {
                    alert("Các định dạng cho phép : "+fileExtension.join(', '));
                    $("#inputAvatar").val("");
                    previewAvatar.style.display = 'none'
                    return;
                }
                const [file] = inputAvatar.files
                if (file) {
                    previewAvatar.style.display = 'unset'
                    previewAvatar.style.marginBottom = '15px'
                    previewAvatar.src = URL.createObjectURL(file)
                }
            });
        })
    </script>
    <script>
        $('.inputimage').change(function(){
            var id  =$(this).data('id');
            const inputAvatar = document.getElementById('input'+id);
            const previewAvatar = document.getElementById('preview'+id);
            const [file] = inputAvatar.files
            if (file) {
                        // previewAvatar.style.display ='block';
                        previewAvatar.src = URL.createObjectURL(file);
                    }

        });
        $('.savechange').click(function(){
            var id = $(this).data('id');
            var created_by = $(this).data('created_by');
            var urlpost = "admin/manage-admin/updateavatar/"+id+"/"+created_by;
            event.preventDefault();
            var formData = new FormData();
            var file = $('.file'+id).prop('files')[0];
            formData.append("file",file);
            $.ajax
            ({
                url: "/"+urlpost,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'post',
                processData: false,
                contentType: false,
                data: formData,
                success: function(result)
                {
                    // toastr.success("Cập nhật ảnh đại diện thành công");
                    location.reload();
                    // setTimeout(()=>{location.reload()},50);

                },
                error: function(data)
                {
                    console.log(data);
                }
            });
        });
    </script>
@endsection
@section('Style')
<style>
.upload-avatar {
    position: relative;
}
.upload-avatar input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    object-fit: cover;
}
.bg-blue-light {
    background-color: #00bff3 !important;
    color: #fff !important;
}
.bg-blue-light:hover{
    text-decoration: underline;
}
.img-fluid {
    max-width: 100%;
    height: auto;
    margin-bottom: 20px;
    /*margin-right: 248px;*/
}
</style>

@endsection

@extends('Admin.Layouts.Master')
@section('Content')

<div class="block-dashed">
    <h3 class="title">thêm tài khoản quản trị</h3>

    <div class="form-group col-md-4">
      </div>
    <form action="{{route('admin.manage.updateavatar',[$admin->id,\Crypt::encryptString($admin->created_by)])}}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="text-center">
            <div class="preview">
                <img style="max-width: 100px; margin: 0 auto" id="previewImg" src="/system/img/avatar-admin/{{$admin->image_url}}" alt="preview image" class="img-fluid"/>
            </div>
            <div class="upload-avatar btn mt-3 mb-3 bg-blue-light">
                <i class="fas fa-upload"></i> Tải lên ảnh đại diện
                <input  type="file" name="file" id="inputAvatar" accept="image/*">
            </div>
            <div>
                <button type="submit" class="btn bg-success"><i class="fas fa-plus-circle"></i> Lưu </button>

            </div>
            <div>
               <button type="submit" class="btn bg-success mt-3 "> Quay lại </button>
            </div>
        </div>
    </form>
</div>

@endsection
@section('Style')
<style>
    .block-dashed .title {
    display: inline-block;
    font-size: 18px;
    padding: 0 20px;
    background-color: #fff;
    font-weight: 500;
    line-height: 1;
    margin-bottom: 0;
    text-transform: uppercase;
    color: #000;
    position: absolute;
    top: -9px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1;
}

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
}
.bg-blue-light {
    background-color: #00bff3 !important;
    color: #fff !important;
}
.bg-blue-light:hover{
    text-decoration: underline;
}

</style>
@endsection
@section('Script')
<script>
    document.getElementById('previewImg').style.display = 'none'
    $(document).ready( () => {
        const inputAvatar = document.getElementById('inputAvatar')
        const previewAvatar = document.getElementById('previewImg')
        $("#inputAvatar").change(function () {
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) === -1) {
                alert("Các định dạng cho phép : "+fileExtension.join(', '));
                $("#inputAvatar").val("");
                previewAvatar.style.display = 'none'
                return;
            }
            const [file] = inputAvatar.files
            if (file) {
                previewAvatar.style.display = 'block'
                previewAvatar.style.marginBottom = '15px'
                previewAvatar.src = URL.createObjectURL(file)
            }
        });
    })

</script>
@endsection

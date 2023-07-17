@extends('Admin.Layouts.Master')

@section('Title', 'Sửa tài khoản quản trị')

@section('Content')
<section class="content">
    <div class="container-fluid">
        <!-- Main row -->

        <div class="block-dashed">
            <h3 class="title"> Sửa tài khoản quản trị</h3>
            <form action="{{route('admin.manage.editaccount',[$admin->id,\Crypt::encryptString($admin->created_by)])}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <input type="text" name="admin_username" class="form-control" value="{{$admin->admin_username}}" placeholder="Tên đăng nhập">
                        @if($errors->has('admin_username'))
                        <small  class="text-danger">
                            {{$errors->first('admin_username')}}
                        </small>
                    @endif
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" name="admin_fullname" class="form-control" value="{{$admin->admin_fullname}}" placeholder="Tên hiển thị">
                        @if($errors->has('admin_fullname'))
                        <small  class="text-danger">
                            {{$errors->first('admin_fullname')}}
                        </small>
                    @endif
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" name="admin_email" class="form-control admin_email" value="{{$admin->admin_email}}"  placeholder="Email cá nhân" >
                        @if($errors->has('admin_email'))
                        <small  class="text-danger">
                            {{$errors->first('admin_email')}}
                        </small>
                    @endif
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <input type="password" name="admin_password" class="form-control dontcoppy" placeholder="Mật khẩu" autocomplete="new-password">
                        @if($errors->has('admin_password'))
                        <small  class="text-danger">
                            {{$errors->first('admin_password')}}
                        </small>
                    @endif

                    </div>
                    <div class="form-group col-md-4">
                        <input type="password" name="password_confirmation" class="form-control dontcoppy" placeholder="Nhập lại mật khẩu">
                        @if($errors->has('password_confirmation'))
                        <small  class="text-danger">
                            {{$errors->first('password_confirmation')}}
                        </small>
                    @endif
                    </div>
                    <div class="form-group col-md-4">
                        <select name="rol_id" class="custom-select">
                            <option selected disabled>Loại tài khoản</option>
                            @if(\Illuminate\Support\Facades\Auth::guard('admin')->user()->admin_type == 1)
                                <option value="-1" {{ $admin->admin_type == 1 || old('rol_id') == -1 ? 'selected' : ''}}>Tài khoản quản trị cao cấp</option>
                            @endif
                            @foreach ($roles as $role)
                                <option value="{{$role->id}}" {{$role->id ==$admin->rol_id || $role->id == old('rol_id') ? 'selected' : ''}}>{{$role->role_name}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('rol_id'))
                        <small  class="text-danger">
                            {{$errors->first('rol_id')}}
                        </small>
                    @endif
                    </div>
                    <div class="form-group col-md-4">
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_customer_care" class="form-check-input" value="1" id="is_customer_care" {{old('is_customer_care') || $admin->is_customer_care ? "checked" : ""}}>
                            <label class="form-check-label text-muted" for="is_customer_care">Tài khoản chăm sóc khách hàng</label>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <div class="preview mb-2">
                        <img style="max-width: 100px;" id="previewImg" src="/system/img/avatar-admin/{{$admin->image_url}}" alt="Không thể tải ảnh" class="img-fluid"/>
                    </div>
                    <div class="upload-avatar btn bg-blue-light">
                        <i class="fas fa-upload"></i> Tải lên ảnh đại diện
                        <input  type="file" name="file" id="inputAvatar" accept="image/*">
                    </div>

                    <button type="submit" class="btn bg-success"><i class="fas fa-plus-circle"></i> Cập nhật  </button>

                </div>
            </form>
        </div>
</div>

 </div>
</div>
        <!-- /Main row -->
    </div><!-- /.container-fluid -->
</section>
@endsection
@section('Style')
<style>
.block-dashed {
    margin: 30px 0;
    padding: 30px 30px 14px;
    position: relative;
    border: 1px dashed #c2c5d6;
}
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

.content .table thead th {
    background-color: #034076;
    color: #fff;
    line-height: 1;
    font-weight: 400;
}

.content .table th, .content .table td {
    border: 1px solid #b7b7b7;
    text-align: center;
    font-size: 14px;
    color: #0d0d0d;
    vertical-align: middle;
    position: relative;
}
.content .table th, .content .table td {
    border: 1px solid #b7b7b7;
    text-align: center;
    font-size: 14px;
    color: #0d0d0d;
    vertical-align: middle;
    position: relative;
}
.content .table td .setting-item {
    display: block;
    color: #0090ff;
    font-size: 14px;
    text-align: left;
    min-width: 150px;
}
.content .table .setting-item:hover{
    text-decoration: underline;
}
.content .table td .setting-item.delete {
    color: #ff0000;
}
.manipulation .custom-select {
    background-color: #347ab6;
    color: #fff;
}
.table-bottom .custom-select {
    height: 32px;
}
.custom-select, .form-control {
    font-size: 14px;
}
.table-bottom .display {
    width: 125px;
}
.table-bottom .display span {
    flex: 0 0 60px;
    max-width: 60px;
    font-size: 14px;
}
.table-bottom .view-trash a i {
    color: #000;
    margin-right: 10px;
}
.table-bottom .count-trash {
    display: inline-block;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: #ff0000;
    color: #fff;
    font-size: 11px;
    text-align: center;
    line-height: 15px;
    margin-left: 5px;
}
.table-bottom .count-item {
    background-color: #eeeeee;
    font-size: 14px;
    padding: 7px 10px;
    border: 1px solid #dddddd;
    border-radius: 3px 0 0 3px;
    color: #004d79;
    height: 38px;
}
.content .user-info .avatar {
    position: relative;
    display: inline-block;
}
.content .table .user-info .avatar img {
    width: 60px;
    height: 60px;
    border-width: 3px;
}

.content .user-info .avatar img {
    width: 125px;
    height: 125px;
    border-radius: 50%;
    border: 5px solid #e9f2f4;
}
.content .table .user-info .avatar .edit-avatar {
    width: 20px;
    height: 20px;
    font-size: 10px;
    line-height: 20px;
}

.content .user-info .avatar .edit-avatar {
    position: absolute;
    top: 0;
    right: 0;
    width: 30px;
    height: 30px;
    line-height: 30px;
    border-radius: 50%;
    text-align: center;
    background-color: #e9ebf2;
    border: 1px solid #d7d7d7;
    color: #70747f;
    cursor: pointer;
}
.preview {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 200px;
}
.preview img{
    display: block;
    width: 100%;
    height: auto;
}

</style>

@endsection
@section('Script')
    <script src="js/table.js"></script>
<script>
    jQuery(document).ready(function ($) {
        $(function () {
            if ($('#previewImg').attr('src') == null) {
                document.getElementById('previewImg').style.display = 'none'
            }

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
            function testImage(URL) {
                var tester=new Image();
                tester.onload=imageFound;
                tester.onerror=imageNotFound;
                tester.src=URL;
            }

            function imageFound() {
                document.getElementById('previewImg').style.display = 'block'
            }
            function imageNotFound() {
                document.getElementById('previewImg').style.display = 'none'
            }

            testImage("/system/img/avatar-admin/{{$admin->image_url}}");
        })
    });
</script>
<script>
        @if(count($errors) > 0)
        toastr.error("Vui lòng kiểm tra các trường");
        @endif
    </script>
    <script>
        $('.dontcoppy').bind('cut copy paste', function(e) {
             e.preventDefault();
             // alert("");
         });
         $('.admin_email').change(function(){
             $('.admin_email').val($('.admin_email').val().toLowerCase());
         });
     </script>
@endsection

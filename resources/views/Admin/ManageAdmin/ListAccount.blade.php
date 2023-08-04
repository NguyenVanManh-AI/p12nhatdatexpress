@extends('Admin.Layouts.Master')

@section('Title', 'Danh sách tài khoản | Tài khoản quản trị')

@section('Content')
<section class="content">
    <div class="container-fluid">
        <!-- Main row -->
        @if($check_role == 1  || key_exists(1, $check_role))
        <div class="block-dashed">
            <h3 class="title">thêm tài khoản quản trị</h3>
            <div class="form-group col-md-4">
            </div>
            <form action="{{route('admin.manage.addaccount')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <input  type="text" name="admin_username" class="form-control" placeholder="Tên đăng nhập" value="{{old('admin_username')}}">
                        @if($errors->has('admin_username'))
                        <small  class="text-danger">
                            {{$errors->first('admin_username')}}
                        </small>
                        @endif

                    </div>
                    <div class="form-group col-md-4">
                        <input value="{{old('admin_fullname')}}" type="text" name="admin_fullname" class="form-control" placeholder="Tên hiển thị">
                        @if($errors->has('admin_fullname'))
                        <small  class="text-danger">
                            {{$errors->first('admin_fullname')}}
                        </small>
                        @endif
                    </div>

                    <div class="form-group col-md-4">
                        <input value="{{old('admin_email')}}"  type="text" name="admin_email" class="form-control admin_email" placeholder="Email cá nhân" >
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
                         <option value="-1" {{ old('rol_id') == -1 ? 'selected' : ''}}>Tài khoản quản trị cao cấp</option>
                         @endif
                         @foreach ($roles as $role)
                         <option value="{{$role->id}}" {{ $role->id == old('rol_id') ? 'selected' : ''}}>{{$role->role_name}}</option>
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
                            <input type="checkbox" name="is_customer_care" value="1" class="form-check-input" id="is_customer_care" {{old('is_customer_care') ? "checked" : ""}}>
                            <label class="form-check-label text-muted" for="is_customer_care">Tài khoản chăm sóc khách hàng</label>
                        </div>
                    </div>

            </div>
            <div class="text-center">
                <div class="preview">
                    <img style="max-width: 100px;" id="previewImg" src="" alt="preview image" class="img-fluid"/>

                </div>
                <div class="upload-avatar btn bg-blue-light">
                    <i class="fas fa-upload"></i> Tải lên ảnh đại diện
                    <input  type="file" name="file" id="inputAvatar" accept="image/*">

                </div>
                <button type="submit" class="btn bg-success"><i class="fas fa-plus-circle"></i> Thêm</button>
            </div>
            @if($errors->has('file'))
            <div class="text-center">
                <small style="margin-left:-10%" class="text-danger text-center">
                    {{$errors->first('file')}}
                </small>
            </div>
            @endif
        </form>
    </div>
    @endif
        <table class="table  table-custom" id="table" >
            <thead>
                <tr>
                    <th><input type="checkbox" class="select-all"></th>
                    <th>STT</th>
                    <th>Tên hiển thị</th>
                    <th>Email</th>
                    <th>Loại tài khoản</th>
                    <th>Ngày tham gia</th>
                    <th>Cài đặt</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_account as $item )
                <tr>
                    <td>
                        <input type="checkbox" class="select-item" value="{{$item->id}}" name="select_item[]">
                        <input type="hidden" class="select-item" value="{{\Crypt::encryptString($item->created_by)}}" name="select_item_created[{{$item->id}}]">
                    </td>
                    <td>{{ $item->id }}</td>
                    <td>
                        <div class="user-info text-center">
                            <div class="avatar">
                                <img src="/system/img/avatar-admin/{{$item->image_url}}" alt="">
                                <div class="edit-avatar">
                                    {{-- <a href="{{route('admin.manage.updateavatar',[$item->id, \Crypt::encryptString($item->created_by)])}}"> --}}
                                        <a data-toggle="modal" data-target="#exampleModal{{$item->id}}" class="upload-avatar-select">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Cập nhật ảnh đại diện</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img id="preview{{$item->id}}" >
                                                        <input style="object-fit: cover" accept="image/*" name="file" type="file" id="input{{$item->id}}" data-id="{{$item->id}}" class="form-control inputimage mt-3 file{{$item->id}}">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                                        <button  type="button"  data-id="{{$item->id}}"  data-created_by="{{ \Crypt::encryptString($item->created_by)}}"  class="btn btn-primary savechange">Cập nhật</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{$item->is_customer_care ? route('admin.manage.accounts.info', $item->id) : route('admin.manage.info_log', $item->id)}}" class="user-name d-block">{{$item->admin_fullname}}</a>

                            </div>
                        </td>
                        <td>{{$item->admin_email}}</td>
                        <td>{{$item->role_name == null ? ($item->admin_type == 1 ? 'Tài khoản quản trị cao cấp' : '') : $item->role_name}}</td>
                        <td>{{\Carbon\Carbon::parse($item->created_at)->format('d/m/Y')}}</td>
                        <td class="text-left">
                            @if($check_role == 1  ||key_exists(2, $check_role))
                                <div class="mb-2 ml-2">
                                    <span class="icon-small-size mr-1 text-dark">
                                        <i class="fas fa-cog"></i>
                                    </span>
                                    <a href="{{route('admin.manage.editaccount',[$item->id,\Crypt::encryptString($item->created_by)])}}" class="text-primary ">Chỉnh sửa</a>
                                </div>
                            @endif

                            <x-admin.delete-button
                                :check-role="$check_role"
                                url="{{ route('admin.manage.accounts.delete-multiple', ['ids' => $item->id]) }}"
                            />
                            {{-- maybe should check permission --}}
                            {{-- @if($check_role == 1)
                                <a
                                    class="btn btn-info btn-sm js-admin-account__view-log"
                                    data-id="{{ $item->id }}"
                                    href="javascript:void(0);"
                                >
                                    <i class="fas fa-file-alt"></i>
                                    Logs
                                </a>
                            @endif --}}
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

        {{-- @include('admin.accounts.partials._view-log-modal') --}}
        <x-admin.table-footer
          :check-role="$check_role"
          :lists="$list_account"
          :count-trash="$count_trash"
          view-trash-url="{{ route('admin.manage.accounts.trash') }}"
          delete-url="{{ route('admin.manage.accounts.delete-multiple') }}"
        />
    </div>
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
        object-fit: cover;
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
        text-decoration: none;
    }
    .content .table td .setting-item.delete {
        color: #ff0000;
        cursor: pointer;
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
        object-fit: cover;
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
    /*Dropdown*/
    .dropdown-custom{
        background-color: #337ab7;
        border-color: #2e6da4;
        color: #fff;
    }
    .dropdown-custom:hover{
        color: white;
        background-color: #286090;
        border-color:  #204d74;
    }

</style>

@endsection
@section('Script')
<script src="js/table.js"></script>
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

<script>
    @if(count($errors) > 0)
    toastr.error("Vui lòng kiểm tra các trường");
    @endif

    @if(Session::has('success'))
    toastr.success("Cập nhật ảnh đại diện thành công");
    @endif
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

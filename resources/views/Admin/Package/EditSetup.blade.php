@extends('Admin.Layouts.Master')

@section('Title', 'Chỉnh sửa gói tin')

@section('Content')
<section class="content">
    <div class="container-fluid">
        <!-- Main row -->
        {{-- @if($check_role == 1  || key_exists(1, $check_role)) --}}
        <div class="block-dashed">
            <h3 class="title">Chỉnh sửa gói tin</h3>
            <div class="form-group col-md-4">
            </div>
            <form action="{{route('admin.setup.edit',[$package->id,\Crypt::encryptString($package->created_by)])}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="">Tên gói tin</label>
                        <input  type="text" name="package_name" class="form-control" placeholder="Tên gói tin" value="{{ old('package_name', $package->package_name) }}">
                        @if($errors->has('package_name'))
                        <small  class="text-danger">
                            {{$errors->first('package_name')}}
                        </small>
                        @endif
                    </div>
                    <div class="form-group col-md-12">
                        <label for="">Loại gói tin</label>
                        <select name="package_type" class="custom-select">
                         <option selected disabled>Loại gói tin</option>
                         <option value="D" {{ ($package->package_type) == 'D' ? 'selected' : ''}}>Mặc định</option>
                         <option value="V" {{ ($package->package_type) == 'V' ? 'selected' : ''}}>Gói víp</option>
                         <option value="A" {{ ($package->package_type) == 'A' ? 'selected' : ''}}>Nâng cao</option>
                     </select>
                     @if($errors->has('package_type'))
                     <small  class="text-danger">
                        {{$errors->first('package_type')}}
                    </small>
                    @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Thời gian sử dụng</label>
                        <select name="duration_time" class="custom-select">
                            <option selected disabled>Thời gian sử dụng</option>
                            <option value="{{7*24*60*60}}" {{ $package->duration_time == 24*60*60? 'selected' : ''}}>Một ngày</option>
                            <option value="{{(24*60*60)}}" {{ $package->duration_time == 7*24*60*60? 'selected' : ''}}>Một tuần</option>
                            <option value="{{30*24*60*60}}" {{ $package->duration_time == 30*24*60*60? 'selected' : ''}}>Một tháng</option>
                            <option value="{{365*24*60*60}}" {{ $package->duration_time ==  365*24*60*60? 'selected' : ''}}>Một năm</option>
                        </select>
                        @if($errors->has('duration_time'))
                        <small  class="text-danger">
                            {{$errors->first('duration_time')}}
                        </small>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Giá thường</label>
                        <input value="{{(int)$package->price}}" type="text" name="price" class="form-control" placeholder="Giá thường">
                        @if($errors->has('price'))
                        <small  class="text-danger">
                            {{$errors->first('price')}}
                        </small>
                        @endif

                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Giá khuyến mãi</label>
                        <input value="{{(int)$package->discount_price}}" type="text" name="discount_price" class="form-control" placeholder="Giá khuyến mãi">
                        @if($errors->has('discount_price'))
                        <small  class="text-danger">
                            {{$errors->first('discount_price')}}
                        </small>
                        @endif

                    </div>
                </div>
                <div class="form-row">


                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Số tin thường</label>
                        <input value="{{$package->classified_nomal_amount}}" type="text" name="classified_nomal_amount" class="form-control" placeholder="Số tin thường">
                        @if($errors->has('classified_nomal_amount'))
                        <small  class="text-danger">
                            {{$errors->first('classified_nomal_amount')}}
                        </small>
                        @endif
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Số tin vip</label>
                        <input value="{{$package->vip_amount}}" type="text" name="vip_amount" class="form-control" placeholder="Số tin vip">
                        @if($errors->has('vip_amount'))
                        <small  class="text-danger">
                            {{$errors->first('vip_amount')}}
                        </small>
                        @endif
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Số tin nổi bật</label>
                        <input value="{{$package->highlight_amount}}" type="text" name="highlight_amount" class="form-control" placeholder="Số tin nổi bật">
                        @if($errors->has('highlight_amount'))
                        <small  class="text-danger">
                            {{$errors->first('highlight_amount')}}
                        </small>
                        @endif
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Số tin thường trong ngày</label>
                        <input value="{{$package->classified_per_day}}" type="text" name="classified_per_day" class="form-control" placeholder="Số tin thường trong ngày">
                        @if($errors->has('classified_per_day'))
                        <small  class="text-danger">
                            {{$errors->first('classified_per_day')}}
                        </small>
                        @endif
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Thời gian vip</label>
                        <select name="vip_duration" class="custom-select">
                            <option selected disabled>Thời gian vip</option>
                            <option value="{{(24*60*60)}}" {{$package->vip_duration == (24*60*60) ? 'selected' : ''}}>Một ngày</option>
                            <option value="{{(7*24*60*60)}}" {{ $package->vip_duration == (7*24*60*60)? 'selected' : ''}}>Một tuần</option>
                            <option value="{{(30*24*60*60)}}" {{$package->vip_duration == (30*24*60*60) ? 'selected' : ''}}>Một tháng</option>
                            <option value="{{(365*24*60*60)}}" {{$package->vip_duration == (365*24*60*60)? 'selected' : ''}}>Một năm</option>


                        </select>
                        @if($errors->has('vip_duration'))
                        <small  class="text-danger">
                            {{$errors->first('vip_duration')}}
                        </small>
                        @endif
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Thời gian nổi bật</label>
                        <select name="highlight_duration" class="custom-select">
                            <option selected disabled>Thời gian nổi bật</option>
                            <option  value="{{(24*60*60)}}" {{$package->highlight_duration == (24*60*60) ? 'selected' : ''}}>Một ngày</option>
                            <option value="{{(7*24*60*60)}}" {{ $package->highlight_duration == (7*24*60*60)? 'selected' : ''}}>Một tuần</option>
                            <option value="{{(30*24*60*60)}}" {{ $package->highlight_duration == (30*24*60*60)? 'selected' : ''}}>Một tháng</option>
                            <option value="{{(365*24*60*60)}}" {{ $package->highlight_duration == (365*24*60*60) ? 'selected' : ''}}>Một năm</option>


                        </select>
                        @if($errors->has('highlight_duration'))
                        <small  class="text-danger">
                            {{$errors->first('highlight_duration')}}
                        </small>
                        @endif
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <input type="checkbox"  {{$package->cus_mana == 1?"checked":""}} name="cus_mana">
                        <label for="">Quản lý khách hàng</label>
                    </div>

                    <div class="form-group col-md-4">
                        <input type="checkbox" {{$package->data_static == 1?"checked":""}} name="data_static">
                        <label for="">Thống kê dữ liệu</label>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <input type="checkbox" {{ old('best_seller', $package->best_seller) ? 'checked' : '' }} name="best_seller">
                        <label>Mua nhiều</label>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <div class="preview">
                    <img style="max-width: 200px; margin-bottom: 30px" id="previewImg" src="{{asset($package->qr_image)}}" alt="preview image" class="img-fluid"/>
                </div>

                @if($errors->has('qr_image'))
                    <div class="text-center">
                        <small class="text-danger text-center d-block mb-4">
                            {{$errors->first('qr_image')}}
                        </small>
                    </div>
                @endif

                <div class="upload-avatar btn bg-blue-light" data-toggle="modal" data-target="#modalFile">
                    <i class="fas fa-upload"></i> Tải lên ảnh QR
                    <input type="hidden" name="qr_image" id="qr_image" autocomplete="off" value="{{$package->qr_image}}">
                </div>
                <button type="submit" class="btn bg-success"><i class="fas fa-plus-circle"></i> Cập nhật</button>
                <a href="{{route('admin.setup.list')}}" class="btn btn-primary">Quay lại</a>
            </div>
        </form>
    </div>
    {{-- @endif --}}

   </div>

</div>

</div>
</section>


<!-- Modal IMAGE QR -->
<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFile" aria-hidden="true">
    <div class="modal-dialog modal-file" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn ảnh QR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe src="{{url('responsive_filemanager/filemanager/dialog.php')}}?multiple=0&type=1&field_id=qr_image" frameborder="0"
                        style="width: 100%; height: 70vh"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
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
    qr_image.onchange = evt => {
        previewImg.src = evt.target.value
        previewImg.style.display = 'block'
    }
</script>
@endsection

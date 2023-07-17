<div id="login" class="popup" style="display: block; top: 130px;">
    <div class="head blue">
        <img src="{{asset(SystemConfig::logo())}}" width="200px" height="60px" >
    </div>
    <div class="wrapper-register wrap active">
        <div class="log-content">
            <div class="type-switcher">
                <span>Chọn loại tài khoản</span>
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{asset('user/images/user_type/personal-logo.png')}}">
                        <div class="tag " data-type=".type-personal">Cá nhân</div>
                    </div>
                    <div class="col-md-4">
                        <img src="{{asset('user/images/user_type/agency-logo.png')}}">
                        <div class="tag" data-type=".type-agency">Chuyên viên tư vấn</div>
                    </div>
                    <div class="col-md-4">
                        <img src="{{asset('user/images/user_type/corp-logo.png')}}">
                        <div class="tag" data-type=".type-company">Doanh nghiệp</div>
                    </div>
                </div>
            </div>
            <div class="type-wrapper type-personal ">
                <div class="desc blue">
                    {!!$registerText->where('config_code', 'N008')->first()->config_value!!}
                </div>
                <div class="click-access">
                    <form action="{{route('user.choose-account-type')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="number" name="user_type" value="1" hidden>
                        <input type="submit" class="btn xacnhan" value="Xác nhận">
                    </form>
                </div>
                <div class="nitication-type-login"><span>!</span> Không thể thay đổi sau khi đã xác nhận</div>
            </div>
            <div class="type-wrapper type-agency">
                <div class="desc blue">
                    {!!$registerText->where('config_code', 'N011')->first()->config_value!!}
                </div>
                <div class="click-access">
                    <form action="{{route('user.choose-account-type')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="number" name="user_type" value="2" hidden>
                        <input type="submit" class="btn xacnhan" value="Xác nhận">
                    </form>
                </div>
                <div class="nitication-type-login"><span>!</span> Không thể thay đổi sau khi đã xác nhận</div>
            </div>
            <div class="type-wrapper type-company">
                <div class="desc blue">
                    {!!$registerText->where('config_code', 'N012')->first()->config_value!!}
                </div>
                <div class="img-show-upload">
                    <img id="previewHolder" alt="Uploaded Image Preview Holder" width="250px" height="250px"
                         style="border-radius:3px;border:5px solid red;"/>
                </div>
                <form action="{{route('user.choose-account-type')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="number" name="user_type" value="3" class="d-none">
                    <div class="click-access">
                        <div class="upload-btn-login">
                            Upload giấy phép kinh doanh <img
                                src="{{asset('user/images/upload-icon-white.png')}}">
                            <input type="file" name="upload-license" value="" id="filePhoto" accept="image/*"
                                   class="required borrowerImageFile" data-errormsg="PhotoUploadErrorMsg" required>
                        </div>
                        <input type="submit" class="btn xacnhan" value="Xác nhận">
                    </div>
                </form>
                <div class="nitication-type-login"><span>!</span> Không thể thay đổi sau khi đã xác nhận</div>
            </div>
        </div>
    </div>
</div>
<div class="over-lay-popup"></div>
<div class="over-lay-1"></div>

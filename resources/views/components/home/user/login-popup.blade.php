<div id="login" class="popup">
    <div class="head">
        {{-- old should change --}}
        <div style="width: 200px;height: 60px">
            <img src="{{ asset(SystemConfig::logo()) }}" class="object-cover">
        </div>
        <div class="close-button"><i class="fas fa-times"></i></div>
    </div>

    <!-- Choosing Login - Register content-->
    <div class="log-switcher">
        <div class="log-switch {{old('user_type')?null:'active'}}" data-wrap=".wrapper-login">Đăng nhập</div>
        <div class="log-switch  {{old('user_type')?'active':null}}" data-wrap=".wrapper-register">Đăng ký</div>
    </div>

    <!-- Login content -->
    <div class="wrapper-login wrap {{old('user_type')?null:'active'}}">
        <div class="form form-group">
            <form action="{{route('user.post-login')}}" method="post">
                @csrf
                <input type="text" name="username" class="bg-white form-control border-dark" placeholder="Tên đăng nhập" required>
                <input type="password" name="password" class="form-control bg-white border-dashed" placeholder="Mật khẩu" required>
                <div class="save">
                    <div class="left d-flex align-items-center">
                        <input class="mr-2" type="checkbox" name="remember" value="1" id="save-password">
                        <label for="save-password">Lưu mật khẩu</label>
                    </div>
                    <div class="right">
                        <a href="#" class="reset-password">Quên mật khẩu?</a>
                    </div>
                </div>
                <input type="submit" value="Đăng nhập">
                <div class="line">
                    <span>Hoặc</span>
                </div>
                <a href="{{route('login-social', 'facebook')}}">
                    <div class="social facebook">
                        <i class="fab fa-facebook-f"></i>
                        <span>Đăng nhập bằng Facebook</span>
                    </div>
                </a>
                <a href="{{route('login-social', 'google')}}">
                    <div class="social google">
                        <i class="fab fa-google"></i>
                        <span>Đăng nhập bằng Google</span>

                    </div>
                </a>
            </form>
        </div>
    </div>

    <!--Register content -->
    <div class="wrapper-register wrap {{old('user_type')?'active':null}}">
        <div class="log-content">
            <!--Type Account -->
            <div class="type-switcher">
                <span>Chọn loại tài khoản</span>
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{asset('user/images/user_type/personal-logo.png')}}">
                        <div class="tag {{(old('user_type') ?? 1) == 1? 'active' : null}}" data-type=".type-personal">Cá nhân</div>
                    </div>
                    <div class="col-md-4">
                        <img src="{{asset('user/images/user_type/agency-logo.png')}}">
                        <div class="tag {{old('user_type') == 2? 'active' : null}}" data-type=".type-agency">Chuyên viên tư vấn</div>
                    </div>
                    <div class="col-md-4">
                        <img src="{{asset('user/images/user_type/corp-logo.png')}}">
                        <div class="tag {{old('user_type') == 3? 'active' : null}}" data-type=".type-company">Doanh nghiệp</div>
                    </div>
                </div>
            </div>

            <!--Personal Account -->
            <div class="type-wrapper type-personal {{old('user_type') == 1 || old('user_type') == null?'active':null}}">
                <div class="desc blue">
                    {!!$registerText->where('config_code', 'N008')->first()->config_value!!}
                </div>
                <div class="create-form">
                    <div class="form-title">Điền thông tin tài khoản</div>
                    <form action="{{route('user.post-register')}}" method="post">
                        @csrf
                        <div class="input-group">
                            <input type="number" name="user_type" value="1" hidden>
                            <div class="group group-1">
                                <label for="">Tên đăng nhập <span>*</span></label>
                                <input type="text" name="username"  class="bg-white" value="{{old('user_type') == 1?old('username'):null}}" required>
                                {{old('user_type') == 1?show_validate_error($errors, 'username'):null}}
                            </div>

                            <div class="group group-2">
                                <label for="">Tên hiển thị <span>*</span></label>
                                <input type="text" name="fullname" class="bg-white" value="{{old('user_type') == 1?old('fullname'):null}}" required >
                                {{old('user_type') == 1?show_validate_error($errors, 'fullname'):null}}
                            </div>

                            <div class="group group-3">
                                <label for="">Mật khẩu <span>*</span></label>
                                <input type="password" name="password" required>
                                {{old('user_type') == 1?show_validate_error($errors, 'password'):null}}
                            </div>

                            <div class="group group-4">
                                <label for="">Nhập lại mật khẩu <span>*</span></label>
                                <input type="password" name="re_password" class="bg-white" required>
                                {{old('user_type') == 1?show_validate_error($errors, 're_password'):null}}
                            </div>

                            <div class="group group-5">
                                <label for="">Số điện thoại <span>*</span></label>
                                <input type="tel" name="phone_number"  class="bg-white" value="{{old('user_type') == 1?old('phone_number'):null}}" required>
                                {{old('user_type') == 1?show_validate_error($errors, 'phone_number'):null}}
                            </div>

                            <div class="group group-6">
                                <label for="">Email <span>*</span></label>
                                <input type="email" name="email" class="bg-white" value="{{old('user_type') == 1?old('email'):null}}" required>
                                {{old('user_type') == 1?show_validate_error($errors, 'email'):null}}
                            </div>

                            <div class="group group-7">
                                <label for="">Ngày sinh <span>*</span></label>
                                <input type="date" name="birthday" class="bg-white" value="{{old('user_type') == 1?old('birthday'):null}}" required>
                                {{old('user_type') == 1?show_validate_error($errors, 'birthday'):null}}
                            </div>

                            <div class="group group-8">
                                <label for="">CMND/CCCD <span>*</span></label>
                                <input type="text" name="tax_number" class="bg-white" value="{{old('user_type') == 1?old('tax_number'):null}}" required>
                                {{old('user_type') == 1?show_validate_error($errors, 'tax_number'):null}}
                            </div>

                            <div class="group group-9" style="min-width: 170px!important;">
                                <label for="">Địa chỉ <span>*</span></label>
                                <select name="province" class="province select2"  data-placeholder="Tỉnh/thành phố" required>
                                    {{show_select_option($provinces, 'id', 'province_name', 'province', old('user_type') == 1?old('province'):null)}}
                                </select>
                                {{old('user_type') == 1?show_validate_error($errors, 'province'):null}}
                            </div>
                            <div class="group group-10" style="min-width: 170px!important;">
                                <label for="" class="blank">Blank</label>
                                <select name="district" class="district select2"  data-placeholder="Quận/huyện" required>
                                    {{show_select_option($districts, 'id', 'district_name', 'district', old('user_type') == 1?old('district'):null)}}
                                </select>
                                {{old('user_type') == 1?show_validate_error($errors, 'district'):null}}
                            </div>
                            <div class="group group-11" style="min-width: 170px!important;">
                                <label for="" class="blank">Blank</label>
                                <select name="ward" class="ward select2"  data-placeholder="Xã/phường" required>
                                    {{show_select_option($wards, 'id', 'ward_name', 'ward', old('user_type') == 1?old('ward'):null)}}
                                </select>
                                {{old('user_type') == 1?show_validate_error($errors, 'ward'):null}}
                            </div>
                        </div>
                        <div class="legend">
                            <div class="title">Bạn biết tới Nhadatexpress.vn từ đâu?</div>
                            <div class="radio-group">
                                <div class="group">
                                    <input type="radio" name="source" value="1" {{old('user_type') == 1 && old('source') == 1?'checked':null}}>
                                    <label for="google">Google (1000)</label>
                                </div>
                                <div class="group">
                                    <input type="radio" name="source" value="2" {{old('user_type') == 1 && old('source') == 3?'checked':null}}>
                                    <label for="facebook">Facebook (800)</label>
                                </div>
                                <div class="group">
                                    <input type="radio" name="source" value="3" {{old('user_type') == 1 && old('source') == 3?'checked':null}}>
                                    <label for="friend">Tiktok (300)</label>
                                </div>
                            </div>
                        </div>
                        <div class="captcha">
                            <strong>Kiểm tra bảo mật</strong>

                            <div class="mb-3 flex-between flex-wrap">
                                <div class="d-inline-block">
                                    <x-common.captcha
                                        show-error="{{ old('user_type') == 1 }}"
                                    />
                                </div>
                                <button class="btn btn-cyan flex-1 ml-2 my-2" type="submit">Đăng ký</button>
                            </div>

                            <div class="note">
                                <p>Chú ý:</p>
                                <p>Tên đăng nhập không bao gồm ký tự đặc biệt và dấu câu</p>
                                <p>Mật khẩu phải bao gồm: ít nhất 1 ký tự viết hoa và 1 chữ số</p>
                                <p><span>* Sau khi đăng ký vui lòng truy cập email để kích hoạt tài khoản</span></p>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!--Agency Account -->
            <div class="type-wrapper type-agency {{old('user_type') == 2?'active':null}}">
                <div class="desc blue">
                    {!!$registerText->where('config_code', 'N011')->first()->config_value!!}
                </div>
                <div class="create-form">
                    <div class="form-title">Điền thông tin tài khoản</div>
                    <form action="{{route('user.post-register')}}" method="post">
                        @csrf
                        <div class="input-group">
                            <input type="number" name="user_type" value="2" hidden>
                            <div class="group group-1">
                                <label for="">Tên đăng nhập <span>*</span></label>
                                <input type="text" name="username"  class="bg-white" value="{{old('user_type') == 2?old('username'):null}}" required>
                                {{old('user_type') == 2?show_validate_error($errors, 'username'):null}}
                            </div>
                            <div class="group group-2">
                                <label for="">Tên hiển thị <span>*</span></label>
                                <input type="text" name="fullname"  class="bg-white" value="{{old('user_type') == 2?old('fullname'):null}}" required >
                                {{old('user_type') == 2?show_validate_error($errors, 'fullname'):null}}
                            </div>
                            <div class="group group-3">
                                <label for="">Mật khẩu <span>*</span></label>
                                <input type="password" name="password" required>
                                {{old('user_type') == 2?show_validate_error($errors, 'password'):null}}
                            </div>
                            <div class="group group-4">
                                <label for="">Nhập lại mật khẩu <span>*</span></label>
                                <input type="password" name="re_password" required>
                                {{old('user_type') == 2?show_validate_error($errors, 're_password'):null}}
                            </div>
                            <div class="group group-5">
                                <label for="">Số điện thoại <span>*</span></label>
                                <input type="tel" name="phone_number"  value="{{old('user_type') == 2?old('phone_number'):null}}" required>
                                {{old('user_type') == 2?show_validate_error($errors, 'phone_number'):null}}
                            </div>
                            <div class="group group-6">
                                <label for="">Email <span>*</span></label>
                                <input type="email" name="email" value="{{old('user_type') == 2?old('email'):null}}" required>
                                {{old('user_type') == 2?show_validate_error($errors, 'email'):null}}
                            </div>
                            <div class="group group-7">
                                <label for="">Ngày sinh <span>*</span></label>
                                <input type="date" name="birthday" value="{{old('user_type') == 2?old('birthday'):null}}" required>
                                {{old('user_type') == 2?show_validate_error($errors, 'birthday'):null}}
                            </div>
                            <div class="group group-8">
                                <label for="">CMND/CCCD <span>*</span></label>
                                <input type="text" name="tax_number" class="bg-white" value="{{old('user_type') == 2?old('tax_number'):null}}" required>
                                {{old('user_type') == 2?show_validate_error($errors, 'tax_number'):null}}
                            </div>
                            <div class="group group-9" style="min-width: 170px!important;">
                                <label for="">Tỉnh/thành phố <span>*</span></label>
                                <select name="province" class="province select2"  data-placeholder="Tỉnh/thành phố" required>
                                    {{show_select_option($provinces, 'id', 'province_name', 'province', old('user_type') == 2?old('province'):null)}}
                                </select>
                                {{old('user_type') == 2?show_validate_error($errors, 'province'):null}}
                            </div>
                            <div class="group group-10" style="min-width: 170px!important;">
                                <label for="" class="blank">Blank</label>
                                <select name="district" class="district select2"  data-placeholder="Quận/huyện" required>
                                    {{show_select_option($districts, 'id', 'district_name', 'district', old('user_type') == 2?old('district'):null)}}
                                </select>
                                {{old('user_type') == 2?show_validate_error($errors, 'district'):null}}
                            </div>
                            <div class="group group-11" style="min-width: 170px!important;">
                                <label for="" class="blank">Blank</label>
                                <select name="ward" class="ward select2"  data-placeholder="Xã/phường" required>
                                    {{show_select_option($wards, 'id', 'ward_name', 'ward', old('user_type') == 2?old('ward'):null)}}
                                </select>
                                {{old('user_type') == 2?show_validate_error($errors, 'ward'):null}}
                            </div>
                        </div>
                        <div class="legend">
                            <div class="title">Bạn biết tới Nhadatexpress.vn từ đâu?</div>
                            <div class="radio-group">
                                <div class="group">
                                    <input type="radio" name="source" value="1" {{old('source') ==1?'checked':null}}>
                                    <label for="google">Google (1000)</label>
                                </div>
                                <div class="group">
                                    <input type="radio" name="source" value="2" {{old('source') ==2?'checked':null}}>
                                    <label for="facebook">Facebook (800)</label>
                                </div>
                                <div class="group">
                                    <input type="radio" name="source" value="3" {{old('source') ==3?'checked':null}}>
                                    <label for="friend">Bạn bè (300)</label>
                                </div>
                            </div>
                        </div>
                        <div class="captcha">
                            <strong>Kiểm tra bảo mật</strong>

                            <div class="mb-3 flex-between flex-wrap">
                                <div class="d-inline-block">
                                    <x-common.captcha
                                        show-error="{{ old('user_type') == 2 }}"
                                    />
                                </div>
                                <button class="btn btn-cyan flex-1 ml-2 my-2" type="submit">Đăng ký</button>
                            </div>

                            <div class="note">
                                <p>Chú ý:</p>
                                <p>Tên đăng nhập không bao gồm ký tự đặc biệt và dấu câu</p>
                                <p>Mật khẩu phải bao gồm: ít nhất 1 ký tự viết hoa và 1 chữ số</p>
                                <p><span>* Sau khi đăng ký vui lòng truy cập email để kích hoạt tài khoản</span></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!--Company Account -->
            <div class="type-wrapper type-company {{old('user_type') == 3?'active':null}}">
                <div class="desc blue">
                    {!!$registerText->where('config_code', 'N012')->first()->config_value!!}
                </div>
                <div class="create-form">
                    <div class="form-title">
                        Điền thông tin tài khoản
                    </div>
                    <form action="{{route('user.post-register')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="number" name="user_type" value="3" hidden>
                        <div class="input-group">
                            <div class="group group-1">
                                <label for="">Tên đăng nhập <span>*</span></label>
                                <input type="text" name="username" class="bg-white"  value="{{old('user_type') == 3?old('username'):null}}" required>
                                {{old('user_type') == 3?show_validate_error($errors, 'username'):null}}
                            </div>
                            <div class="group group-2">
                                <label for="">Tên công ty <span>*</span></label>
                                <input type="text" name="fullname" class="bg-white"  value="{{old('user_type') == 3?old('fullname'):null}}" required >
                                {{old('user_type') == 3?show_validate_error($errors, 'fullname'):null}}
                            </div>
                            <div class="group group-3">
                                <label for="">Mật khẩu <span>*</span></label>
                                <input type="password" name="password" class="bg-white" required>
                                {{old('user_type') == 3?show_validate_error($errors, 'password'):null}}
                            </div>
                            <div class="group group-4">
                                <label for="">Nhập lại mật khẩu <span>*</span></label>
                                <input type="password" name="re_password" class="bg-white" required>
                                {{old('user_type') == 3?show_validate_error($errors, 're_password'):null}}
                            </div>
                            <div class="group group-5">
                                <label for="">Số điện thoại <span>*</span></label>
                                <input type="tel" name="phone_number"  class="bg-white" value="{{old('user_type') == 3?old('phone_number'):null}}" required>
                                {{old('user_type') == 3?show_validate_error($errors, 'phone_number'):null}}
                            </div>
                            <div class="group group-6">
                                <label for="">Email <span>*</span></label>
                                <input type="email" name="email" class="bg-white" value="{{old('user_type') == 3?old('email'):null}}" required>
                                {{old('user_type') == 3?show_validate_error($errors, 'email'):null}}
                            </div>
                            <div class="group group-7">
                                <label for="">Website</label>
                                <input type="url" name="website" class="bg-white" value="{{old('user_type') == 3?old('website'):null}}" >
                                {{old('user_type') == 3?show_validate_error($errors, 'website'):null}}
                            </div>
                            <div class="group group-8">
                                <label for="">Mã số thuế <span>*</span></label>
                                <input type="text" name="tax_number" class="bg-white" value="{{old('user_type') == 3?old('tax_number'):null}}" required>
                                {{old('user_type') == 3?show_validate_error($errors, 'tax_number'):null}}
                            </div>
                            <div class="group group-9" style="min-width: 170px!important;">
                                <label for="">Địa chỉ <span>*</span></label>
                                <select name="province" class="province select2"  data-placeholder="Tỉnh/thành phố" required>
                                    {{show_select_option($provinces, 'id', 'province_name', 'province', old('user_type') == 3?old('province'):null)}}
                                </select>
                                {{old('user_type') == 3?show_validate_error($errors, 'province'):null}}
                            </div>
                            <div class="group group-10" style="min-width: 170px!important;">
                                <label for="" class="blank">Blank</label>
                                <select name="district" class="district select2"  data-placeholder="Quận/huyện" required>
                                    {{show_select_option($districts, 'id', 'district_name', 'district', old('user_type') == 3?old('district'):null)}}
                                </select>
                                {{old('user_type') == 3?show_validate_error($errors, 'district'):null}}
                            </div>
                            <div class="group group-11" style="min-width: 170px!important;">
                                <label for="" class="blank">Blank</label>
                                <select name="ward" class="ward select2"  data-placeholder="Xã/phường" required>
                                    {{show_select_option($wards, 'id', 'ward_name', 'ward', old('user_type') == 3?old('ward'):null)}}
                                </select>
                                {{old('user_type') == 3?show_validate_error($errors, 'ward'):null}}
                            </div>
                        </div>

                        <div class="file-input-group mb-4">
                            @include('components.common.partials._preview-image', [
                                'name' => 'upload-license',
                                'oldName' => 'old_upload-license',
                                'imageValue' => null
                            ])

                            <div class="file-input__upload upload mb-0">
                                <p>Tải lên giấy phép kinh doanh <span>*</span> (Bắt buộc)</p>
                                <div class="upload-btn-license">
                                    Upload <img src="{{asset('user/images/icon/upload-icon-white.png')}}">
                                </div>
                                <input type="file" name="upload-license" style="display: none;">
                            </div>
                            @if(old('user_type') == 3)
                                {{ show_validate_error($errors, 'upload-license') }}
                            @endif
                        </div>

                        {{-- <div class="upload-license-wrap d-none">
                            <img src="" class="upload-license">
                        </div>
                        <div class="upload">
                            <p>Tải lên giấy phép kinh doanh <span>*</span> (Bắt buộc)</p>
                            <div class="upload-btn-license">
                                Upload <img src="{{asset('user/images/icon/upload-icon-white.png')}}">
                            </div>
                            <input type="file" name="upload-license" style="display: none;">
                            {{old('user_type') == 3?show_validate_error($errors, 'upload-license'):null}}
                        </div> --}}
                        <div class="legend">
                            <div class="title">
                                Bạn biết tới Nhadatexpress.vn từ đâu?
                            </div>
                            <div class="radio-group">
                                <div class="group">
                                    <input type="radio" name="source" value="1" {{old('source') ==1?'checked':null}}>
                                    <label for="google">Google (1000)</label>
                                </div>
                                <div class="group">
                                    <input type="radio" name="source" value="2" {{old('source') ==2?'checked':null}}>
                                    <label for="facebook">Facebook (800)</label>
                                </div>
                                <div class="group">
                                    <input type="radio" name="source" value="3" {{old('source') ==3?'checked':null}}>
                                    <label for="friend">Bạn bè (300)</label>
                                </div>
                            </div>
                        </div>
                        <div class="captcha">
                            <strong>Kiểm tra bảo mật</strong>

                            <div class="mb-3 flex-between flex-wrap">
                                <div class="d-inline-block">
                                    <x-common.captcha
                                        show-error="{{ old('user_type') == 3 }}"
                                    />
                                </div>
                                <button class="btn btn-cyan flex-1 ml-2 my-2" type="submit">Đăng ký</button>
                            </div>

                            <div class="note">
                                <p>Chú ý:</p>
                                <p>Tên đăng nhập không bao gồm ký tự đặc biệt và dấu câu</p>
                                <p>Mật khẩu phải bao gồm: ít nhất 1 ký tự viết hoa và 1 chữ số</p>
                                <p><span>* Tài khoản doanh nghiệp sẽ được kiểm duyệt trong 24h. Sau khi được xét duyệt bộ phận CSKH sẽ liên hệ thông báo tới Khách hàng.</span></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="introduce-account-done">
    <div class="title-introduce-account-done">
        <h5>Đường dẫn giới thiệu</h5>
    </div>
    <div class="content-introduce-account-done">
        <a href="" class="link-need-copy">{{route('user-ref-link',$user_info->user_code)}}</a>
        <div class="group-button-introduce-account-done">
            <button type="button" class="btn copy-link-account">Sao chép</button>
            <a href="{{share_fb(route('user-ref-link',$user_info->user_code))}}" target="_blank" class="btn share-link-account">
                <i class="fab fa-facebook-f">&nbsp;</i>Chia sẻ
            </a>
        </div>
        <p>Người giới thiệu sẽ được nhận <span>{{SystemConfig::refPercent()}}%</span> giá trị coin khi người được giới thiệu nạp tiền vào tài khoản</p>
    </div>
</div>
<div class="introduce-account-done">
    <div class="title-introduce-account-done">
        <h5>Thông tin cá nhân</h5>
    </div>
    <div class="content-introduce-account-done">
        <form action="{{route('user.update-avatar')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="action-change-avatar">
                <div class="avatar-introduce-account-done">
                    <img class="avatar-introduce" src="{{asset($user_info->image_url??SystemConfig::USER_AVATAR)}}"  style="width: 100%; height: 100%;" alt="">
                    <input type="file" id="upload-img-ava" name="upload_cover">
                </div>
                <i class="fas fa-camera"></i>
            </div>
            <div class="info-introduce-account-done">
                <h5 class="introduce-account-done text-capitalize">{{$user_info->fullname}}</h5>
                <p  class="job-introduce-account-done">{{$user_info->type_name}}</p>
                <p class="position-introduce-account-done">Cấp bậc : {{$user_info->level_name}}</p>
                <div class="result-group" style="color: gold">
                    @for($i = 1; $i <= $user_info->rating;$i++)
                        <i class="fas fa-star"></i>
                    @endfor
                    @for($i = 5; $i > $user_info->rating;$i--)
                        <i class="far fa-star"></i>
                    @endfor

                </div>
                <p class="address-introduce-account-done">{{"$user_info->ward_name $user_info->district_name $user_info->province_name"}}</p>
                @if(auth()->guard('user')->user()->user_type_id !== 1)
                <a href="{{route('trang-ca-nhan.dong-thoi-gian',$user_info->user_code)}}" ><button type="button" class="btn go-to-profile-introduce">Xem trang cá nhân</button></a>
                @endif
                <button type="submit" class="btn go-to-profile-introduce">Lưu ảnh</button>
            </div>
        </form>

    </div>
</div>
@if(auth()->guard('user')->user()->user_type_id  == 2 || auth()->guard('user')->user()->user_type_id  == 3)
<div class="introduce-account-done">
    <div class="title-introduce-account-done">
        <h5>Nâng cấp tài khoản</h5>
    </div>
    <div class="content-introduce-account-done">
        <div class="header-notication-introduce-account-done">
            <div class="custom-switch custom-switch-label-onoff custom-switch-xs pl-0">
                <form action="{{route('user.upgrade-account')}}" method="post" id="form-upgrade-account">
                    @csrf
                    <input type="checkbox" name="inp_user_highlight" value="1" class="custom-switch-input" id="example_07" {{$user_info->is_highlight?'checked':null}}>
                    <label class="custom-switch-btn" for="example_07"></label>
                </form>
            </div>
            <div class="notication-introduce-account-done">
                <p>Sau khi bật tài khoản sẽ đứng đầu danh sách chuyên viên tư vấn </p>
                <p> Phí duy trì :
                    <span>
                        {{auth()->guard('user')->user()->user_type_id  == 2?SystemConfig::serviceFee(8):null}}
                        {{auth()->guard('user')->user()->user_type_id  == 3?SystemConfig::serviceFee(9):null}}
                        Coin/ tuần
                    </span>
                </p>
            </div>
        </div>
{{--        <div class="bottom-notication-introduce-account-done">--}}
{{--            <p> Không áp dụng với tài khoản cá nhân </p>--}}
{{--        </div>--}}
    </div>
</div>
@endif
<div class="introduce-account-done">
    <div class="title-introduce-account-done">
        <h5>Cập nhật ảnh bìa</h5>
    </div>
    <div class="content-introduce-account-done">
        <form action="{{route('user.update-background')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="update-images-cover">
                <img class="img-old-info mb-2" src="{{asset($user_info->background_url??'user/images/icon/img-upload.png')}}" alt="">
                <input type="file" id="upload-img" name="background_img" >
            </div>
            <button type="submit" class="btn bg-success w-100">Cập nhật</button>
        </form>
    </div>

</div>
<!--User có login_method == 0 mới đổi được mật khẩu -->
@if(Auth::guard('user')->user()->login_method == 0)
    <div class="introduce-account-done">
        <div class="title-introduce-account-done">
            <h5>Đổi mật khẩu</h5>
        </div>
        <div class="content-change-password">
            <form action="{{route('user.post-password-change')}}" method="post">
                @csrf
                <label>Mật khẩu cũ</label>
                <input type="password" name="current_password" class="form-control">
                @php show_validate_error($errors, 'current_password') @endphp
                <br><label>Mật khẩu mới</label>
                <input type="password" name="new_password" class="form-control">
                @php show_validate_error($errors,'new_password') @endphp
                <br><label>Nhập lại mật khẩu</label>
                <input type="password" name="re_password" class="form-control">
                @php show_validate_error($errors, 're_password') @endphp
                <input type="submit" class="btn change-password" value="Lưu">
            </form>
        </div>
    </div>
@endif
<div class="introduce-account-done">
    <div class="title-introduce-account-done">
        <h5>Xóa tài khoản</h5>
    </div>
    <div class="content-introduce-account-done position-relative">
        <div class="absolute-full account__delete-background"></div>
        @if($user_info->is_deleted == 0)
            <div class="delete-account">
                <div class="content-delete-account">
                    <h5>Xóa tài khoản</h5>
                    <p>Có thể khôi phục tài khoản 7 ngày sau khi xóa</p>
                </div>
            </div>

            <form action="{{ route('user.deleteAccount') }}" method="POST">
                @csrf
                <button type="button" class="btn btn-danger submit-accept-alert w-100 account__delete-button" data-action="xóa tài khoản">
                    Xóa tài khoản
                </button>
            </form>
        @else
            <div class="delete-account">
                <div class="content-delete-account">
                    <h5>{{ auth('user')->user()->isDeleted() ? 'Tài khoản đã bị xóa' : 'Khôi phục tài khoản' }}</h5>
                </div>
            </div>
            @if(auth('user')->user()->canRestore())
                <form action="{{ route('user.restoreAccount') }}" method="POST">
                    @csrf
                    <button type="button" class="btn btn-success submit-accept-alert w-100 account__delete-button" data-action="khôi phục tài khoản">
                        Khôi phục tài khoản
                    </button>
                </form>
            @endif
        @endif
    </div>
</div>

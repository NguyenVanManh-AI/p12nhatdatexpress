<div class="update-info-account">
    <div class="title-update-account">
        <h5>Cập nhật thông tin tài khoản</h5>
    </div>
    <form action="{{route('user.post-update-personal-info')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6 col-update">
                <label>{{ auth('user')->user()->isEnterprise() ? 'Tên công ty' : 'Họ tên'}}
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="fullname" class="form-control" value="{{old('fullname')??$user_info->fullname}}">
                {{show_validate_error($errors, 'fullname')}}
            </div>
            <div class="col-md-6 col-update">
                <label>Ngày sinh</label>
                <input name="birthday" type="date" class="form-control" value="{{old('birthday')??date('Y-m-d',$user_info->birthday)}}">
                {{show_validate_error($errors, 'birthday')}}
            </div>
            <div class="col-md-6 col-update">
                <div class="d-flex justify-content-between">
                    <div>
                        <label>Số điện thoại
                            <span class="text-danger">*</span>
                        </label>
                    </div>
                    @if(!auth('user')->user()->isEnterprise())
                    <div>
                        <div class="form-check text-right">
                            <input class="form-check-input" type="checkbox" name="phone_securiry" value="1" {{ old('phone_securiry', $user_info->phone_securiry) ? 'checked' : '' }}>
                            <label class="form-check-label">
                                Bảo mật thông tin
                            </label>
                        </div>
                        {{show_validate_error($errors, 'phone_securiry')}}
                    </div>
                    @endif
                </div>
                <input type="tel" name="phone_number" class="form-control" value="{{old('phone_number')??$user_info->phone_number}}">
                {{show_validate_error($errors, 'phone_number')}}
            </div>
            <div class="col-md-6 col-update">
                <label>Website</label>
                <input type="url" name="website" class="form-control" value="{{old('website')??$user_info->website}}">
                {{show_validate_error($errors, 'website')}}
            </div>
            <div class="col-md-6 col-update">
                <label>Email
                    <span class="text-danger">*</span>
                </label>
                <input type="email" name="email" class="form-control" value="{{old('email')??$user_info->email}}">
                {{show_validate_error($errors, 'email')}}
            </div>
            <div class="col-md-6 col-update">
                <label>{{ auth('user')->user()->isEnterprise() ? 'Mã số thuế': 'Số CMND/CCCD' }}
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="tax_number" class="form-control" {{ $user_info->tax_number ? 'disabled readonly' : '' }} value="{{ old('tax_number', $user_info->tax_number) }}">
                {{ show_validate_error($errors, 'tax_number') }}
            </div>
            <div class="col-md-12 col-update">
                <label>Địa chỉ<span class="text-danger">*</span></label>
                <div class="row group-load-address">
                    <div class="col-md-4">
                        <x-common.select2-input
                            label="Tỉnh/thành phố"
                            input-class="province-load-district"
                            name="province_id"
                            placeholder="-- Chọn tỉnh/Thành Phố  --"
                            :items="$provinces"
                            item-text="province_name"
                            items-select-name="province_id"
                            items-current-value="{{ old('province_id', $user_info->province_id) }}"
                        />
                    </div>
                    <div class="col-md-4">
                        <x-common.select2-input
                            label="Quận huyện"
                            input-class="district-province district-load-ward"
                            name="district_id"
                            placeholder="-- Chọn quận/Huyện --"
                            data-selected="{{ old('district_id', $user_info->district_id) }}"
                        />
                    </div>
                    <div class="col-md-4">
                        <x-common.select2-input
                            label="Xã/phường"
                            input-class="ward-district"
                            name="ward_id"
                            placeholder="-- Chọn xã/Phường --"
                            data-selected="{{ old('ward_id', $user_info->ward_id) }}"
                        />
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-update">
                <label>Giới thiệu</label>
                <textarea name="intro" class="form-control" id="exampleFormControlTextarea1" rows="5">{{old('intro')??$user_info->intro}}</textarea>
                {{show_validate_error($errors, 'intro')}}
            </div>
            <div class="click-update"><input type="submit" class="btn update-info-btn" value="Lưu"></div>
        </div>
    </form>
</div>

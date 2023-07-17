{{-- should combine with _form to 1 file. add field step by step keep position --}}
<div class="col-md-8">
  <div class="row group-load-address">
    <div class="col-12">
      <div
        class="form-group
        {{ ($group->group_url == 'nha-dat-ban' && $errors->has('paradigm')) ||
        ($group->group_url == 'nha-dat-cho-thue' && $errors->has('deposit'))
            ? 'pb-4'
            : '' }}">
        <label class="text-main fw-normal">Thuộc dự án (Nếu có)
          <span class="color-blue" data-title="Các thông tin sẽ được điền nhanh theo dự án đã chọn">
            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
          </span>
        </label>
        <select name="project" class="custom-select select2" data-placeholder="Chọn dự án">
          {{ show_select_option($project, 'id', 'project_name', 'project', old('project', $classified->project_id)) }}
        </select>
      </div>
    </div>

    @if ($group->group_url == 'can-mua-can-thue')
      <div class="col-md-8">
        <div class="box-slash relative p-4">
          <div class="box-slash__title text-main px-5 py-2 fs-normal fw-normal">
            Loại <span class="text-danger">*</span>
          </div>

          <div class="mt-2">
            <div class="row">
              <div class="col-6 flex-center">
                <label class="c-form-check mr-4">
                  Cần mua
                  <input type="radio" name="classified_type" value="19"
                    {{ old('classified_type', data_get($classified->group, 'parent_id')) != '20' ? 'checked' : '' }}
                    class="c-form-check-input">
                  <span class="c-form-check-mark"></span>
                </label>
              </div>
              <div class="col-6 flex-center">
                <label class="c-form-check">
                  Cần thuê
                  <input type="radio" name="classified_type" value="20"
                    {{ old('classified_type', data_get($classified->group, 'parent_id')) == '20' ? 'checked' : '' }}
                    class="c-form-check-input">
                  <span class="c-form-check-mark"></span>
                </label>
              </div>
              {{ showError($errors, 'classified_type') }}
            </div>
          </div>
        </div>
      </div>
    @else
      <input type="hidden" name="classified_type" value="{{ $group->id }}">
    @endif

    @if ($group->group_url == 'can-mua-can-thue' || $group->group_url == 'nha-dat-cho-thue')
      <div class="col-md-4">
        <div class="form-group">
          <label class="text-main fw-normal">Mô hình
            <span class="text-danger">*</span>
            <x-common.loading class="loading-inline small parent-group-loading ml-1" />
          </label>
          <select name="paradigm" class="custom-select select2 {{ $errors->has('paradigm') ? 'is-invalid' : '' }}"
            data-placeholder="Chọn mô hình">
            {{ show_select_option($paradigm, 'id', 'group_name', 'paradigm', old('paradigm', $classified->group_id)) }}
          </select>
          {{ showError($errors, 'paradigm') }}
        </div>
      </div>
    @endif

    @if ($group->group_url == 'nha-dat-cho-thue')
      <div class="col-md-4">
        <div class="form-group manually-project-input">
          <label class="text-main fw-normal">Tình trạng
            <span class="text-danger">*</span>
          </label>

          <select name="progress" class="custom-select select2 {{ $errors->has('progress') ? 'is-invalid' : '' }}"
            data-placeholder="Chọn tình trạng">
            {{ show_select_option($progress, 'id', 'progress_name', 'progress', old('progress', $classified->classified_progress)) }}
          </select>
          {{ showError($errors, 'progress') }}
        </div>
      </div>
    @endif

    <div class="col-md-4">
      <div
        class="form-group
        {{ ($group->group_url == 'nha-dat-ban' &&
            $errors->has('progress') &&
            (!$errors->has('unit') && !$errors->has('unit_price') && !$errors->has('area') && !$errors->has('direction'))) ||
        ($group->group_url == 'nha-dat-cho-thue' &&
            $errors->has('capacity') &&
            (!$errors->has('unit') && !$errors->has('unit_price') && !$errors->has('paradigm') && !$errors->has('progress')))
            ? 'pb-4'
            : '' }}">
        <label class="text-main fw-normal">
          @if ($group->group_url == 'nha-dat-ban')
            Giá bán <span class="important">*</span>
          @elseif($group->group_url == 'nha-dat-cho-thue')
            Giá thuê
          @else
            Tài chính
          @endif
          <x-common.loading class="loading-inline small parent-group-loading project-loading ml-1" />
        </label>
        <div class="input-group">
          <input name="price_show" type="text"
            class="form-control {{ $errors->has('price') || $errors->has('unit_price') ? 'is-invalid' : '' }}"
            placeholder="Số tiền">
          <input hidden name="price" type="number" value="{{ old('price', $classified->classified_price) }}">
          <div
            class="input-group-append w-50 {{ $errors->has('price') || $errors->has('unit_price') ? 'is-invalid' : '' }}">
            <select name="unit_price"
              class="custom-select form-control select2 unit_price {{ $errors->has('price') || $errors->has('unit_price') ? 'is-invalid' : '' }}"
              data-placeholder="Đơn vị">
              {{ show_select_option($unit_price, 'id', 'unit_name', 'unit_price', old('unit_price', $classified->price_unit_id)) }}
            </select>
          </div>
          @if ($errors->has('price'))
            {{ showError($errors, 'price') }}
          @elseif($errors->has('unit_price'))
            {{ showError($errors, 'unit_price') }}
          @endif
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div
        class="form-group
        {{ ($group->group_url == 'nha-dat-ban' &&
            $errors->has('progress') &&
            (!$errors->has('unit') && !$errors->has('unit_price') && !$errors->has('area') && !$errors->has('direction'))) ||
        ($group->group_url == 'nha-dat-cho-thue' &&
            $errors->has('capacity') &&
            (!$errors->has('unit') && !$errors->has('unit_price') && !$errors->has('paradigm') && !$errors->has('progress')))
            ? 'pb-4'
            : '' }}">
        <label class="text-main fw-normal">Diện tích
          <span class="text-danger">*</span>
          <x-common.loading class="loading-inline small project-loading ml-1" />
        </label>
        <div class="input-group">
          <input type="number" class="form-control {{ $errors->has('area') ? 'is-invalid' : '' }}" name="area"
            value="{{ old('area', $classified->classified_area) }}" min="0" placeholder="Nhập diện tích"
            required>
          <div class="input-group-append">
            <span class="input-group-text">m2</span>
          </div>
          {{ showError($errors, 'area') }}
        </div>
      </div>
    </div>

    @if ($group->group_url == 'nha-dat-ban')
      <div class="col-md-4">
        <div
          class="form-group manually-project-input
          {{ ($group->group_url == 'nha-dat-ban' &&
              $errors->has('progress') &&
              (!$errors->has('unit') && !$errors->has('unit_price') && !$errors->has('area') && !$errors->has('juridical'))) ||
          ($group->group_url == 'nha-dat-cho-thue' &&
              $errors->has('capacity') &&
              (!$errors->has('unit') && !$errors->has('unit_price') && !$errors->has('paradigm') && !$errors->has('progress')))
              ? 'pb-4'
              : '' }}">
          <label class="text-main fw-normal">Pháp lý
            <span class="text-danger">*</span>
          </label>

          <select name="juridical" class="custom-select select2 {{ $errors->has('juridical') ? 'is-invalid' : '' }}"
            data-placeholder="Chọn pháp lý">
            {{ show_select_option($classifiedParams->where('param_type', 'L'), 'id', 'param_name', 'juridical', old('juridical', $classified->classified_juridical)) }}
          </select>
          {{ showError($errors, 'juridical') }}
        </div>
      </div>
    @endif

    <div class="col-md-4">
      <div class="form-group manually-project-input">
        <label class="text-main fw-normal">Phòng ngủ</label>

        <select name="bedroom" class="custom-select select2 {{ $errors->has('bedroom') ? 'is-invalid' : '' }}"
          data-placeholder="Chọn số phòng ngủ">
          {{ show_select_option($classifiedParams->where('param_type', 'B'), 'id', 'param_name', 'bedroom', old('bedroom', $classified->num_bed)) }}
        </select>
        {{ showError($errors, 'bedroom') }}
      </div>
    </div>

    @if ($group->group_url == 'nha-dat-ban')
      <div class="col-md-4">
        <div class="form-group manually-project-input">
          <label class="text-main fw-normal">Nhà vệ sinh</label>

          <select name="toilet" class="custom-select select2 {{ $errors->has('toilet') ? 'is-invalid' : '' }}"
            data-placeholder="Chọn số phòng vệ sinh">
            {{ show_select_option($classifiedParams->where('param_type', 'T'), 'id', 'param_name', 'toilet', old('toilet', $classified->num_toilet)) }}
          </select>
          {{ showError($errors, 'toilet') }}
        </div>
      </div>
    @endif

    @if ($group->group_url == 'nha-dat-ban' || $group->group_url == 'nha-dat-cho-thue')
      <div class="col-md-4">
        <div class="form-group">
          <label class="text-main fw-normal">Hướng
            <x-common.loading class="loading-inline small project-loading ml-1" />
          </label>

          <select name="direction" class="custom-select select2 {{ $errors->has('direction') ? 'is-invalid' : '' }}"
            data-placeholder="Chọn hướng">
            {{ show_select_option($direction, 'id', 'direction_name', 'direction', old('direction', $classified->classified_direction)) }}
          </select>
          {{ showError($errors, 'direction') }}
        </div>
      </div>
    @endif

    <div class="col-md-4">
      <div class="form-group">
        <label class="text-main fw-normal">Vị trí
          <span class="text-danger">*</span>
          <x-common.loading class="loading-inline small project-loading ml-1" />
        </label>

        <input type="text" name="address" value="{{ old('address', data_get($location, 'address')) }}"
          class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" placeholder="Địa chỉ" required>
        {{ showError($errors, 'address') }}
      </div>
    </div>

    <div class="col-md-4">
      <x-common.select2-input
        class="mt-4 pt-2"
        input-class="province-load-district"
        name="province"
        placeholder="Chọn tỉnh/thành phố"
        :items="$province"
        item-text="province_name"
        items-select-name="province"
        :items-current-value="old('province', data_get($location, 'province_id'))"
      />
    </div>

    <div class="col-md-4">
      <x-common.select2-input
        class="mt-4 pt-2"
        input-class="district-province"
        name="district"
        placeholder="Chọn quận/huyện"
        :data-selected="old('district', data_get($location, 'district_id'))"
      />
    </div>

    {{-- <input type="hidden" name="latitude" id="project-latitude" value="{{ old('latitude', data_get($location, 'map_latitude')) }}" >
    <input type="hidden" name="longtitude" id="project-longtitude" value="{{ old('longtitude', data_get($location, 'map_longtitude')) }}" > --}}
  </div>
</div>
<div class="col-md-4">
  <div class="row">
    @if ($group->group_url == 'nha-dat-ban')
      <div class="col-12">
        <div class="form-group">
          <label class="text-main fw-normal">Mô hình
            <span class="text-danger">*</span>
          </label>

          <select name="paradigm" class="custom-select select2 {{ $errors->has('paradigm') ? 'is-invalid' : '' }}"
            data-placeholder="Chọn mô hình">
            {{ show_select_option($paradigm, 'id', 'group_name', 'paradigm', old('paradigm', $classified->group_id)) }}
          </select>
          {{ showError($errors, 'paradigm') }}
        </div>
      </div>
      <div class="col-12">
        <div
          class="form-group manually-project-input
          {{ !$errors->has('progress') &&
          ($errors->has('unit') || $errors->has('unit_price') || $errors->has('area') || $errors->has('juridical'))
              ? 'pb-4'
              : '' }}">
          <label class="text-main fw-normal">Tình trạng
            <span class="text-danger">*</span>
          </label>

          <select name="progress" class="custom-select select2 {{ $errors->has('progress') ? 'is-invalid' : '' }}"
            data-placeholder="Chọn tình trạng">
            {{ show_select_option($progress, 'id', 'progress_name', 'progress', old('progress', $classified->classified_progress)) }}
          </select>
          {{ showError($errors, 'progress') }}
        </div>
      </div>
    @endif

    @if ($group->group_url == 'nha-dat-cho-thue')
      <div class="col-12">
        <div class="form-group manually-project-input">
          <label class="text-main fw-normal">Cọc trước
            <span class="text-danger">*</span>
          </label>

          <select name="deposit" class="custom-select select2 {{ $errors->has('deposit') ? 'is-invalid' : '' }}"
            data-placeholder="Chọn mức cọc">
            {{ show_select_option($classifiedParams->where('param_type', 'A'), 'id', 'param_name', 'deposit', old('deposit', $classified->advance_stake)) }}
          </select>
          {{ showError($errors, 'deposit') }}
        </div>
      </div>
      <div class="col-12">
        <div
          class="form-group manually-project-input
          {{ !$errors->has('capacity') &&
          ($errors->has('unit') || $errors->has('unit_price') || $errors->has('paradigm') || $errors->has('progress'))
              ? 'pb-4'
              : '' }}">
          <label class="text-main fw-normal">Số người ở tối đa
            <span class="text-danger">*</span>
          </label>

          <select name="capacity" class="custom-select select2 {{ $errors->has('capacity') ? 'is-invalid' : '' }}"
            data-placeholder="Chọn số người ở tối đa">
            {{ show_select_option($classifiedParams->where('param_type', 'P'), 'id', 'param_name', 'capacity', old('capacity', $classified->num_people)) }}
          </select>
          {{ showError($errors, 'capacity') }}
        </div>
      </div>
    @endif

    @if ($group->group_url == 'nha-dat-ban')
      <div class="col-12">
        <div class="form-group manually-project-input">
          <label class="text-main fw-normal">Nội thất</label>

          <select name="furniture" class="custom-select select2 {{ $errors->has('furniture') ? 'is-invalid' : '' }}"
            data-placeholder="Chọn nội thất">
            {{ show_select_option($furniture, 'id', 'furniture_name', 'furniture', old('furniture', $classified->classified_furniture)) }}
          </select>
          {{ showError($errors, 'furniture') }}
        </div>
      </div>
    @endif

    <div class="col-md-12 mt-4">
      <div class="checkbox--project-land mt-2">
        @if(!(isset($isHideUtilities) && $isHideUtilities))
          <div>
            <label class="checkbox-button">
              <input type="checkbox" name="is_monopoly" value="1" class="checkbox-button__input account-verify"
                {{ old('verify', $classified->is_monopoly) ? 'checked' : '' }}
                {{ !$account_verify ? 'readonly' : '' }}>
              <span class="checkbox-button__control"></span>
              <span class="checkbox-button__label">Chính chủ</span>
              <span class="color-blue" data-title="Chỉ những tài khoản được xác thực(nạp trên 500k) mới được sử dụng tính năng này">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
              </span>
            </label>
          </div>
        @endif
        <div>
          <label class="checkbox-button">
            <input type="checkbox" {{ old('freezer', $classified->is_freezer) ? 'checked' : '' }} name='is_broker' value="1" class="checkbox-button__input account-verify"
              {{ old('is_broker', $classified->is_broker) ? 'checked' : '' }}>
              {{-- {{ !$account_verify ? 'readonly' : '' }}> --}}
            <span class="checkbox-button__control"></span>
            <span class="checkbox-button__label">Miễn môi giới</span>
          </label>
        </div>
        @if (!(isset($isHideUtilities) && $isHideUtilities) && $group->group_url != 'nha-dat-ban')
          <div>
            <label class="checkbox-button">
              <input type="checkbox" name="freezer" value="1" class="checkbox-button__input account-verify"
                {{ old('freezer', $classified->is_freezer) ? 'checked' : '' }}
                {{ !$account_verify ? 'readonly' : '' }}>
              <span class="checkbox-button__control"></span>
              <span class="checkbox-button__label">Điều hòa</span>
            </label>
          </div>
          <div>
            <label class="checkbox-button">
              <input type="checkbox" name="balcony" value="1" class="checkbox-button__input account-verify"
                {{ old('mezzanino', $classified->is_balcony) ? 'checked' : '' }}
                {{ !$account_verify ? 'readonly' : '' }}>
              <span class="checkbox-button__control"></span>
              <span class="checkbox-button__label">Ban công</span>
            </label>
          </div>
          <div>
            <label class="checkbox-button">
              <input type="checkbox" name="internet" value="1" class="checkbox-button__input account-verify"
                {{ old('internet', $classified->is_internet) ? 'checked' : '' }}
                {{ !$account_verify ? 'readonly' : '' }}>
              <span class="checkbox-button__control"></span>
              <span class="checkbox-button__label">Internet(wifi)</span>
            </label>
          </div>
          <div>
            <label class="checkbox-button">
              <input type="checkbox" name="mezzanino" value="1" class="checkbox-button__input account-verify"
                {{ old('mezzanino', $classified->is_mezzanino) ? 'checked' : '' }}
                {{ !$account_verify ? 'readonly' : '' }}>
              <span class="checkbox-button__control"></span>
              <span class="checkbox-button__label">Gác lửng</span>
            </label>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="row group-load-address">
  <div class="col-md-6">
    <x-common.text-input
      label="Tên khách hàng"
      name="fullname"
      value="{{ old('fullname') }}"
      placeholder="Nhập tên khách hàng"
      required
    />
  </div>
  <div class="col-md-6">
    <x-common.text-input
      label="Sinh nhật"
      name="birthday"
      type="date"
      value="{{ old('birthday') }}"
      placeholder="Nhập sinh nhật"
      required
    />
  </div>
  <div class="col-md-6">
    <x-common.text-input
      label="Email"
      name="email"
      type="email"
      value="{{ old('email') }}"
      placeholder="Nhập email"
    />
  </div>
  <div class="col-md-6">
    <x-common.text-input
      label="Số điện thoại"
      name="phone_number"
      value="{{ old('phone_number') }}"
      placeholder="Nhập số điện thoại"
      required
    />
  </div>
  <div class="col-md-6">
    <x-common.select2-input
      label="Nghề nghiệp"
      name="job"
      placeholder="Chọn nghề nghiệp"
      :items="$jobs"
      item-text="param_name"
      items-select-name="job"
      :items-current-value="old('job')"
      select2-parent="{{ $select2Parent }}"
    />
  </div>
  <div class="col-md-6">
    <x-common.text-input
      label="Địa chỉ"
      name="address"
      value="{{ old('address') }}"
      placeholder="Nhập địa chỉ"
      required
    />
  </div>
  <div class="col-md-6">
    <x-common.select2-input
      label="Tỉnh/thành phố"
      input-class="province-load-district"
      name="province"
      placeholder="Chọn Tỉnh/Thành Phố "
      :items="$provinces"
      item-text="province_name"
      items-select-name="province"
      :items-current-value="old('province')"
      select2-parent="{{ $select2Parent }}"
    />
  </div>
  <div class="col-md-6">
    <x-common.select2-input
      label="Quận huyện"
      input-class="district-province"
      name="district"
      placeholder="Chọn Quận/Huyện"
      :data-selected="old('district')"
      select2-parent="{{ $select2Parent }}"
    />
  </div>

  <div class="col-md-6">
    <x-common.select2-input
      label="Nguồn phát sinh"
      name="source"
      placeholder="Chọn nguồn"
      :items="$sources"
      item-text="param_name"
      items-select-name="source"
      :items-current-value="old('source')"
      select2-parent="{{ $select2Parent }}"
    />
  </div>
  <div class="col-md-6">
    <x-common.select2-input
      label="Trạng thái"
      name="status"
      placeholder="Chọn trạng thái"
      :items="$status"
      item-text="param_name"
      items-select-name="status"
      :items-current-value="old('status')"
      select2-parent="{{ $select2Parent }}"
    />
  </div>
  <div class="col-12">
    <x-common.textarea-input
      label="Ghi chú"
      name="note"
      value="{{ old('note') }}"
      placeholder="Ghi chú..."
    />
  </div>

  <div class="col-12 mb-4">
    <x-common.file-box-input
      class="customer-file-input"
      name="avatar"
      image-value="{{ null }}"
      base64-file
      preview-inline
    />
  </div>
</div>

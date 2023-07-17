<div class="row keyword-form group-load-address">
  <div class="col-md-6">
    <x-common.text-input
      label="Vị trí"
      name="province_name"
      value="{{ $province->province_name }}"
      readonly
      disabled
    />
  </div>
  <div class="col-md-6">
    <x-common.text-input
      label="Từ khóa"
      name="seo[meta_key]"
      value="{{ old('seo.meta_key', data_get($province, 'seo.meta_key')) }}"
      error-name="seo.meta_key"
    />
  </div>
  <div class="col-md-6">
    <x-common.text-input
      label="Tiêu đề"
      name="seo[meta_title]"
      value="{{ old('seo.meta_title', data_get($province, 'seo.meta_title')) }}"
      error-name="seo.meta_title"
    />
  </div>
  <div class="col-md-6">
    <x-common.text-input
      label="Mô tả"
      name="seo[meta_description]"
      value="{{ old('seo.meta_description', data_get($province, 'seo.meta_description')) }}"
      error-name="seo.meta_description"
      required
    />
  </div>
</div>

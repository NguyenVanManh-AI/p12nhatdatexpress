<div class="row">
  <div class="col-12">
    <x-common.text-input
      label="Tiêu đề"
      name="page_title"
      id="page_title"
      value="{{ old('page_title', $item->page_title) }}"
      required
    />
  </div>
  <div class="col-12">
    <x-common.text-input
      label="Mô tả ngắn"
      name="page_description"
      id="page_description"
      value="{{ old('page_description', $item->page_description) }}"
      required
    />
  </div>
  <div class="col-12">
    <x-common.admin.textarea-input
      label="Nội dung"
      name="page_content"
      id="page_content"
      rows="5"
      placeholder="Nhập nội dung"
      value="{{ old('page_content', $item->page_content) }}"
      is-tiny-mce
      required
    />
  </div>
  <div class="col-12">
    <div class="form-group">
      <h3>Tối ưu hóa SEO</h3>
      <i class="text-gray">Xem kết quả tìm kiếm</i>
      <div class="mt-2 py-2 px-3 bg-gray">
          <p class="mb-1 text-main fs-18 text-break" id="google_title_seo"></p>
          <p class="mb-1 text-success small-90 text-break">{{\Request::getSchemeAndHttpHost()}}/<span id="google_url_seo"></span></p>
          <p class="mb-0 text-muted text-break" id="google_description_seo"></p>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <x-common.text-input
      label="Đường dẫn thân thiện"
      name="page_url"
      id="page_url"
      value="{{ old('page_url', $item->page_url) }}"
      hint="Ví dụ: gioi-thieu-ve-chung-toi"
      has-count
    />
  </div>
  <div class="col-md-6">
    <x-common.text-input
      label="Tiêu đề của trang"
      name="meta_title"
      id="meta_title"
      value="{{ old('meta_title', $item->meta_title) }}"
      hint="Ví dụ: giới thiệu | gioi thieu"
      has-count
    />
  </div>
  <div class="col-md-6">
    <x-common.admin.textarea-input
      label="Từ khóa trên công cụ tìm kiếm"
      name="meta_key"
      id="meta_key"
      rows="3"
      value="{{ old('meta_key', $item->meta_key) }}"
      hint="Ví dụ: giới thiệu, công ty"
      has-count
    />
  </div>
  <div class="col-md-6">
    <x-common.admin.textarea-input
      label="Mô tả trên công cụ tìm kiếm"
      name="meta_desc"
      id="meta_desc"
      rows="3"
      value="{{ old('meta_desc', $item->meta_desc) }}"
      hint="Không nên nhập quá 200 chữ và cần có từ khóa cần seo"
      has-count
    />
  </div>
</div>







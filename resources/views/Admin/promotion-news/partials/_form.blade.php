<div class="col-md-7-3">
  <x-common.text-input
    label="Tiêu đề bài viết"
    name="news_title"
    placeholder="Nhập tiêu đề bài viết"
    value="{{ old('news_title', $promotion->news_title) }}"
    required
  />

  <x-common.text-input
    label="Mô tả ngắn"
    name="news_description"
    placeholder="Nhập mô tả ngắn"
    value="{{ old('news_description', $promotion->news_description) }}"
    required
  />

  <x-common.select2-input
    label="Mã khuyến mãi áp dụng"
    name="promotion_id"
    :items="$list_code_0"
    item-text="label"
    item-value="id"
    placeholder="Chọn mã khuyến mãi"
    items-current-value="{{ old('promotion_id', $promotion->promotion_id) }}"
  />
</div>

<div class="col-md-3-7">
  <div>
    <label>Ảnh đại diện</label><br>
    @if ($errors->has('image'))
      <small class="text-danger mt-1 float-right" style="font-size: 90%">
        {{ $errors->first('image') }}
      </small>
    @endif
    <div>
      <div class="button-choose-image text-center " style="width: 100%;border:1px solid #ccc">
        <p>Chọn hình ảnh</p>
        <input accept="image/*" type='file' id="imgInp" name="image" style="width: 100%">
      </div>

      <div class="h-157px pt-2">
        @if ($promotion->image != null)
          <img class="object-contain" id="blah2"
            src="{{ asset('system/images/post_promotion') . '/' . $promotion->image }}" alt="your image" width="100%"
            height="100%" />
        @else
          <img class="object-contain" id="blah2" src="{{ asset('system/image/upload-file.png') }}" alt="your image"
            width="100%" height="100%" style="border: 1px solid #ccc" />
        @endif
      </div>
    </div>
  </div>
</div>
<div class="col-12">
  <x-common.admin.textarea-input label="Nội dung" name="news_content" placeholder="Nhập nội dung"
    value="{{ old('news_content', $promotion->news_content) }}" is-tiny-mce required />
</div>

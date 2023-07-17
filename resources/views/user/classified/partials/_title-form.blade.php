{{-- should combine with _form & _project-form to 1 file. add field step by step keep position --}}
<div class="text-need-show-count mb-4">
  <div class="row flex-column-reverse flex-md-row">
    <div class="col-md-8">
      <div class="form-group">
        <label class="fw-bold text-main">
          Tiêu đề tin đăng
          <span class="text-orange">{{ data_get($group, 'group_name') }}</span>
          <span class="text-danger">*</span>
        </label>
        <input type="text" name="title" value="{{ old('title', $classified->classified_name) }}"
          class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" placeholder="Tối đa 99 ký tự" required>
        {{ showError($errors, 'title') }}
      </div>

      <div class="form-group">
          <label class="fw-bold text-main">
            Nội dung tin đăng
            <span class="text-danger">*</span>
          </label>
          <div class="position-relative">
            <textarea name="description" rows="18" class="form-control input-need-show-count pb-5 {{ $errors->has('description') ? 'is-invalid' : '' }}" placeholder="Tối thiểu 200 ký tự" required>{{ old('description', $classified->classified_description) }}</textarea>
            {{ showError($errors, 'description') }}

            <div class="position-absolute flex-end flex-wrap bottom-0 start-0 w-100 px-3 py-2 {{ $errors->has('description') ? 'mb-4' : '' }}">
              <span>Số ký tự: <strong class="word-count text-red text-bold">0</strong></span>
            </div>
          </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="h-100 pt-4 mt-2 pb-4">
        <div class="tips-posts">
          <div class="title-tips-post">Mẹo đăng tin hiệu quả</div>
          <div class="content-tips-post">
            <?php $postTip = $guide->firstWhere('config_code', 'N006') ?>
            {!! data_get($postTip, 'config_value') !!}
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- <div class="row">
    <div class="col-md-8 d-flex justify-content-between">
      <span class="font-italic">Nội dung đăng tin không bao gồm hình ảnh</span>
      <span>Số ký tự: <strong class="word-count text-red text-bold">0</strong></span>
    </div>
  </div> --}}
</div>

<div class="form-group">
  <label class="fw-bold text-main">
    Đường dẫn video (Youtube)
  </label>
  <input type="url" name="video_url" value="{{ old('video_url', $classified->video) }}"
    class="form-control text-cyan {{ $errors->has('video_url') ? 'is-invalid' : '' }}">
  {{ showError($errors, 'video_url') }}
</div>

{{-- default close. for open: should-toggle-active is-active, js-toggle-active is-on, remove js-toggle-area-slide display:none --}}
<div class="display-optimize-box js-need-toggle-active should-toggle-active">
  <div class="display-optimize__title text-dark-blue flex-between js-toggle-active">
    <h5 class="fs-normal mb-0">Tối ưu hiển thị trên google</h5>

    <span class="icon">
      <i class="fas fa-angle-down"></i>
    </span>
  </div>

  <div class="js-toggle-area-slide" style="display:none;">
    <div class="form-group">
      <label class="fw-bold text-main">
        Tiêu đề hiển thị
      </label>
      <input type="text" name="meta_title" {{ isset($canEdit) && $canEdit ? '' : 'readonly' }} class="form-control"
        value="{{ isset($canEdit) && $canEdit ? old('meta_title', $classified->meta_title) : old('title', $classified->meta_title) }}">
    </div>

    <div class="form-group">
      <label class="fw-bold text-main">
        Từ khóa
        <x-common.loading class="loading-inline small project-loading ml-1" />
      </label>
      <input type="text" name="meta_key" class="form-control" value="{{ old('meta_key', $classified->meta_key) }}">
      <span class="font-italic">
        Nhập từ khóa bạn muốn hiển thị trên google , mỗi từ khóa cách nhau bằng dấu phẩy <br>
        Các từ khóa cần xuất hiện trong tin đăng
      </span>
    </div>

    <div class="form-group">
      <label class="fw-bold text-main">
        Nội dung hiển thị
      </label>
      <textarea name="meta_desc" {{ isset($canEdit) && $canEdit ? '' : 'readonly' }} class="form-control" rows="8">{{ old('meta_desc', $classified->meta_desc) }}</textarea>
    </div>
  </div>
</div>

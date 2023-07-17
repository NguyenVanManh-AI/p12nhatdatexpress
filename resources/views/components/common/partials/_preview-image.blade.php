<div class="file-input__preview position-relative {{ old($name, $imageValue) ? '' : 'd-none' }}">
  <div class="file-input__preview-inner position-relative" style="height: {{ isset($previewHeight) ? $previewHeight : '200' }}px">
    <a href="{{ old($name, $imageValue) ?: 'javascript:void(0);' }}" class="file-input__preview-image js-fancy-box">
      <img class="object-contain" src="{{ old($name, $imageValue) }}">
    </a>
    <input class="file-input__preview-input" type="hidden" name="{{ $oldName }}" value="{{ old($name, $imageValue) }}">
    <span class="file-input__remove-image is-opacity-class position-absolute top-0 end-0 cursor-pointer text-white icon-squad-2 flex-center rounded-circle bg-white hover-bg-gray"
      title="Xóa" data-name="{{ $name }}">
      <i class="fas fa-times"></i>
    </span>
  </div>
</div>

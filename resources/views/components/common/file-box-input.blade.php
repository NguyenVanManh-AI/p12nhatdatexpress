<div class="file-input-group {{ $attributes['class'] }} {{ $imageValue ? 'has-value' : '' }} {{ $base64File ? 'base-64-input' : '' }}">
  <div class="file-input__upload border p-2 position-relative {{ $errors->has($name) ? 'is-invalid' : '' }}">
    <div class="file-input__upload-inner flex-center flex-column p-3">
      <div class="file-input__upload-image mb-2">
        @if($previewInline)
          @include('components.common.partials._preview-image', [
            'name' => $name,
            'oldName' => $oldName,
            'imageValue' => $imageValue,
          ])
        @endif
        <img src="{{ asset('frontend/images/upload-logo.png') }}">
      </div>

      <span class="file-input__upload-note mb-2 font-weight-bold text-cyan">
        Kéo & Thả ảnh tại đây!
      </span>
      <div class="file-with-button cursor-pointer">
        <button type="button" class="btn btn-light-cyan button-with-file px-1 py-0 fs-14">
          Tải ảnh lên
        </button>
        {{-- <button type="button" class="btn btn-light-cyan px-1 py-0 mr-2 mb-2 fs-14">
            Chọn ảnh có sẵn
          </button> --}}
        <input type="file" name="{{ $base64File ? null : $name }}" class="absolute-full opacity-hide cursor-pointer" accept=".png, .jpg, .jpeg">
      </div>
    </div>
  </div>

  {{ showError($errors, $name) }}

  @if(!$previewInline)
    @include('components.common.partials._preview-image', [
      'name' => $name,
      'oldName' => $oldName,
      'imageValue' => $imageValue,
    ])
  @endif
</div>

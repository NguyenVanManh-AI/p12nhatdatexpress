<div class="row">
  <div class="configuration-1 col-md-7-3 mb-4">
    <x-common.text-input
      label="Tiêu đề"
      name="mail_header"
      value="{{ old('mail_header', $template->mail_header) }}"
      placeholder="Tiêu đề"
      required
    />

    <x-common.textarea-input
      class="mb-0"
      label="Nội dung"
      name="mail_content"
      value="{{ old('mail_content', $template->mail_content) }}"
      rows="15"
      placeholder="Nội dung"
      :is-tiny-mce="true"
      required
    />
  </div>

  <div class="col-md-3-7 my-4">
    <x-user.guide :guide="$guide" class="bg-white h-100" />
  </div>
</div>
<div class="row mb-4">
  <div class="col-md-7-3">
    <div class="d-flex justify-content-between align-items-start flex-wrap">
      <div class="mw-65 mr-2 mb-2 flex-1">
        <p>Ghi chú : chèn <span class="text-danger">%ten_nguoi_nhan%</span> vào nội dung để hệ thống tự thay đổi tên phù
          hợp khi gửi mail cho nhiều người . </p>
      </div>
      <button class="btn btn-normal">
        <img class="icon-squad-2" src="{{asset('user/images/icon/save-icon.png')}}" alt="">
        {{-- <i class="fas fa-save text-blue"></i> --}}
        <span class="px-5">
          Lưu
        </span>
      </button>
    </div>
  </div>
</div>

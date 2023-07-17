<div class="py-3 px-3 text-center w-100">
  <a href="{{ auth('user')->user() ? route('user.add-classified', 'nha-dat-ban') : 'javascript:void(0);' }}" class="{{ !auth('user')->user() ? 'js-open-add-classified__login' : '' }} btn btn-success">
    <span class="btn-icon flex-inline-center mr-1">
      <i class="fas fa-plus"></i>
    </span>
    Đăng tin
  </a>
</div>
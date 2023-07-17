<form action="{{ $advisoryUrl }}" class="js-send-advisory-form" method="POST">
  @csrf
  <x-common.text-input
    name="fullname"
    value="{{ $authUser ? $authUser->getFullname() : '' }}"
    placeholder="Họ & Tên"
  >
    <x-slot name="prependInner">
      <div class="c-icon text-gray">
        <i class="fas fa-user"></i>
      </div>
    </x-slot>
  </x-common.text-input>
  <x-common.text-input
    name="phone_number"
    value="{{ $authUser ? $authUser->phone_number : '' }}"
    placeholder="Số điện thoại"
  >
    <x-slot name="prependInner">
      <div class="c-icon text-gray">
        <i class="fas fa-phone-alt"></i>
      </div>
    </x-slot>
  </x-common.text-input>
  <x-common.text-input
    name="email"
    value="{{ $authUser ? $authUser->email : '' }}"
    placeholder="Email"
  >
    <x-slot name="prependInner">
      <div class="c-icon text-gray">
        <i class="fas fa-envelope"></i>
      </div>
    </x-slot>
  </x-common.text-input>
  <x-common.textarea-input
    name="note"
    rows="3"
    placeholder="Nội dung khác (nếu có)"
  />
</form>

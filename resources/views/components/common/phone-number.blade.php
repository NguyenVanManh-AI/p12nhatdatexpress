@props(['phone'])
<div class="phone flex-start">
  @if($phone)
    <a href="tel:{{ $phone }}" class="text-blue bold flex-start">
      <i class="fas fa-phone-alt mr-1 fs-18"></i>
      <span class="display-phone">{{ substr($phone,0,4) }}</span>
    </a>
    <span class="click-show-phone" data-phone="{{ format_phone_number($phone) }}">Hiện số</span>
  @endif
</div>

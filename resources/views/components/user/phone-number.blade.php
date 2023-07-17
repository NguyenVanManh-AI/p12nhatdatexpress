@props(['user', 'phone'])
<div class="phone flex-start">
  @if(isset($icon) && $icon)
    {{ $icon }}
  @else
    <img class="mr-2" src="{{ asset('frontend/images/grey-circle-phone.png')}}" alt="">
  @endif
  @if(isset($user) && $user)
    @if(!$user->phone_securiry)
      @if($user->phone_number)
        <a href="tel:{{ $user->phone_number }}" class="{{ $attributes['class'] ?: 'color-grey' }}">
          <span class="display-phone">{{ substr($user->phone_number, 0, 4) }}</span>
        </a>
        <span class="click-show-phone" data-phone="{{ format_phone_number($user->phone_number) }}">Hiện số</span>
      @endif
    @else
      <span>Số điện thoại đã bị ẩn</span>
    @endif
  @elseif(isset($phone) && $phone)
    <a href="tel:{{ $phone }}" class="{{ $attributes['class'] ?: 'color-grey' }}">
      <span class="display-phone">{{ substr($phone, 0, 4) }}</span>
    </a>
    <span class="click-show-phone" data-phone="{{ format_phone_number($phone) }}">Hiện số</span>
  @endif
</div>

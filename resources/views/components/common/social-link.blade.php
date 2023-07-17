{{-- <div class="social-link__box">
  <ul>
    <li class="social-link__item mr-3">
      <a href="{{ $getConfig->facebook }}" class="d-inline-block icon-squad-32 hover-scale" target="_blank">
        <img class="object-cover rounded-circle" src="{{ asset('frontend/images/facebook.png') }}">
      </a>
    </li>
    <li class="social-link__item mr-3">
      <a href="{{ $getConfig->facebook }}" class="d-inline-block icon-squad-32 hover-scale" target="_blank">
        <img class="object-cover rounded-circle" src="{{ asset('frontend/images/Youtube.png') }}">
      </a>
    </li>
    <li class="social-link__item">
      <a href="{{ $getConfig->facebook }}" class="d-inline-block icon-squad-32 hover-scale" target="_blank">
        <img class="object-cover rounded-circle" src="{{ asset('frontend/images/Logotikktok.png') }}">
      </a>
    </li>
  </ul>
</div> --}}

<div class="social-link__box {{ $attributes['class'] }}">
  @foreach ($socials as $social)
    @if (data_get($social, 'link'))
      <a href="{{ data_get($social, 'link') }}"
        class="social-link__icon c-icon icon-squad-32 shadow hover-scale mr-2 {{ data_get($social, 'type') }}"
        title="{{ ucfirst(data_get($social, 'type')) }}" target="_blank">
        <i class="{{ data_get($social, 'icon') }}"></i>
      </a>
    @endif
  @endforeach
</div>

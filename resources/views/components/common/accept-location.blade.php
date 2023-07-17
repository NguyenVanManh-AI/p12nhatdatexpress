@props(['enableText', 'disableText', 'iconClass', 'enableBtnClass', 'disableBtnClass', 'isIndividual'])
@if(isset($isIndividual))
  {{-- load ajax riêng biệt từng section --}}
  <a
    class="btn {{ $enableBtnClass ?? 'btn-orange' }} rounded-0 js-search-near-individual {{ $attributes['class'] ?: 'py-2' }}"
  >
    <i class="{{ $iconClass ?? 'fas fa-map-marker-alt' }}"></i> {{ $enableText ?? 'Tìm kiếm gần đây' }}
  </a>
  <a
    class="btn {{ $disableBtnClass ?? 'btn-primary' }} rounded-0 d-none js-disable-search-near-individual {{ $attributes['class'] ?: 'py-2' }}"
  >
    <i class="{{ $iconClass ?? 'fas fa-redo' }}"></i> {{ $disableText ?? 'Xóa vị trí' }} 
  </a>
@else
  @if(!isAcceptLocation())
    <a class="btn {{ $enableBtnClass ?? 'btn-orange' }} rounded-0 js-search-near {{ $attributes['class'] ?: 'py-2' }}">
      <i class="{{ $iconClass ?? 'fas fa-map-marker-alt' }}"></i> {{ $enableText ?? 'Tìm kiếm gần đây' }}
    </a>
  @else
    <a
      class="btn {{ $disableBtnClass ?? 'btn-primary' }} rounded-0 js-disable-search-near {{ $attributes['class'] ?: 'py-2' }}"
      data-url="{{ route('param.remove-location') }}"
    >
      <i class="{{ $iconClass ?? 'fas fa-redo' }}"></i> {{ $disableText ?? 'Xóa vị trí' }} 
    </a>
  @endif
@endif

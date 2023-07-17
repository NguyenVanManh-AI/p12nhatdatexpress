<p class="{{ $attributes['class'] }}">
  @if (is_array($textHeaderSplit) && count($textHeaderSplit) > 1 && $numVirtual)
    {{ $textHeaderSplit[0] }} <span class="number-animate text-danger font-weight-bold"
      data-end-value="{{ $numVirtual }}" data-increment="50" data-start-value="1000" data-duration="2000"
      data-delay="1000">1.000+</span> {{ $textHeaderSplit[1] }}
  @else
    {{ data_get($homeConfig, 'header_text_block') }}
  @endif
</p>

@push('scripts_children')
  <script src="{{ asset('frontend/js/anime.min.js') }}"></script>
  <script type="text/javascript">
    (() => {
      document.querySelectorAll('.number-animate').forEach((el) => {
        const startValue = el.getAttribute('data-start-value');
        const endValue = el.getAttribute('data-end-value');
        const incrementValue = el.getAttribute('data-increment');
        const durationValue = el.getAttribute('data-duration');
        const delayValue = el.getAttribute('data-delay');

        if (endValue) numberAnimation(el, startValue, endValue, incrementValue, durationValue, delayValue);
      });
    })()
  </script>
@endpush

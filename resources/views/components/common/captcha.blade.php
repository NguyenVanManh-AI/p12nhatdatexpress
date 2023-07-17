<div class="box-recaptcha scrollable-vertical">
  {!! NoCaptcha::renderJs() !!}
  {!! NoCaptcha::display() !!}
  @if($showError && $errors->has('g-recaptcha-response'))
    <small class="text-danger">
      {{ $errors->first('g-recaptcha-response') }}
    </small>
  @endif
</div>

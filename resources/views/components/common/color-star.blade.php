@switch($type)
  @case('icon')
    <div class="color-stars-box {{ $attributes['class'] }}">
        {!! renderRating($stars) !!}
    </div>
    @break
  @case('icon-action')
    <div class="rating-stars-action cursor-pointer {{ $attributes['class'] }}">
      {!! renderRating($stars, 1) !!}
      @if($actionInputName)
        <input type="hidden" name="{{ $actionInputName }}" value="">
      @endif
    </div>
    @break
  @default
    <div class="yellow-stars-box text-center {{ $attributes['class'] }}">
      {!! renderYellowStars($stars) !!}
    </div>
@endswitch

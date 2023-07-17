<figure>
    @if(trim($script) != '')
        {!! $script !!}
    @else
        @if($image)
        <a href="{{$url ?? 'javascript:{};'}}" target="__blank">
            <img src="{{asset($image)}}" alt="" class="img-right ad-focus" style="width: 336px; height: 280px">
        </a>
        @endif
    @endif
</figure>

@if(count($featuredKeywords))
  <div class="single-tags section">
    <div class="title"><i class="fas fa-tag"></i>Từ khóa nổi bật:</div>
    <div class="tags">
      @foreach ($featuredKeywords as $keyword)
        <a href="{{ data_get($keyword, 'link') }}" class="link-light-cyan text-lowercase">
          {{ data_get($keyword, 'label') }}
          @if ($loop->index + 1 < count($featuredKeywords))
            , &nbsp;
          @endif
        </a>
      @endforeach
    </div>
  </div>
@endif

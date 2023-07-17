{{-- both classified and project & event used --}}
<div class="review-result review-result-section flex-center flex-wrap w-100">
  <div class="result-compact mb-2">
    <div class="number">{{ $rating_avg }}</div>
    <div class="star">
      <x-common.color-star :stars="$rating_avg" type="icon" class="yellow"/>
    </div>
    <div class="review-count">
      {{ $total_rating }} đánh giá
    </div>
  </div>
  <div class="result review-result-section__result-bar px-4 mr-2 mb-2">
    @for($i = 5; $i > 0; $i--)
      <div class="result-group mb-0">
        <x-common.color-star :stars="$i" type="icon" class="flat-{{ $i }}"/>
        <div class="result-bar">
          <div class="result-bar-inside" style="width: {{ ($item->ratings->where('star', $i)->count() * 100) / ($total_rating != 0 ? $total_rating : 1) }}%;">
          </div>
        </div>
        <span class="px-1">{{ $item->ratings->where('star', $i)->count() }}</span>
      </div>
    @endfor
  </div>
  <form action="" class="js-detail-rating-form mb-2">
    @csrf
    <div class="flex-column-end">
      <div class="review-receiver">
        <div class="head">Đánh giá</div>
        <div class="receiver px-5 py-3">
          <x-common.color-star :stars="$old_rating" type="icon-action" action-input-name="rating" class="star-2x dark-gray-empty-star"/>
        </div>
      </div>
    </div>
  </form>
</div>

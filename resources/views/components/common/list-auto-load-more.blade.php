<div
  class="list-news js-load-more__box"
  data-items-per-page="{{ $itemsPerPage }}"
  data-first-page="{{ request()->page }}"
  data-item-page="{{ $itemClass }}"
  data-more-url="{{ $moreUrl }}"
  data-items-per-row="{{ $itemsPerRow }}"
>
  @if(isset($itemLists))
    <div class="js-load-more__list row">
      {{ $itemLists }}
    </div>
    <x-common.loading class="inner inner-block" />
  @endif
 
  @if ($lists->lastPage() > 1)
    <x-home.layout.paginate :list="$lists">
      @slot('auto_load_page')
        @if (!$lists->onLastPage())
          <div class="btn btn-light-cyan rounded-bottom-left-0 rounded-bottom-right-0 position-relative js-load-more__auto-button">
            <input
              type="checkbox"
              class="absolute-full cursor-pointer opacity-hide js-load-more__toggle-auto"
              data-start="{{ $lists->currentPage() * $lists->currentPage() }}"
            >
            <label for="" class="m-0"><i class="fas fa-sync mr-2"></i>Tự động qua trang</label>
          </div>

          {{-- <div class="auto_load auto-paged">
            <input type="checkbox"
              name="autoload"
              value="1"
              class="js-load-more__auto-load"
            >
            <label for="" class="m-0"><i class="fas fa-sync mr-2"></i>Tự động qua trang</label>
          </div> --}}
        @endif
      @endslot
    </x-home.layout.paginate>
  @endif
</div>

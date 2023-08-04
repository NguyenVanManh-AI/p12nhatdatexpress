<div class="news mb-4">
  <div class="section-title">
    <h2 class="title">Tin mới</h2>
  </div>

  <x-common.list-auto-load-more
    :lists="$news"
    items-per-page="{{ $itemsPerPage }}"
    items-per-row="3"
    item-class=".focus-list__item"
    more-url="/focus/more-news"
  >
    <x-slot name="itemLists">
      @forelse($news as $item)
        <x-home.focus.property-item
          :property="$item"
        />
      @empty
        <div class="text-center col-12">
          <p class="p-5">Chưa có dữ liệu</p>
        </div>
      @endforelse
    </x-slot>
  </x-common.list-auto-load-more>
</div>

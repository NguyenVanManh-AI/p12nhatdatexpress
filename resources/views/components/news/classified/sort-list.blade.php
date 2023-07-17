<div class="sort js-classified-sort-list">
  <span class="classified-sort-list__text">{{ $selectedSort ?: 'Sắp xếp theo' }}</span> <i class="fas fa-caret-down"></i>
  <ul class="sort-sub">
    @foreach ($sortLists as $sort)
      <li class="js-search-classified-sort" data-value="{{ data_get($sort, 'value') }}">{{ data_get($sort, 'label') }}</li>
    @endforeach
  </ul>
</div>

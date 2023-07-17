<?php $province = isset($province) && $province ? $province : null; ?>
<div
  class="row category-search-results-box js-need-load-individual"
  data-current-page="{{ request()->page ?: 1 }}"
  data-group-type="classified"
>
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-7-3 project-main">
        <div class="property-list section">
          <div class="head-divide mb-3 flex-wrap">
            <div class="left h-100">
              <h3>{{ $province ? $province->getLabel() : data_get($group, 'group_name') }}</h3>
            </div>
            <div class="right h-100">
              <div class="md-hide p-0 h-100">
                <div class="flex-start h-100">
                  <x-news.classified.sort-list />
                  <x-common.accept-location
                    :is-individual="true"
                    enable-btn-class="btn-orange h-100"
                    disable-btn-class="btn-primary h-100"
                    disable-text="Quay lại"
                  />
                </div>
              </div>
              <div class="md-show hide p-0 h-100">
                <div class="flex-start h-100">
                  <x-news.classified.sort-list selected-text="Sắp xếp" />
                  <x-common.accept-location
                    :is-individual="true"
                    enable-btn-class="btn-orange h-100"
                    disable-btn-class="btn-primary h-100"
                    disable-text=""
                    enable-text=""
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="property-list-wrapper">
            {{-- map popup for item list --}}
            <x-common.classified.map-popup />
            <div class="row p-0 js-lists-box">
              @forelse($classifieds as $item)
                <x-news.classified.item :item="$item" />
              @empty
                <x-home.classified.add-classified-button />
              @endforelse
            </div>

            <x-common.loading class="inner inner-block" />
          </div>
        </div>

        @if ($classifieds->lastPage() > 1)
          <x-home.layout.paginate :list="$classifieds">
            @slot('auto_load_page')
              @if (!$classifieds->onLastPage())
                <div class="auto_load auto-paged">
                  <input type="checkbox" name="autoload" id="autoload" value="1" class="js-load-more-item">
                  <label for="" class="m-0"><i class="fas fa-sync mr-2"></i>Tự động qua trang</label>
                </div>
              @endif
            @endslot
          </x-home.layout.paginate>
        @endif
        <x-home.keyword-use :type="1" :group="$group" :province="$province" />
        <x-home.classified.footer-info :group="$group" :province="$province" />
      </div>
      <div class="col-md-3-7 project-sidebar-width project-sidebar">
        {{-- old should change --}}
        <x-home.classified.near :group="$group ? $group->getAncestorId() : null" />
        <x-home.consultants />
        <x-home.classified.hight-link :group="$group ? $group->getAncestorId() : null" :child="$group ? $group->id : null" :province="$province" />
        {{-- end old should change --}}
      </div>
    </div>
  </div>
  <div id="location_classified"></div>
</div>

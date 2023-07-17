<div
  class="row category-search-results-box js-need-load-individual"
  data-current-page="{{ request()->page ?: 1 }}"
  data-group-type="project"
>
  <div class="col-md-7-3 project-main">
    <div class="section mb-4">
      <div class="head-divide flex-wrap">
        <div class="left">
          <h3>{{ $group->group_name }}</h3>
        </div>
        <div class="right">
          <div class="md-hide p-0">
            <div class="flex-start">
              <x-news.classified.sort-list />
              <x-common.accept-location
                :is-individual="true"
                disable-text="Quay lại"
              />
            </div>
          </div>
          <div class="md-show hide p-0">
            <div class="flex-start">
              <x-news.classified.sort-list selected-text="Sắp xếp" />
              <x-common.accept-location
                :is-individual="true"
                disable-text=""
                enable-text=""
              />
            </div>
          </div>
        </div>
      </div>
      <div class="property-list-wrapper pt-2 border">
        <div class="row p-0 js-lists-box">
          @forelse($projects as $item)
            <x-news.project.item :item="$item" />
          @empty
            <x-news.project.not-found-item
              :keyword="$keyword ?? ''"
            />
          @endforelse
          {{-- @include('Home.Project.ProjectNews') --}}
        </div>

        <x-common.loading class="inner inner-block" />
      </div>
    </div>

    @if ($projects->lastPage() > 1)
      <x-home.layout.paginate :list="$projects">
        @slot('auto_load_page')
          @if (!$projects->onLastPage())
            <div class="auto_load auto-paged">
              <input type="checkbox" name="autoload" id="autoload" value="1" class="js-load-more-item">
              <label for="" class="m-0"><i class="fas fa-sync mr-2"></i>Tự động qua trang</label>
            </div>
          @endif
        @endslot
      </x-home.layout.paginate>
    @endif

    @if (!$projects->count())
      <x-home.project.project-interested :keyword="request('keyword')" />
    @endif

    <x-home.keyword-use :type="1" :group="$group" />

    <x-home.classified.footer-info :group="$group" />
  </div>
  <div class="col-md-3-7 project-sidebar-width project-sidebar">
    <x-project.recent-project :group="$group" />
    <x-home.event />
    <x-project.hight-link />
  </div>

  <div class="overlay"></div>
  <x-project.popup-project />
  <x-home.project.update-project />
</div>

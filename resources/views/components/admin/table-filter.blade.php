@props(['filter'])

<div>
  <div class="filter c-dashed-box mt-0">
    <h3 class="title">Bộ lọc</h3>

    <form action="" method="get" class="form-filter">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <x-common.text-input label="Từ khóa" name="keyword" value="{{ request()->keyword }}"
              placeholder="{{ $searchPlaceHolder }}" />
          </div>
        </div>

        @if (isset($filter) && $filter)
          {{ $filter }}
        @endif

        @if($deleteFilter)
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
                <x-common.filter.delete-statuses-filter />
              </div>
            </div>
          </div>
        @endif
      </div>

      <div class="text-center py-3">
        <button type="submit" class="btn btn-cyan rounded-0">
          <i class="fas fa-search"></i>
          Tìm kiếm
        </button>
      </div>
    </form>
  </div>

  @if($addRoute)
    <div class="flex-end mb-3">
      <a href="{{ $addRoute }}" class="btn btn-success btn-sm" title="Thêm">
        Thêm
        <i class="fas fa-plus"></i>
      </a>
    </div>
  @endif
</div>

@props(['filter'])
<div>
  <div class="filter c-dashed-box mt-0">
    <h3 class="title">Bộ lọc</h3>

    <form action="" method="get" class="form-filter">
      <div class="row">
        @if($searchFilter)
          <div class="col-lg-3 col-md-4">
            <div class="form-group">
              <x-common.text-input label="Từ khóa" name="keyword" value="{{ request()->keyword }}"
                placeholder="{{ $searchPlaceHolder }}" />
            </div>
          </div>
        @endif

        @if($dateRangeFilter)
          <div class="col-lg-3 col-md-4">
            <x-common.text-input label="Ngày tạo" :type="request()->start_day ? 'date' : 'text'" name="start_day" value="{{ request()->start_day }}"
              placeholder="Từ ngày" hover-date />
          </div>

          <div class="col-lg-3 col-md-4">
            <x-common.text-input class="mt-4 pt-1" :type="request()->end_day ? 'date' : 'text'" name="end_day" value="{{ request()->end_day }}"
              placeholder="Đến ngày" hover-date />
          </div>
        @endif

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
        <a href="{{ request()->url() }}" class="btn btn-warning rounded-0">
          <i class="fas fa-times"></i>
          Xóa bộ lọc
        </a>
        <button type="submit" class="btn btn-cyan rounded-0">
          <i class="fas fa-search"></i>
          Tìm kiếm
        </button>
      </div>
    </form>
  </div>

  @if($addRoute && ($check_role == 1 || key_exists(1, $check_role)))
    <div class="flex-end mb-3">
      <a href="{{ $addRoute }}" class="btn btn-success btn-sm" title="Thêm">
        Thêm
        <i class="fas fa-plus"></i>
      </a>
    </div>
  @endif
</div>

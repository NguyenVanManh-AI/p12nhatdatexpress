<div class="sticky top-3">
  <button class="btn btn-success btn-lg btn-block rounded-0 bold text-uppercase fs-14 js-select-near">
    <i class="far fa-compass fs-18 mr-1"></i>
    Tìm gần tôi
  </button>
  
  <form action="" method="get" class="search-company group-load-address">
    <x-common.text-input
      class="mb-0"
      name="keyword"
      value="{{ request()->keyword }}"
      placeholder="Tìm theo tên hoặc số điện thoại"
    />
  
    <x-common.text-input
      class="mb-0"
      name="project"
      value="{{ request()->project }}"
      placeholder="Tìm theo dự án"
    />
  
    <x-common.select2-input
      class="mb-0"
      name="rate"
      placeholder="Đánh giá"
      :items="range(1, 5)"
      sub-text="sao"
      items-current-value="{{ request()->rate }}"
      :show-error="false"
    />
  
    <x-common.select2-input
      class="mb-0"
      input-class="province-load-district"
      name="province_id"
      id="province"
      placeholder="Tỉnh/Thành Phố"
      :items="$provinces"
      item-text="province_name"
      items-current-value="{{ request()->province_id }}"
      :show-error="false"
    />
      
    <x-common.select2-input
      class="mb-0"
      input-class="district-province"
      name="district_id"
      id="district"
      placeholder="Quận/Huyện"
      data-selected="{{ request()->district_id }}"
      :show-error="false"
    />
  
    <button type="submit" class="btn btn-sm btn-light-cyan">Tìm kiếm</button>
  </form>
</div>

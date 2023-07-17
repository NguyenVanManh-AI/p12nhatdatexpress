<div class="category-search-form position-relative">
  <x-common.loading class="inner"/>

  <div class="search-head-box mb-0">
    <h3 class="title mb-0">Công cụ tìm kiếm</h3>
  </div>

  <form action="" class="search-form-box group-load-address">
    <div class="change-category-box my-1">
      @if(!in_array($activeCategory, ['can-mua', 'can-thue']))
      <div class="radio-with-button">
        <input type="radio" name="category" {{ $activeCategory == 'nha-dat-ban' ? 'checked' : '' }} value="nha-dat-ban">
        <button type="button" class="btn button-with-radio">
          Nhà đất bán
        </button>
      </div>
      <div class="radio-with-button">
        <input type="radio" name="category" {{ $activeCategory == 'nha-dat-cho-thue' ? 'checked' : '' }} value="nha-dat-cho-thue">
        <button type="button" class="btn button-with-radio">
          Nhà đất cho thuê
        </button>
      </div>
      <div class="radio-with-button">
        <input type="radio" name="category" {{ $activeCategory == 'du-an' ? 'checked' : '' }} value="du-an">
        <button type="button" class="btn button-with-radio">
          Dự án
        </button>
      </div>
      @else
      <div class="radio-with-button">
        <input type="radio" name="category" {{ $activeCategory == 'can-mua' ? 'checked' : '' }} value="can-mua">
        <button type="button" class="btn button-with-radio">
          Cần mua
        </button>
      </div>
      <div class="radio-with-button">
        <input type="radio" name="category" {{ $activeCategory == 'can-thue' ? 'checked' : '' }} value="can-thue">
        <button type="button" class="btn button-with-radio">
          Cần thuê
        </button>
      </div>
      @endif
    </div>

    <div class="select-field-box position-relative p-3 border">
      <div class="c-mb-10">
        <div class="c-mb-10">
          <div class="js-search-dropdown relative" data-url="{{ '/category-search-keyword/' . $path }}">
            <input type="text" class="js-input-search form-control" maxlength="60" name="keyword" value="{{ request()->keyword }}" autocomplete="off" placeholder="Nhập từ khóa tìm kiếm">
            <div class="dropdown-menu-custom js-search-result-body hide">
            </div>
            <span class="icon-search cursor-pointer" title="Tìm">
              <i class="fas fa-search"></i>
            </span>
          </div>
        </div>
        <div class="c-mb-10">
          <x-common.select2-input
            class="mb-0"
            name="paradigm"
            placeholder="-- Mô hình --"
            :show-error="false"
          />
        </div>
        <div class="c-mb-10">
          <x-common.select2-input
            class="mb-0"
            input-class="province-load-district"
            name="province_id"
            placeholder="-- Tỉnh/Thành Phố  --"
            :items="$provinces"
            item-text="province_name"
            items-select-name="province_id"
            :items-current-value="request()->province_id"
            :show-error="false"
          />
        </div>
        <div class="c-mb-10">
          <x-common.select2-input
            class="mb-0"
            input-class="district-province"
            name="district_id"
            placeholder="-- Quận/Huyện --"
            :data-selected="request()->district_id"
            :show-error="false"
          />
        </div>
        <div class="c-mb-10">
          <x-common.select2-input
            class="mb-0"
            name="price"
            placeholder="-- Mức giá --"
            :show-error="false"
          />
        </div>
        <div>
          <x-common.select2-input
            class="mb-0"
            name="area"
            placeholder="-- Diện tích --"
            :show-error="false"
          />
        </div>
      </div>

      <div class="search-footer flex-between">
        <button type="submit" class="btn btn-blue text-uppercase btn-search {{ !$activeCategory ? 'blur' : '' }}">
          <i class="fas fa-search mr-2"></i>
          Tìm kiếm
        </button>
        <a href="javascript:void(0)" class="open-advance-menu {{ !$activeCategory ? 'blur' : '' }}">
          <span class="opened">
            Thu gọn <<
          </span>
          <span class="closed">
            Nâng cao >>
          </span>
        </a>
        <div class="advance-search-box">
          <div class="c-mb-10 advance-search__item advance-search__project">
            <x-common.select2-input
              class="mb-0"
              name="project"
              placeholder="-- Thuộc dự án --"
              :items="$project"
              item-text="project_name"
              items-select-name="project"
              :items-current-value="request()->project"
              :show-error="false"
            />
          </div>
          <div class="c-mb-10 advance-search__item advance-search__progress">
            <x-common.select2-input
              class="mb-0"
              name="progress"
              placeholder="-- Tình trạng --"
              :disabled="true"
              :data-selected="request()->progress"
              :show-error="false"
            />
          </div>
          <div class="c-mb-10 advance-search__item advance-search__furniture">
            <x-common.select2-input
              class="mb-0"
              name="furniture"
              placeholder="-- Nội thất --"
              :disabled="true"
              :data-selected="request()->furniture"
              :show-error="false"
            />
          </div>
          <div class="c-mb-10 advance-search__item advance-search__direction">
            <x-common.select2-input
              class="mb-0"
              name="direction"
              placeholder="-- Hướng nhà --"
              :items="$direction"
              item-text="direction_name"
              items-select-name="direction"
              :items-current-value="request()->direction"
              :show-error="false"
            />
          </div>
          <div class="c-mb-10 advance-search__item advance-search__num_people">
            <x-common.select2-input
              class="mb-0"
              name="num_people"
              placeholder="-- Số người ở tối đa --"
              :items="$num_people"
              item-text="param_name"
              items-select-name="num_people"
              :items-current-value="request()->num_people"
              :show-error="false"
            />
          </div>
          <div class="c-mb-10 advance-search__item advance-search__num_bed">
            <x-common.select2-input
              class="mb-0"
              name="num_bed"
              placeholder="-- Số phòng ngủ --"
              :items="$num_bed"
              item-text="param_name"
              items-select-name="num_bed"
              :items-current-value="request()->num_bed"
              :show-error="false"
            />
          </div>
          <div class="row my-3">
            <div class="col-6 advance-search__item advance-search__monopoly">
              <div class="c-checkbox-group">
                <input {{ request()->monopoly == '1' ? 'checked' : '' }} type="checkbox" id="verify-project" name="monopoly" value="1">
                <label for="verify-project">Tin chính chủ</label>
              </div>
            </div>

            <div class="col-6 advance-search__item advance-search__broker">
              <div class="c-checkbox-group">
                <input {{ request()->monopoly == '1' ? 'checked' : '' }} type="checkbox" id="broker" name="monopoly" value="1">
                <label for="broker">Miễn môi giới</label>
              </div>
            </div>

            <div class="col-6 advance-search__item advance-search__advance_value">
              <x-common.select2-input
                class="mb-0"
                name="advance_value"
                placeholder="-- Cọc trước --"
                :items="$advance"
                item-text="param_name"
                items-select-name="advance_value"
                :items-current-value="request()->advance_value"
                :show-error="false"
              />
            </div>
            <div class="col-6 advance-search__item advance-search__internet">
              <div class="c-checkbox-group">
                <input {{ request()->internet == '1' ? 'checked' : '' }} type="checkbox" id="internet" name="internet" value="1">
                <label for="internet">Internet</label>
              </div>
            </div>
            <div class="col-6 advance-search__item advance-search__freezer">
              <div class="c-checkbox-group">
                <input {{ request()->freezer == '1' ? 'checked' : '' }} type="checkbox" id="freezer" name="freezer" value="1">
                <label for="freezer">Điều hòa</label>
              </div>
            </div>
            <div class="col-6 advance-search__item advance-search__balcony">
              <div class="c-checkbox-group">
                <input {{ request()->balcony == '1' ? 'checked' : '' }} type="checkbox" id="balcony" name="balcony" value="1">
                <label for="balcony">Ban công</label>
              </div>
            </div>
            <div class="col-6 advance-search__item advance-search__mezzanino">
              <div class="c-checkbox-group">
                <input {{ request()->mezzanino == '1' ? 'checked' : '' }} type="checkbox" id="mezzanino" name="mezzanino" value="1">
                <label for="mezzanino">Gác lửng</label>
              </div>
            </div>
            <div class="col-6 advance-search__item advance-search__loan_support">
              <div class="c-checkbox-group">
                <input {{ request()->bank_sponsor ? 'checked' : '' }} type="checkbox" id="loan_support" name="bank_sponsor" value="1">
                <label for="loan_support">Hỗ trợ vay</label>
              </div>
            </div>
            <div class="col-6 advance-search__item advance-search__ecommerce">
              <div class="c-checkbox-group">
                <input {{ request()->ecommerce ? 'checked' : '' }} type="checkbox" id="ecommerce" name="ecommerce" value="1">
                <label for="ecommerce">TTTM</label>
              </div>
            </div>
            <div class="col-6 advance-search__item advance-search__parking">
              <div class="c-checkbox-group">
                <input {{ request()->parking ? 'checked' : '' }} type="checkbox" id="parking" name="parking" value="1">
                <label for="parking">Bãi đỗ xe</label>
              </div>
            </div>
            <div class="col-6 advance-search__item advance-search__gym">
              <div class="c-checkbox-group">
                <input {{ request()->gym ? 'checked' : '' }} type="checkbox" id="gym" name="gym" value="1">
                <label for="gym">Phòng gym</label>
              </div>
            </div>
            <div class="col-6 advance-search__item advance-search__park">
              <div class="c-checkbox-group">
                <input {{ request()->park ? 'checked' : '' }} type="checkbox" id="park" name="park" value="1">
                <label for="park">Công viên</label>
              </div>
            </div>
            <div class="col-6 advance-search__item advance-search__spa">
              <div class="c-checkbox-group">
                <input {{ request()->spa ? 'checked' : '' }} type="checkbox" id="spa" name="spa" value="1">
                <label for="spa">Spa & Massage</label>
              </div>
            </div>

            <div class="col-6 advance-search__item advance-search__pool">
              <div class="c-checkbox-group">
                <input {{ request()->pool ? 'checked' : '' }} type="checkbox" id="pool" name="pool" value="1">
                <label for="pool">Hồ bơi</label>
              </div>
            </div>
            <div class="col-6 advance-search__item advance-search__kindergarten">
              <div class="c-checkbox-group">
                <input {{ request()->kindergarten ? 'checked' : '' }} type="checkbox" id="kindergarten" name="kindergarten" value="1">
                <label for="kindergarten">Nhà trẻ</label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

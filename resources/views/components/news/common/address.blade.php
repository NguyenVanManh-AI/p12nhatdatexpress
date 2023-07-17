<x-common.select2-input
  input-class="province-load-district"
  name="province_id"
  placeholder="-- Chọn tỉnh/Thành Phố  --"
  :items="$provinces"
  item-text="province_name"
  :show-error="$showError"
/>

<x-common.select2-input
  input-class="district-province"
  name="district_id"
  placeholder="-- Chọn quận/Huyện --"
  :show-error="$showError"
/>
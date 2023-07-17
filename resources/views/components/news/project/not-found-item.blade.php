<div class="col-12 project-list-wrapper project-notfound-wrapper pt-2 border-0">
  <div class="notfound-img">
    <img src="{{ asset('frontend/images/notfound.jpg') }}">
  </div>
  <h4>Không tìm thấy dự án phù hợp</h4>
  <h5>Nếu bạn muốn hiển thị dự án đang tìm kiếm. Bạn có thể yêu cầu cập nhật thêm dự án theo mẫu dưới</h5>

  <form action="{{ route('home.project.request-project') }}" id="notfound-form" method="post">
    @csrf
    <div class="mb-4 group-load-address">
      <x-common.text-input
        class="mb-2"
        input-class="bg-gray"
        name="project_name"
        value="{{ old('project_name') }}"
        placeholder="Nhập tên dự án"
        required
      />

      <x-common.text-input
        class="mb-2"
        input-class="bg-gray"
        name="investor"
        value="{{ old('investor') }}"
        placeholder="Nhập chủ đầu tư"
        required
      />

      <x-common.text-input
        class="mb-2"
        input-class="bg-gray"
        name="address"
        value="{{ old('address') }}"
        placeholder="Nhập địa chỉ"
        required
      />

      <x-common.select2-input
        class="mb-2"
        input-class="bg-gray province-load-district"
        name="province_id"
        placeholder="Tỉnh/Thành Phố "
        :items="$provinces"
        item-text="province_name"
        items-select-name="province_id"
        :items-current-value="old('province_id')"
      />
        
      <x-common.select2-input
        class="mb-2"
        input-class="bg-gray district-province district-load-ward"
        name="district_id"
        placeholder="Quận/Huyện"
        :data-selected="old('district_id')"
      />
        
      <x-common.select2-input
        input-class="bg-gray ward-district"
        name="ward_id"
        placeholder="Xã/Phường"
        :data-selected="old('ward_id')"
      />
    </div>

    <div class="text-center">
      <button class="btn btn-success px-5">Gửi</button>
    </div>
  </form>
</div>
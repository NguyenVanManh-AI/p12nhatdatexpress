@extends('Admin.Layouts.Master')

@section('Title', 'Sửa mã khuyến mãi | Mã khuyến mãi')

@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection

@section('Content')
  <x-admin.breadcrumb active-label="Chỉnh sửa mã khuyến mãi" :parents="[
      [
          'label' => 'Admin',
          'route' => 'admin.thongke',
      ],
      [
          'label' => 'Mã khuyến mãi',
          'route' => 'admin.promotion.index',
      ],
  ]" />
  <section class="content">
    <div class="container-fluid">
      <div class="px-3 pb-3">
        <form
          action="{{ route('admin.promotion.update', $promotion->id) }}"
          method="post" id="add-promotion">
          @csrf
          <div class="row">
            <div class="col-12">
              <label>Kiểu khuyến mãi</label>
              <select class="form-control" id="exampleFormControlSelect1" name="promotion_type">
                <option value="0" {{ old('promotion_type', $promotion->promotion_type) == 0 ? 'selected' : '' }}>
                  -% Thanh toán bằng express coin
                </option>
                <option value="1" {{ old('promotion_type', $promotion->promotion_type) == 1 ? 'selected' : '' }}>
                  +% Nạp tiền
                </option>
              </select>
            </div>

            <div class="col-12">
              <div class="pt-3">
                <label class="form-control-409 mr-2">
                  <input type="checkbox" name="is_all" value="1" {{ old('is_all', $promotion->is_all) ? 'checked' : '' }}>
                </label>
                <label>Áp dụng cho tất cả thành viên</label>
              </div>
            </div>

            <div class="add-category col-12">
              <div class="row">
                <div class="col-md-6">
                  <x-common.text-input
                    label="Số lần dùng tối đa"
                    name="num_use"
                    type="number"
                    value="{{ old('num_use', $promotion->num_use) }}"
                    hint="Mã khuyến mãi này sẽ có thể được dùng tối đa bao nhiêu lần?"
                    required
                  />
                </div>
                <div class="col-md-6">
                  <x-common.text-input
                    label="Phần trăm áp dụng"
                    name="value"
                    type="number"
                    value="{{ old('value', $promotion->value) }}"
                    required
                  />
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label>Thời gian bắt đầu hiển thị</label>
                    <div class="search-reponsive">
                      <input id="handleDateFrom" name="date_from" class="start_day form-control float-left"
                        type="datetime-local"
                        value="{{ \Carbon\Carbon::createFromTimestamp(old('date_from', $promotion->date_from))->format('Y-m-d\Th:i') }}">
                    </div>
                    <div class="clear-both"></div>
                    <span class="text-example">Để trống hiện tức thì</span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label>Thời gian kết thúc hiển thị</label>
                    <input id="handleDateTo" name="date_to" class="end_day form-control float-left" type="datetime-local"
                      placeholder="Ngày bắt đầu"
                      value="{{ \Carbon\Carbon::createFromTimestamp(old('date_to', $promotion->date_from))->format('Y-m-d\Th:i') }}">
                    <div id="appendDateError"></div>
                    <div class="clear-both"></div>
                    <span class="text-example">Để trống là 10 năm</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="flex-center">
            <button class="btn btn-primary rounded-0 mr-3">
              Hoàn tất
            </button>
            <a href="{{ request()->url() }}" class="btn btn-secondary rounded-0 mr-2">
              Làm lại
            </a>
          </div>
        </form>
      </div>
    </div>
  </section>
@endsection

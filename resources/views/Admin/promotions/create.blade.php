@extends('Admin.Layouts.Master')

@section('Title', 'Thêm mã khuyến mãi | Mã khuyến mãi')

@section('Style')
  <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection

@section('Content')
  <x-admin.breadcrumb active-label="Thêm mã khuyến mãi" :parents="[
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
        <form action="{{ route('admin.promotion.store') }}" method="post" id="add-promotion">
          @csrf
          <div class="row mb-4">
            <div class="col-12">
              <label>Số lượng mã tạo ra</label>
              @if ($errors->has('quanlity_code'))
                <small class="text-danger mt-1 float-right" style="font-size: 90%">
                  {{ $errors->first('quanlity_code') }}
                </small>
              @endif
              <input class="form-control required" type="number" name="quanlity_code" value="1">
              <span class="promotion-warring">Lưu ý: khi dùng chức năng này! Hệ thống sẽ tự động tạo ra 1 lượng lớn(số nhập
                vào) mã số tương ứng với cấu hình bên dưới chỉ khác nhau về mã số!</span>
            </div>

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
              <div class="row">
                <div class="col-md-6">
                  <div class="pt-3 form-group">
                    <label class="form-control-409 mr-2">
                      <input id="checkall" type="radio" name="radio_button" value="is_all" {{ old('is_all', $promotion->is_all) ? 'checked' : '' }}>
                    </label>
                    <label>Áp dụng cho tất cả thành viên</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="pt-3 form-group">
                    <label class="form-control-409 mr-2">
                      <input id="checkprivate" type="radio" value="is_private" name="radio_button" {{ old('is_all', $promotion->is_all) }}>
                    </label>
                    <label>Áp dụng cho khuyến mãi nhận tại trang</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12">
              <label>Thành viên áp dụng</label>
              <div class="form-group select-tags">
                <select name="list_users[]" class="chosen" data-order="true" id="multiselect" multiple="true"
                  style="height: 38px">
                  @foreach ($list_users as $item)
                    <option value="{{ $item->id }}">[ID: {{ $item->id }}] {{ $item->username }}</option>
                  @endforeach
                </select>
                <span class="promotion-warring">Lưu ý: khi dùng chức năng này! Hệ thống sẽ tạo ra số mã theo số lượng thành
                  viên mà không phụ thuộc vào số lượng mã nhập bên trên, mã sẽ áp dụng cho từng đối tượng cụ thể!</span>
              </div>
            </div>

            <div class="add-category col-12">
              <div class="row">
                <div class="col-md-6">
                  <x-common.text-input
                    label="Số lần dùng tối đa"
                    name="num_use"
                    type="number"
                    value="{{ old('num_use') }}"
                    hint="Mã khuyến mãi này sẽ có thể được dùng tối đa bao nhiêu lần?"
                    required
                  />
                </div>

                <div class="col-md-6">
                  <x-common.text-input
                    label="Phần trăm áp dụng"
                    name="value"
                    type="number"
                    value="{{ old('value') }}"
                    required
                  />
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>Thời gian bắt đầu hiển thị</label>
                    <div class="search-reponsive col-12 p-0">
                      <div style="position: relative">
                        <input id="handleDateFrom" name="date_from" class="start_day form-control float-left"
                          type="datetime-local"
                          value="{{ \Carbon\Carbon::createFromTimestamp(old('date_from', now()->timestamp))->format('Y-m-d\Th:i') }}">
                      </div>
                    </div>
                  </div>
                  <span class="text-example">Để trống hiện tức thì</span>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Thời gian kết thúc hiển thị</label>
                    <div style="position: relative">
                      <input id="handleDateTo" name="date_to" class="end_day form-control float-left" type="datetime-local"
                        placeholder="Ngày bắt đầu"
                        value="{{ \Carbon\Carbon::createFromTimestamp(old('date_to', now()->addYears(10)->timestamp))->format('Y-m-d\Th:i') }}">
                    </div>
                    <div id="appendDateError"></div>
                  </div>
                  <span class="text-example">Để trống là 10 năm</span>
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

@section('Script')
  <script src="js/table.js"></script>

  <script type="text/javascript">
    $('#multiselect').change(function() {
      if (this.value != "") {
        $('#checkall').prop('checked', false);
        $("#checkall").prop("disabled", true);
        $('#checkprivate').prop('checked', false);
        $("#checkprivate").prop("disabled", true);
      } else {
        $('#checkall').prop('checked', true);
        $("#checkall").prop("disabled", false);

        $("#checkprivate").prop("disabled", false);
      }
    })

    setMinMaxDate('#handleDateFrom', '#handleDateTo')
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"
    integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css"
    integrity="sha512-0nkKORjFgcyxv3HbE4rzFUlENUMNqic/EzDIeYCgsKa/nwqr2B91Vu/tNAu4Q0cBuG4Xe/D1f/freEci/7GDRA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script type="text/javascript">
    $(".chosen").chosen({
      width: "300px",
      enable_search_threshold: 10
    }).change(function(event) {
      if (event.target == this) {
        var value = $(this).val();
        $("#result").text(value);
      }
    });
    $('.chosen-search-input').val("Nhập danh sách")
  </script>
@endsection

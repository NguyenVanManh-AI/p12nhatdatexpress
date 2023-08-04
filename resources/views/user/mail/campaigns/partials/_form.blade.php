<div class="card rounded-0 mb-4">
  <h5 class="card-header bg-dark-main text-white text-center text-uppercase rounded-0 fs-16">Tên chiến dịch và nội dung Mail</h5>
  <div class="card-body">
    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Nhập tên chiến dịch</label>
      <div class="col-sm-9">
        <x-common.text-input
          class="mb-0"
          name="campaign_name"
          value="{{ old('campaign_name', $campaign->campaign_name) }}"
          placeholder="Nhập tên chiến dịch"
        />
      </div>
    </div>

    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Chon mẫu mail gửi</label>
      <div class="col-sm-9 d-flex align-items-start">
        <x-common.select2-input
          class="flex-1 mb-0 mr-3 select2-auto miw-1"
          name="mail_template_id"
          :items="$mail_templates"
          item-text="mail_header"
          placeholder="Chúc mừng sinh nhật"
          items-current-value="{{ old('mail_template_id', $campaign->mail_template_id) }}"
        />
        <a href="{{ route('user.template-mail') }}" class="btn btn-light-cyan py-2">
          <i class="fas fa-plus-circle"></i>&nbsp;Tạo mẫu mail
        </a>
      </div>
    </div>
  </div>
</div>

<div class="card rounded-0 mb-4 mail-campaign__customer-fields">
  <h5 class="card-header bg-dark-main text-white text-center text-uppercase rounded-0 fs-16">Đối tượng nhận Mail</h5>
  <div class="card-body">
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">Nhập email hoặc tên khách hàng</label>
      <div class="col-sm-8">
        <x-common.select2-input
          class="mb-0"
          name="customers[]"
          :items="$customers"
          :items-current-value="old('customers', $campaign->customers->pluck('id')->toArray())"
          item-text="label"
          placeholder="Nhập email hoặc tên khách hàng"
          multiple
        />
      </div>
    </div>

    <div class="mb-4">
      <span class="text-gray">Lưu ý khi sử dụng tính năng nhập mail hoặc tên khách hàng thì các chức năng lọc bên dưới sẽ bị vô hiệu hóa . Mail nhập vào là mail đã có trong mục khách hàng</span>
      {{-- , cách nhau bằng dấu "<span class="text-danger">,</span>" --}}
    </div>

    <div class="row">
      <div class="col-md-6">
        <x-common.select2-input
          label="Tình trạng khách hàng"
          name="cus_status"
          :items="$statuses"
          item-text="param_name"
          placeholder="Tất cả"
          items-current-value="{{ old('cus_status', $campaign->cus_status) }}"
        />
      </div>
      <div class="col-md-6">
        <x-common.select2-input
          label="Nguồn phát sinh"
          name="cus_source"
          :items="$sources"
          item-text="param_name"
          placeholder="Tất cả"
          items-current-value="{{ old('cus_source', $campaign->cus_source) }}"
        />
      </div>
      <div class="col-md-6">
        <x-common.select2-input
          label="Nghề nghiệp"
          name="cus_job"
          :items="$jobs"
          item-text="param_name"
          placeholder="Tất cả"
          items-current-value="{{ old('cus_job', $campaign->cus_job) }}"
        />
      </div>
      <div class="col-md-6">
        <x-common.select2-input
          label="Khu vực"
          name="province_id"
          :items="$provinces"
          item-text="province_name"
          placeholder="Tất cả"
          items-current-value="{{ old('province_id', $campaign->province_id) }}"
        />
      </div>
      <div class="col-md-6">
        <x-common.text-input
          label="Thời gian phát sinh"
          :type="old('date_from', $campaign->date_from) ? 'date' : 'text'"
          name="date_from"
          value="{{ old('date_from', $campaign->date_from ? $campaign->date_from->format('Y-m-d') : '') }}"
          placeholder="Từ"
          hover-date
        />
      </div>

      <div class="col-md-6">
        <x-common.text-input
          class="mt-md-4 pt-md-2"
          :type="old('date_to', $campaign->date_to) ? 'date' : 'text'"
          name="date_to"
          value="{{ old('date_to', $campaign->date_to ? $campaign->date_to->format('Y-m-d') : '') }}"
          placeholder="Đến"
          hover-date
        />
      </div>
    </div>

    <div class="js-show-list-customers">
       Số người nhận mail: <span class="text-light-cyan js-show-list-customers__count">0</span>
    </div>
  </div>
</div>

<div class="card rounded-0 mb-4 mail-campaign__schedule-fields">
  <h5 class="card-header bg-dark-main text-white text-center text-uppercase rounded-0 fs-16">Lịch trình gửi</h5>
  <div class="card-body">
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="row">
          <label class="col-sm-4 col-form-label">Ngày gửi</label>
          <div class="col-sm-8">
            <x-common.text-input
              class="mb-0"
              type="datetime-local"
              name="start_date"
              value="{{ old('start_date', $campaign->start_date) }}"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="form-inline mt-2">
          <label>Tự động gửi mail chúc mừng sinh nhật &nbsp;</label>
          <input type="checkbox" name="is_birthday" value="1" class="checkbox" {{ old('is_birthday', $campaign->is_birthday) ? 'checked' : '' }}>
        </div>
      </div>
    </div>
    <span class="text-gray">Lưu ý không chọn chức năng trong mục này nếu bạn muốn tạo chiến dịch gửi ngay</span>
  </div>
</div>

@push('scripts_children')
<script type="text/javascript">
  (() => {
    const checkCustomerInput = () => {
      let $parents = $('.mail-campaign__customer-fields'),
      customerIds = $parents.find('[name="customers[]"]').val(),
        disableInputNames = [
          'province_id', 'date_from', 'date_to', 'cus_job', 'cus_source', 'cus_status'
        ];

      disableInputNames.forEach(inputName => {
        if (customerIds && customerIds.length) {
          $parents.find(`[name="${inputName}"]`).val('').prop('disabled', true).trigger('change')
        } else {
          $parents.find(`[name="${inputName}"]`).prop('disabled', false).trigger('change')
        }
      });

      showCountCustomers(customerIds.length || 0)
    }

    const getListCustomers = () => {
      let $form = $('.js-campaigns-form')

      let data = {
        province_id: $form.find('[name="province_id"]').val(),
        date_from: $form.find('[name="date_from"]').val(),
        date_to: $form.find('[name="date_to"]').val(),
        cus_job: $form.find('[name="cus_job"]').val(),
        cus_source: $form.find('[name="cus_source"]').val(),
        cus_status: $form.find('[name="cus_status"]').val(),
      }

      data = Object.fromEntries(Object.entries(data).filter(([_, v]) => v));

      if (!data || !Object.keys(data).length) {
        showCountCustomers(0)
        return
      }

      $.ajax({
        url: '/user/campaigns/list-customers',
        method: 'GET',
        dataType: 'JSON',
        data: data,
        success: res => {
          console.log(res)
          showCountCustomers(res.data.customers_count)
        },
        error: () => {
          showCountCustomers(0)
        }
      })
    }

    const showCountCustomers = count => {
      $('.js-show-list-customers__count').html(count)
    }

    const checkScheduleInput = () => {
      let $parents = $('.mail-campaign__schedule-fields'),
        $startDayInput = $parents.find('[name="start_date"]')
        $birthDayInput = $parents.find('[name="is_birthday"]')
        startDay = $startDayInput.val(),
        birthDay = $birthDayInput.prop('checked');

      $birthDayInput.prop('disabled', startDay ? true : false)
      $startDayInput.prop('disabled', birthDay ? true : false)
    }

    $('.mail-campaign__customer-fields [name="customers[]"]').on('change', function () {
      checkCustomerInput();
    })

    $('.mail-campaign__customer-fields').on('change', 'input, select', function () {
      let $form = $('.js-campaigns-form'),
        customerIds = $form.find('[name="customers[]"]').val();

      if (!customerIds || !customerIds.length)
        getListCustomers()
    })

    $('.mail-campaign__schedule-fields [name="start_date"]').on('change', function () {
      checkScheduleInput();
    })

    $('.mail-campaign__schedule-fields [name="is_birthday"]').on('change', function () {
      checkScheduleInput();
    })

    checkCustomerInput();
    checkScheduleInput();
  }) ()
</script>
@endpush

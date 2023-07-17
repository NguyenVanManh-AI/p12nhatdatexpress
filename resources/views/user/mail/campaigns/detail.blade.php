@extends('user.layouts.master')

@section('title', 'Danh sách chi tiết gửi mail')

@section('content')
  <x-user.breadcrumb
    active-label="Danh sách chi tiết gửi mail"
    :parents="[
      [
        'label' => 'Thành viên',
        'route' => 'user.index'
      ],
      [
        'label' => 'Quản lý chiến dịch',
        'route' => 'user.campaigns.index'
      ],
    ]"
  />

  <div class="container-fluid">
    <div class="table-responsive">
      <table class="table table-sendmail table-introduce mb-0">
        <thead>
          <tr class="bg-dark-main text-white">
            <th class="stt">STT</th>
            <th>Email gửi</th>
            <th>Email nhận</th>
            <th>Trạng thái</th>
            <th>Ngày gửi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($details as $detail)
            <tr>
                <td>
                  {{ ($details->currentpage()-1) * $details->perpage() + $loop->index + 1 }}
                </td>
              <td>{{ data_get($detail, 'userMailConfig.mail_username') }}</td>
              <td>{{ data_get($detail, 'customer.email') }}</td>
              <td>
                <?php $statusesMap = ['warning', 'success', 'danger']; ?>
                <span class="badge badge-pill badge-{{ data_get($statusesMap, $detail->receipt_status, 'danger') }}">
                  {{ $detail->getStatusLabel() }}
                </span>
              </td>
              <td>
                {{ $detail->receipt_time ? date('d/m/Y H:i:s', $detail->receipt_time) : '---' }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @if($details->count())
    <div class="table-bottom d-flex flex-wrap align-items-center justify-content-between pb-3">
      <div class="text-left d-flex align-items-center flex-wrap group-option-top mb-2">
        <div class="d-flex flex-wrap align-items-center justify-content-between mr-4 p-2">
          <div class="d-flex mr-2 align-items-center">Hiển thị</div>

          <form action="{{ route('user.campaigns.index') }}" method="GET">
            <label class="select-custom2">
              <select id="paginateNumber" name="items" onchange="submitPaginate(event, this)">
                <option @if (request()->items == 10) {{ 'selected' }} @endif value="10">10</option>
                <option @if (request()->items == 20) {{ 'selected' }} @endif value="20">20</option>
                <option @if (request()->items == 30) {{ 'selected' }} @endif value="30">30</option>
              </select>
            </label>
          </form>
        </div>
      </div>

      <div class="bottom-account-business mb-2">
        <div class="row">
          <div class="col-12 pagenate-bottom">
            {{ $details->render('user.page.pagination') }}
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>
@endsection

@section('script')
<script type="text/javascript">
  function submitPaginate(event) {
    const uri = window.location.toString();
    const exist = uri.indexOf('?')
    const existItems = uri.indexOf('?items')
    const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
    exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() :
    window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
  }
</script>
@endsection

@extends('user.layouts.master')

@section('title', 'Quản lý chiến dịch')

@section('content')
  <x-user.breadcrumb active-label="Quản lý chiến dịch" />

  <div class="container-fluid">
    <div class="table-responsive">
      <table class="table table-sendmail table-introduce mb-0">
        <thead>
          <tr class="bg-dark-main text-white">
            <th class="stt">STT</th>
            <th>Tên chiến dịch</th>
            <th>Mẫu sử dụng</th>
            <th>Tổng số mail</th>
            <th>Đã gửi</th>
            <th>Loại</th>
            <th>Thời gian bắt đầu</th>
            <th>Trạng thái</th>
            <th class="setting">Cài đặt</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($campaigns as $item)
            <tr>
                <td>
                  {{ ($campaigns->currentpage()-1) * $campaigns->perpage() + $loop->index + 1 }}
                </td>
              <td>{{ $item->campaign_name }}</td>
              <td>
                <span class="mr-1">
                  {{ data_get($item->template, 'mail_header') }}
                </span>
                {{-- view content modal --}}
                <a
                  href="javascript:void(0);"
                  data-toggle="modal"
                  data-target="#user-mail-view-content-{{ $item->mail_template_id }}"
                >
                  Xem
                </a>
                @include('user.mail.template.partials._view-content', [
                  'userMail' => $item->template
                ])
              </td>
              <td>{{ $item->details->count() }}</td>
              <td>
                @if($item->details()->where('receipt_status', '!=', 0)->count())
                  <span class="text-danger cursor-pointer" data-title="Số mail gửi bị lỗi">
                    {{ $item->details()->where('receipt_status', 2)->count() }}
                  </span>
                  /
                  <span class="cursor-pointer" data-title="Tổng số mail gửi">
                    {{ $item->details()->where('receipt_status', '!=', 0)->count() }}
                  </span>
                @endif
              </td>
              <td>
                @if($item->start_date == null && $item->is_birthday == 0)
                  Gửi ngay
                @elseif($item->is_birthday == 1 && $item->start_date == null)
                  Sinh nhật
                @else
                  Đặt lịch
                @endif
              </td>
              <td>{{ $item->start_date ? $item->start_date->format('d-m-Y H:i') : '' }}</td>
              <td>
                <span class="badge badge-sm badge-{{ $item->getStatusClass() }}">
                  {{ $item->getStatusLabel() }}
                </span>
              </td>
              <td class="setting-table-1 text-left">
                <div class="row">
                  @if($item->canEdit())
                    <div class="col-6">
                      <a href="{{ route('user.campaigns.edit', $item->id) }}" class="mr-2 mb-2 d-inline-block">
                        <i class="fas fa-cog"></i>
                        <span class="text-cyan">
                          Chỉnh sửa
                        </span>
                      </a>
                    </div>
                  @endif

                  <div class="col-6">
                    <a
                      href="{{ route('user.campaigns.view-details', $item) }}"
                      class="mr-2 mb-2 d-inline-block text-success"
                    >
                      <i class="fas fa-eye"></i>
                      Xem chi tiết
                    </a>
                  </div>

                  <div class="col-6">
                    <form action="{{ route('user.campaigns.destroy', $item) }}" class="d-inline-block mb-2" method="POST">
                      @csrf
                      <span class="icon-small-size mx-1">
                        <i class="fas fa-times"></i>
                      </span>
                      <button class="submit-accept-alert btn btn-link text-danger fw-500 p-0" title="Xóa" type="button" data-action="xóa">
                        Xóa
                      </button>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @if($campaigns->count())
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
            {{ $campaigns->render('user.page.pagination') }}
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

@extends('user.layouts.master')
@section('title', 'Danh sách sự kiện')

@section('content')
  <div class="p-4">
    <div class="table-account-business c-normal-table">
      <table class="table" border="1">
        <thead>
          <tr>
            <th>
              <input type="checkbox" class="select-all checkbox">
            </th>
            <th>Tiêu đề </th>
            <th>Ngày tổ chức</th>
            <th class="set-widt">Nhận tư vấn </th>
            <th>Tình trạng</th>
            <th class="fixed">Cài đặt</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($events as $event)
            <tr>
              <td class="td-checkbox">
                <input type="checkbox" value="{{ $event->id }}" class="select-item checkbox" name="select_item[]" />
              </td>
              <td>
                <div class="name-business-post">
                  <a href="{{ route('home.event.detail', $event->event_url) }}"
                    class="{{ $event->isHighLight() ? 'link-red-flat' : '' }}">
                    {{ strlen($event->event_title) > 40 ? substr($event->event_title, 0, 39) . '...' : $event->event_title }}
                  </a>
                </div>
                <div class="list-item-name-business-account">
                  <div class="item-name-business-account">
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    <span>Đăng ngày: {{ vn_date($event->created_at) }}</span>
                  </div>
                  <div class="item-name-business-account">
                    <i class="fa fa-area-chart" aria-hidden="true"></i>
                    <span>Tổng lượt xem: {{ $event->num_view }}</span>
                  </div>
                  <div class="item-name-business-account">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                    <span>Lượt xem trong ngày: {{ $event->num_view_today }}</span>
                  </div>
                </div>
              </td>
              <td class="text-center">
                <p class="time mb-1">{{ date('G', $event->start_date) }}h{{ date('i', $event->start_date) }}</p>
                <p class="date">{{ date('d/m/Y', $event->start_date) }}</p>
              </td>
              <td>
                <div class="info-account-create">4</div>
                {{-- <div class="info-account-create">{{ $event->customers->count() }}</div> --}}
              </td>
              @if ($event->is_confirmed == 0)
                <td class="status-account yellow text-center">
                  <span class="bg-white p-1 fs-14 px-2 py-1 rounded">Chờ duyệt</span>
                </td>
              @elseif($event->is_confirmed == 1)
                <td class="status-account text-center">
                  <span class="bg-white p-1 fs-14 px-2 py-1 rounded">Đã duyệt</span>
                </td>
              @elseif($event->is_confirmed == 3)
                <td class="status-account brow text-center">
                  <span class="bg-white p-1 fs-14 px-2 py-1 rounded">Hết hạn</span>
                </td>
              @else
                <td class="status-account pink text-center">
                  <span class="bg-white p-1 fs-14 px-2 py-1 rounded">'Không duyệt'</span>
                </td>
              @endif

              <td>
                <div class="ml-3 mb-2">
                  <i class="fas fa-undo-alt mr-2"></i>
                  <a href="javascript:{}" class="text-primary action_restore" data-c="{{ $event->created_by }}"
                    data-id="{{ $event->id }}" data-created="{{ \Crypt::encryptString($event->created_by) }}">Khôi
                    phục</a>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <form action="{{ route('user.events.restore-multiple') }}" class="restore-item-form" method="POST">
        @csrf
        <input type="hidden" name="ids">
      </form>
    </div>

    <div class="group-option-eventlist table-bottom d-flex flex-wrap align-items-center justify-content-between mb-4  pb-5 py-3" >
      <div class="text-left d-flex align-items-center flex-wrap group-option-top mb-2">
        <div class="manipulation d-flex mr-2 p-2">
          {{-- <img src="/system/image/manipulation.png" alt="" id="btnTop"> --}}
          <img src="{{ asset('/images/icons/redo.png') }}" class="js-go-to-top cursor-pointer rotate-90 mr-2"
            title="Về đầu trang" alt="">

          <div class="btn-group ml-1">
            <button type="button" class="btn btn-blue dropdown-toggle dropdown-custom" data-toggle="dropdown"
              aria-expanded="false" data-flip="false" aria-haspopup="true">
              Thao tác
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item restoreItem" type="button" href="javascript:{}">
                <i class="fas fa-undo-alt bg-blue p-1 mr-2 rounded text-center"></i>Khôi phục
              </a>
            </div>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between mx-4">
          <div class="d-flex mr-2 align-items-center">Hiển thị</div>

          <form action="{{ route('user.events.index') }}" method="GET">
            <label class="select-custom2">
              <select id="paginateNumber" name="items" onchange="submitPaginate(event, this)">
                <option @if (request()->items_per_page == 10) {{ 'selected' }} @endif value="10">10</option>
                <option @if (request()->items_per_page == 20) {{ 'selected' }} @endif value="20">20</option>
                <option @if (request()->items_per_page == 30) {{ 'selected' }} @endif value="30">30</option>
              </select>
            </label>
          </form>
        </div>
        <a href="{{ route('user.events.index') }}" class="btn btn-primary" title="Quay lại">Quay lại</a>
      </div>

      <div class="bottom-account-business">
        <div class="row">
          <div class="col-12 pagenate-bottom">
            {{ $events->render('user.page.pagination') }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script src="{{asset('system/js/table.js')}}" type="text/javascript"></script>

  <script type="text/javascript">
    function submitPaginate(event) {
      const uri = window.location.toString();
      const exist = uri.indexOf('?')
      const existItems = uri.indexOf('?items')
      const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
      exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() :
        window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
    }

    function restoreItem(id) {
      if (!id) return
      Swal.fire({
        title: 'Xác nhận khôi phục!',
        text: `Sau khi khôi phục sẽ được chuyển sang danh sách`,
        icon: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        customClass: {
          confirmButton: 'btn btn-success btn-lg mr-2',
          cancelButton: 'btn btn-outline-secondary btn-lg'
        },
        confirmButtonText: 'Khôi phục',
        cancelButtonText: 'Hủy',
      }).then(e => {
        if (e.isConfirmed) {
          $('.restore-item-form input[name="ids"]').val(id)
          $('.restore-item-form').trigger('submit')
        }
      })
    }

    $(document).ready(function() {
      // restore click
      $('.action_restore').click(function() {
        restoreItem($(this).data('id'))
      })

      // restote
      $('.dropdown-item.restoreItem').click(function() {
        const selectedArray = getSelected();
        if (!selectedArray) return;

        Swal.fire({
          title: 'Xác nhận khôi phục!',
          text: `Sau khi khôi phục sẽ được chuyển sang danh sách`,
          icon: 'warning',
          showCancelButton: true,
          buttonsStyling: false,
          customClass: {
            confirmButton: 'btn btn-success btn-lg mr-2',
            cancelButton: 'btn btn-outline-secondary btn-lg'
          },
          confirmButtonText: 'Khôi phục',
          cancelButtonText: 'Hủy',
        }).then((e) => {
          if (e.value) {
            let ids = [];
            selectedArray.forEach(element => {
              if ($(element).val())
                ids.push($(element).val())
            })

            $('.restore-item-form input[name="ids"]').val(ids)
            $('.restore-item-form').trigger('submit')
          } else {
            e.dismiss;
          }
        })
      })
    });
  </script>
@endsection

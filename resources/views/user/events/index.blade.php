@extends('user.layouts.master')
@section('title', 'Danh sách sự kiện')

@section('content')
  <div class="p-4">
    <form action="">
      <div class="row">
        <div class="col-md-7 col-sm-5">
          <div class="form-group">
            <label class="font-weight-normal text-dark-blue" for="">Nhập từ khóa</label>
            <input type="text" name="keyword" value="{{ request()->keyword }}" class="form-control">
          </div>
        </div>
        <div class="col-md-3 col-sm-4">
          <div class="form-group">
            <label class="font-weight-normal text-dark-blue" for="">Trạng thái</label>
            <select name="status" class="form-control">
              <option value="">-- Lựa chọn --</option>
              <option value="0" {{ request()->status === '0' ? 'selected' : '' }}>Chờ duyệt</option>
              <option value="1" {{ request()->status === '1' ? 'selected' : '' }}>Đã duyệt</option>
              <option value="2" {{ request()->status === '2' ? 'selected' : '' }}>Không duyệt</option>
              <option value="3" {{ request()->status === '3' ? 'selected' : '' }}>Hết hạn</option>
            </select>
          </div>
        </div>
        <div class="col-md-2 col-sm-3">
          <div class="mt-4"></div>
          <button type="submit" class="mt-2 btn btn-light-cyan">
            <i class="fas fa-search"></i>
            Tìm kiếm
          </button>
        </div>
      </div>
    </form>

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
                <div class="list-event-setting">
                  @if($event->canHighlight())
                    <div>
                      <form action="{{ route('user.events.highlight', $event) }}" class="d-inline-block" method="POST">
                        @csrf
                        <span class="icon-small-size mr-1">
                          <i class="fas fa-star"></i>
                        </span>
                        <button type="button" class="submit-accept-alert btn btn-link link-light-cyan p-0"
                          title="Làm nổi bật"
                          data-action="làm nổi bật">
                          Làm nổi bật
                          <span class="text-danger bg-transparent m-0 p-0">({{ data_get($serviceFee, 'service_coin') }}
                            coins)</span>
                        </button>
                      </form>
                    </div>
                  @endcan
                  {{-- @if ($event->canEdit()) --}}
                  <div>
                    <span class="icon-small-size mr-1">
                      <i class="fas fa-cog"></i>
                    </span>
                    <a href="{{ route('user.events.edit', $event) }}" class="link-light-cyan" title="Chỉnh sửa">
                      Chỉnh sửa
                    </a>
                  </div>
                  {{-- @endif --}}
                  <div>
                    <span class="icon-small-size mr-1">
                      <i class="fas fa-times"></i>
                    </span>
                    {{-- {{ route('user.events.destroy', $event) }} --}}
                    <a href="javascript:void(0);" data-id="{{ $event->id }}" class="link-red-flat setting-item delete"
                      title="Xóa">
                      Xóa
                    </a>
                  </div>

                  {{-- chưa có detail thông báo --}}
                  <div>
                    <span class="icon-small-size mr-1">
                      <i class="fas fa-comment"></i>
                    </span>
                    <a href="#" class="link-light-cyan" title="Thông báo">
                      Thông báo
                      {{-- <span class="badge badge-danger rounded-circle right">2</span> --}}
                    </a>
                  </div>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <form action="{{ route('user.events.delete-multiple') }}" class="delete-item-form d-none" method="POST">
      @csrf
      <input type="hidden" name="ids">
    </form>
  </div>

  <div class="group-option-eventlist table-bottom d-flex flex-wrap align-items-center justify-content-between mb-4 py-3">
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
            <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">
              <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"></i>
              Thùng rác
              <input type="hidden" name="action" value="trash">
            </a>
          </div>
        </div>
      </div>
      <div class="d-flex flex-wrap align-items-center justify-content-between mx-4 p-2">
        <div class="d-flex mr-2 align-items-center">Hiển thị</div>

        <form action="{{ route('user.events.index') }}" method="GET">
          <label class="select-custom2">
            <select id="paginateNumber" name="items" onchange="submitPaginate(event, this)">
              <option @if (request()->items == 10) {{ 'selected' }} @endif value="10">10</option>
              <option @if (request()->items == 20) {{ 'selected' }} @endif value="20">20</option>
              <option @if (request()->items == 30) {{ 'selected' }} @endif value="30">30</option>
            </select>
          </label>
        </form>
      </div>
      <div class="view-trash p-2">
        <a class="fs-normal" href="{{ route('user.events.trash') }}">
          <span class="mr-2 text-dark">
            <i class="far fa-trash-alt"></i>
          </span>
          <span class="link-light-cyan text-underline mr-1">
            Xem thùng rác
          </span>
        </a>
        <span class="badge badge-danger badge-right badge-sm rounded-circle">{{ $countTrash }}</span>
      </div>
    </div>

    <div class="bottom-account-business mb-2">
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

  function deleteItem(id) {
    if (!id) return
    Swal.fire({
      title: 'Xác nhận xóa',
      text: "Sau khi xóa sẽ chuyển vào thùng rác!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      cancelButtonText: 'Quay lại',
      confirmButtonText: 'Đồng ý'
    }).then((result) => {
      if (result.isConfirmed) {
        $('.delete-item-form input[name="ids"]').val(id)
        $('.delete-item-form').trigger('submit')
      }
    })
  }

  $(document).ready(function() {
      // remove click
      $('.setting-item.delete').click(function(e) {
        e.preventDefault()
        deleteItem($(this).data('id'))
      })

      // move to trash
      $('.dropdown-item.moveToTrash').click(function() {
        const selectedArray = getSelected();
        if (selectedArray) {
          Swal.fire({
            title: 'Xác nhận xóa',
            text: "Sau khi xóa sẽ chuyển vào thùng rác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
          }).then((result) => {
            if (result.isConfirmed) {
              let ids = [];
              selectedArray.forEach(element => {
                if ($(element).val())
                  ids.push($(element).val())
              })

              $('.delete-item-form input[name="ids"]').val(ids)
              $('.delete-item-form').trigger('submit')
            }
          });
        }
      })
    });
</script>
@endsection

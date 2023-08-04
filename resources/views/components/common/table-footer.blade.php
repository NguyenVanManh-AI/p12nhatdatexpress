<form action="" class="js-table__action-form d-none" data-delete-url="{{ $deleteUrl }}"
  data-restore-url="{{ $restoreUrl }}" method="POST">
  @csrf
  <input type="hidden" name="ids">
</form>

<div class="group-option-eventlist table-bottom d-flex flex-wrap align-items-center justify-content-between mb-4 py-3">
  <div class="text-left d-flex align-items-center flex-wrap group-option-top mb-2">
    <div class="manipulation d-flex mr-2 p-2">
      <img src="{{ asset('/images/icons/redo.png') }}" class="js-go-to-top cursor-pointer rotate-90 mr-2"
        title="Về đầu trang" alt="">

      @if (!$hideAction && ($deleteUrl || $restoreUrl))
        <div class="btn-group ml-1">
          <button type="button" class="btn btn-blue dropdown-toggle dropdown-custom" data-toggle="dropdown"
            aria-expanded="false" data-flip="false" aria-haspopup="true">
            Thao tác
          </button>

          <div class="dropdown-menu">
            @if (isset($moreAction) && $moreAction)
              {{ $moreAction }}
            @endif

            @if ($deleteUrl)
              <a class="dropdown-item js-delete-multiple" type="button" href="javascript:{}" title="Xóa">
                <i class="fas fa-trash bg-red p-1 mr-2 rounded"></i> Xóa
              </a>
            @endif
            @if ($restoreUrl)
              <a class="dropdown-item js-restore-multiple" type="button" href="javascript:{}" title="Khôi phục">
                <i class="fas fa-redo bg-success p-1 mr-2 rounded"></i> Khôi phục
              </a>
            @endif
          </div>
        </div>
      @endif
    </div>

    <div class="flex-start p-2">
      <span class="mr-2">Hiển thị:</span>
      <form method="get" id="paginateform" action="">
        <select class="custom-select" id="paginateNumber" name="items">
          <option @if (request()->items == 10) {{ 'selected' }} @endif value="10">
            10
          </option>
          <option @if (request()->items == 20) {{ 'selected' }} @endif value="20">
            20
          </option>
          <option @if (request()->items == 30) {{ 'selected' }} @endif value="30">
            30
          </option>
        </select>
      </form>
    </div>

    @if ($viewTrashUrl)
      <div class="view-trash p-2">
        <a class="fs-normal" href="{{ $viewTrashUrl }}">
          <span class="mr-2 text-dark">
            <i class="far fa-trash-alt"></i>
          </span>
          <span class="link-light-cyan text-underline mr-1">
            Xem thùng rác
          </span>
        </a>
        @if ($countTrash)
          <span class="badge badge-pill badge-danger rounded-circle icon-squad-2 p-0 flex-center">
            {{ $countTrash }}
          </span>
        @endif
      </div>
    @endif
  </div>

  <div class="d-flex align-items-center">
    <div class="count-item">Tổng cộng: {{ $lists->total() }} mục</div>
    @if ($lists)
      {{ $lists->render('Admin.Layouts.Pagination') }}
      {{-- {{ $lists->render('user.page.pagination') }} --}}
      @endif
  </div>
</div>

{{-- <div class="table-pagination">
  <div class="left"></div>
  <div class="right">
    {{ $vouchers->render('user.page.pagination') }}
  </div>
</div> --}}
{{-- <nav aria-label="Page navigation example">
  <ul class="pagination">
    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item"><a class="page-link" href="#">Next</a></li>
  </ul>
</nav> --}}
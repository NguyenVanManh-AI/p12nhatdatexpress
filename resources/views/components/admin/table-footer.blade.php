<form action="" class="js-table__action-form d-none" data-delete-url="{{ $deleteUrl }}"
  data-force-delete-url="{{ $forceDeleteUrl }}" data-restore-url="{{ $restoreUrl }}"
  data-update-url="{{ $updateUrl }}"
  data-duplicate-url="{{ $duplicateUrl }}"
  method="POST">
  @csrf
  <input type="hidden" name="ids">
</form>

<div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
  <div class="text-left d-flex align-items-center">
    <div class="manipulation d-flex mr-3">
      <a href="javascript:void(0);" class="mr-2">
        <img src="{{ asset('/system/image/manipulation.png') }}" class="js-go-to-top cursor-pointer py-1"
          title="Về đầu trang" alt="">
      </a>

      @if(!$hideAction && ($checkRole == 1 || key_exists(5, $checkRole) || key_exists(6, $checkRole) || key_exists(7, $checkRole)))
        <div class="btn-group">
          <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown" aria-expanded="false"
            data-flip="false" aria-haspopup="true">
            Thao tác
          </button>
            <div class="dropdown-menu">
              @if(isset($moreAction) && $moreAction)
                {{ $moreAction }}
              @endif

              @if ($updateUrl && ($checkRole == 1 || key_exists(2, $checkRole)))
                <a class="dropdown-item js-update-show-order" type="button" href="javascript:{}">
                  <span class="bg-orange px-1 mr-2 rounded d-inline-block">
                    <i class="fas fa-pencil-alt text-white"></i>
                  </span>
                  Cập nhật
                </a>
              @endif

              @if ($duplicateUrl && ($checkRole == 1 || key_exists(3, $checkRole)))
                <a class="dropdown-item js-duplicate-multiple" type="button" href="javascript:{}" title="Nhân bản">
                  <i class="far fa-copy bg-main text-white p-1 mr-2 rounded"></i> Nhân bản
                </a>
              @endif

              @if ($checkRole == 1 || key_exists(5, $checkRole))
                @if($deleteUrl)
                  <a class="dropdown-item js-delete-multiple" type="button" href="javascript:{}" title="Xóa">
                    <i class="fas fa-trash bg-red p-1 mr-2 rounded"></i> Xóa
                  </a>
                @endif
                @if ($forceDeleteUrl && ($checkRole == 1 || key_exists(7, $checkRole)))
                  <a class="dropdown-item js-force-delete-multiple" type="button" href="javascript:{}" title="Xóa hẳn">
                    <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"></i> Xóa hẳn
                  </a>
                @endif
              @endif
              @if ($restoreUrl && ($checkRole == 1 || key_exists(6, $checkRole)))
                <a class="dropdown-item js-restore-multiple" type="button" href="javascript:{}" title="Khôi phục">
                  <i class="fas fa-redo bg-success p-1 mr-2 rounded"></i> Khôi phục
                </a>
              @endif
            </div>
        </div>
      @endif
    </div>

    <div class="display d-flex align-items-center mr-3">
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

    @if ($viewTrashUrl && ($checkRole == 1 || key_exists(8, $checkRole)))
      <div class="mr-3">
        <div class="manipulation d-flex mr-2 pt-1">
          <a href="{{ $viewTrashUrl }}" class="flex-start text-dark fs-14">
            <span class="c-icon mr-1">
              <i class="far fa-trash-alt"></i>
            </span>
            <span class="mr-1">
              Xem thùng rác
            </span>
            @if($countTrash)
              <span class="badge badge-pill badge-danger rounded-circle icon-squad-2 p-0 flex-center">
                {{ $countTrash }}
              </span>
            @endif
          </a>
        </div>
      </div>
    @endif
  </div>
  <div class="d-flex align-items-center">
    <div class="count-item">Tổng cộng: {{ $lists->total() }} mục</div>
    @if ($lists)
      {{ $lists->render('Admin.Layouts.Pagination') }}
    @endif
  </div>
</div>

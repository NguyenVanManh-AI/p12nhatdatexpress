@extends('Admin.Layouts.Master')

@section('Title', 'Quản lý mẫu Mail')

@section('Content')
  <x-admin.breadcrumb active-label="Quản lý mẫu Mail" :parents="[
      [
          'label' => 'Admin',
          'route' => 'admin.thongke',
      ],
      [
          'label' => 'Cấu hình hệ thống',
      ],
  ]" />
  <section class="content">
    <div class="container-fluid">
      <x-admin.table-filter search-place-holder="Tiêu đề"
        delete-filter="{{ $check_role == 1 || key_exists(8, $check_role) ? true : false }}"
        add-route="{{ $check_role == 1 || key_exists(1, $check_role) ? route('admin.system.mail.new') : null }}" />

      <div class="table table-responsive table-bordered table-hover">
        <table class="table">
          <thead class="thead-main">
            <tr>
              <th class="text-center">
                <input type="checkbox" class="select-all checkbox" name="select-all" />
              </th>
              <th scope="col">Thứ tự</th>
              <th scope="col">ID</th>
              <th scope="col">Tiêu đề</th>
              <th scope="col">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <input type="hidden" name="action" id="action_list" value="">
            @foreach ($templates as $template)
              <tr>
                <td class=" active">
                  <input type="checkbox" class="select-item checkbox order{{ $template->id }}" name="select_item[]"
                    value="{{ $template->id }}" />

                </td>
                <td class="input_rounded">

                  <input type="number" class="show_order" name="order{{ $template->id }}"
                    value="{{ $template->show_order }}">
                  <input type="hidden" class="select-item checkbox" name="select_item_created[{{ $template->id }}]"
                    value="{{ \Crypt::encryptString($template->created_by) }}" />
                </td>
                <td class="">
                  @if ($template->template_action)
                    <span class="mail_code">{{ $template->template_action }}</span>
                  @endif
                </td>
                <td>{{ $template->template_title }}</td>
                <td>
                  <div class="flex-end">
                    @if (!$template->is_deleted)
                      @if ($check_role == 1 || key_exists(2, $check_role))
                        <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-sm btn-info mb-2 mr-2"
                          title="Chỉnh sửa">
                          <i class="fas fa-edit"></i>
                        </a>
                      @endif
                      @if ($check_role == 1 || key_exists(5, $check_role))
                        <form action="{{ route('admin.templates.delete-multiple', ['ids' => $template->id]) }}" class="d-inline-block" method="POST">
                          @csrf
                          <button type="button" class="btn btn-sm btn-danger mb-2 mr-2 submit-accept-alert"
                            data-action="xóa" title="Xóa">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      @endif
                    @else
                      @if ($check_role == 1 || key_exists(5, $check_role))
                        <form action="{{ route('admin.templates.force-delete-multiple', ['ids' => $template->id]) }}" class="d-inline-block" method="POST">
                          @csrf
                          <button type="button" class="btn btn-sm btn-danger mb-2 mr-2 submit-accept-alert"
                            data-action="xóa hẳn" title="Xóa hẳn">
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </form>
                      @endif
                      @if ($check_role == 1 || key_exists(6, $check_role))
                        <form action="{{ route('admin.templates.restore-multiple', ['ids' => $template->id]) }}" class="d-inline-block"
                          method="POST">
                          @csrf
                          <button type="button" class="btn btn-sm btn-success mb-2 mr-2 submit-accept-alert"
                            data-action="khôi phục" title="Khôi phục">
                            <i class="fas fa-redo"></i>
                          </button>
                        </form>
                      @endif
                    @endif
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- maybe use 1 form with dynamic action --}}
      <form action="{{ route('admin.templates.delete-multiple') }}" class="delete-item-form d-none" method="POST">
        @csrf
        <input type="hidden" name="ids">
      </form>

      <form action="{{ route('admin.templates.force-delete-multiple') }}" class="force-delete-item-form d-none" method="POST">
        @csrf
        <input type="hidden" name="ids">
      </form>

      <form action="{{ route('admin.templates.restore-multiple') }}" class="restore-item-form d-none" method="POST">
        @csrf
        <input type="hidden" name="ids">
      </form>
      {{-- end maybe use 1 form --}}

      <!-- /Main row -->
      <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
        <div class="text-left d-flex align-items-center">
          <div class="manipulation d-flex mr-4">
            <a href="javascript:void(0);" class="mr-2">
              <img src="{{ asset('/system/image/manipulation.png') }}" class="js-go-to-top cursor-pointer py-1"
                title="Về đầu trang" alt="">
            </a>

            <div class="btn-group ml-1">
              <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown"
                aria-expanded="false" data-flip="false" aria-haspopup="true">
                Thao tác
              </button>
              @if($check_role == 1 || key_exists(5, $check_role) || key_exists(6, $check_role))
                <div class="dropdown-menu">
                  @if($check_role == 1 || key_exists(5, $check_role))
                    <a class="dropdown-item js-delete-multiple" type="button" href="javascript:{}">
                      <i class="fas fa-trash bg-red p-1 mr-2 rounded"></i> Xóa
                    </a>
                    @if($check_role == 1)
                      <a class="dropdown-item js-force-delete-multiple" type="button" href="javascript:{}">
                        <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"></i> Xóa hẳn
                      </a>
                    @endif
                  @endif
                  @if($check_role == 1 || key_exists(6, $check_role))
                    <a class="dropdown-item js-restore-multiple" type="button" href="javascript:{}">
                      <i class="fas fa-redo bg-success p-1 mr-2 rounded"></i> Khôi phục
                    </a>
                  @endif
                </div>
              @endif
            </div>
          </div>

          <div class="display d-flex align-items-center mr-4">
            <span class="mr-2">Hiển thị:</span>
            <form method="get" id="paginateform" action="{{ route('admin.classified.list') }}">
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
        </div>
        <div class="d-flex align-items-center">
          <div class="count-item">Tổng cộng: {{ $templates->total() }} mục</div>
          @if ($templates)
            {{ $templates->render('Admin.Layouts.Pagination') }}
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection

@section('Script')
  <script src="{{ asset('system/js/table.js') }}" type="text/javascript"></script>

  <script type="text/javascript">
    $('.submit-upgrade').click(function(event) {
      event.preventDefault();
      Swal.fire({
        title: 'Xác nhận thao tác',
        text: "Nhấn đồng ý thì sẽ tiến hành thao tác!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Quay lại',
        confirmButtonText: 'Đồng ý'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#upgrade-classified-form').attr('action', $(event.target).attr('href'))
          $('#upgrade-classified-form').submit()
        } else {
          return false;
        }
      });
    });

    function submitPaginate(event) {
      const uri = window.location.toString();
      const exist = uri.indexOf('?')
      const existItems = uri.indexOf('?items')
      const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
      exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() :
        window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
    }

    (() => {
      function restoreItem(selectedArray) {
        if (!selectedArray) return
        Swal.fire({
          title: 'Xác nhận khôi phục!',
          text: `Sau khi khôi phục sẽ được chuyển sang danh sách`,
          icon: 'success',
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
            $('.restore-item-form input[name="ids"]').val(ids)
            $('.restore-item-form').trigger('submit')
          }
        })
      }

      function deleteItem(selectedArray) {
        if (!selectedArray) return
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
        })
      }

      function forceDeleteItem(selectedArray) {
        if (!selectedArray) return
        Swal.fire({
          title: 'Xác nhận xóa hẳn',
          text: "Sau khi xóa sẽ không thể khôi phục",
          icon: 'error',
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
            $('.force-delete-item-form input[name="ids"]').val(ids)
            $('.force-delete-item-form').trigger('submit')
          }
        })
      }

      $(document).ready(function() {
        $('.js-delete-multiple').click(function(e) {
          e.preventDefault()
          const selectedArray = getSelected();

          deleteItem(selectedArray)
        })

        $('.js-force-delete-multiple').click(function(e) {
          e.preventDefault()
          const selectedArray = getSelected();

          forceDeleteItem(selectedArray)
        })

        $('.js-restore-multiple').click(function(e) {
          e.preventDefault()
          const selectedArray = getSelected();

          restoreItem(selectedArray)
        })
      })
    })()
  </script>
@endsection

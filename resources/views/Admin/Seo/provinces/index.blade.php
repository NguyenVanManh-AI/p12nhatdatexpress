@extends('Admin.Layouts.Master')

@section('Title', 'SEO vị trí | Quản lý SEO')

@section('Content')
  <x-admin.breadcrumb active-label="SEO vị trí" :parents="[
      [
        'label' => 'Admin',
        'route' => 'admin.thongke',
      ],
      [
        'label' => 'Quản lý SEO',
      ]
    ]"
  />

  <section class="content">
    <div class="container-fluid">
      <x-admin.table-filter search-place-holder="Vị trí, tiêu đề, từ khóa, mô tả..."/>

      <div class="table table-responsive table-bordered table-hover">
        <table class="table">
          <thead class="thead-main">
            <tr>
              <th style="width:5%">#</th>
              {{-- <th class="text-center">
                <input type="checkbox" class="select-all checkbox" name="select-all" />
              </th> --}}
              <th scope="col" style="width:15%">Vị trí</th>
              <th scope="col" style="width:15%">Từ khóa</th>
              <th scope="col" style="width:20%">Tiêu đề</th>
              <th scope="col" style="width:30%">Mô tả</th>
              <th scope="col" style="width:15%">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <input type="hidden" name="action" id="action_list" value="">
            @foreach ($provinces as $province)
              <tr>
                <td>
                  {{ ($provinces->currentpage()-1) * $provinces->perpage() + $loop->index + 1 }}
                </td>
                {{-- <td class=" active">
                  <input type="checkbox" class="select-item checkbox order{{ $template->id }}" name="select_item[]"
                    value="{{ $template->id }}" />
                </td> --}}
                <td>{{ $province->province_name }}</td>
                <td>{{ data_get($province, 'seo.meta_key') }}</td>
                <td>{{ data_get($province, 'seo.meta_title') }}</td>
                <td>{{ data_get($province, 'seo.meta_description') }}</td>
                <td>
                  <div class="flex-end">
                    @if ($check_role == 1 || key_exists(2, $check_role))
                      <a href="{{ route('admin.seo.provinces.edit', $province) }}" class="btn btn-sm btn-info mb-2 mr-2" title="Chỉnh sửa">
                        <i class="fas fa-edit"></i>
                      </a>
                    @endif
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- <form action="{{ route('admin.templates.delete-multiple') }}" class="delete-item-form d-none" method="POST">
        @csrf
        <input type="hidden" name="ids">
      </form> --}}

      <!-- /Main row -->
      <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
        <div class="text-left d-flex align-items-center">
          <div class="manipulation d-flex mr-4">
            <a href="javascript:void(0);" class="mr-2">
              <img src="{{ asset('/system/image/manipulation.png') }}" class="js-go-to-top cursor-pointer py-1"
                title="Về đầu trang" alt="">
            </a>

            {{-- <div class="btn-group ml-1">
              <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown"
                aria-expanded="false" data-flip="false" aria-haspopup="true">
                Thao tác
              </button>
              @if ($check_role == 1 || key_exists(5, $check_role) || key_exists(6, $check_role))
                <div class="dropdown-menu">
                  @if ($check_role == 1 || key_exists(5, $check_role))
                    <a class="dropdown-item js-delete-multiple" type="button" href="javascript:{}">
                      <i class="fas fa-trash bg-red p-1 mr-2 rounded"></i> Xóa
                    </a>
                    @if ($check_role == 1)
                      <a class="dropdown-item js-force-delete-multiple" type="button" href="javascript:{}">
                        <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"></i> Xóa hẳn
                      </a>
                    @endif
                  @endif
                  @if ($check_role == 1 || key_exists(6, $check_role))
                    <a class="dropdown-item js-restore-multiple" type="button" href="javascript:{}">
                      <i class="fas fa-redo bg-success p-1 mr-2 rounded"></i> Khôi phục
                    </a>
                  @endif
                </div>
              @endif
            </div> --}}
          </div>

          <div class="display d-flex align-items-center mr-4">
            <span class="mr-2">Hiển thị:</span>
            <form method="get" id="paginateform" action="{{ route('admin.seo.provinces.index') }}">
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
          {{-- @if ($check_role == 1 || key_exists(8, $check_role))
          <div class="view-trash">
            <a href="{{ route('admin.classified.listtrash') }}" class=" text-primary"><i
                class="text-primary far fa-trash-alt"></i>
              Xem thùng rác</a>
            <span class="count-trash">{{ $trash_count }}</span>
          </div>
        @endif --}}
        </div>
        <div class="d-flex align-items-center">
          <div class="count-item">Tổng cộng: {{ $provinces->total() }} mục</div>
          @if ($provinces)
            {{ $provinces->render('Admin.Layouts.Pagination') }}
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

@section('Content')
  <section class="content hiden-scroll">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

          <h4 class="text-center text-bold mt-4">SEO CHUYÊN MỤC TIÊU ĐIỂM</h4>
          <div class="table-responsive mt-2">
            <form action="" id="formtrash" method="post">
              @csrf
              <input type="hidden" name="action" id="action_list" value="">
              <table class="table table-bordered text-center table-custom" id="table">
                <thead>
                  <tr>
                    {{-- <th scope="row" class=" active" style="width: 3%">
                                        <input type="checkbox" class="select-all checkbox" name="select-all"/>
                                    </th> --}}
                    <th scope="col" style="width:15%">Vị trí</th>
                    <th scope="col" style="width:15%"> Tiêu đề</th>
                    <th scope="col" style="width: 10%">Từ khóa</th>
                    {{--                                <th scope="col" style="width: 14%">Thứ tự</th> --}}
                    <th scope="col" style="width: 20%">Mô tả</th>
                    <th scope="col" style="width: 20%">Đường dẫn</th>
                    <th scope="col" style="width: 13%">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($provinces as $province)
                    <tr>
                      <td> {{ $province->province_name }}</td>
                      <td>{{ data_get($province, 'seo.meta_title') }}</td>
                      <td>{{ data_get($province, 'seo.meta_key') }}</td>
                      <td>{{ data_get($province, 'seo.meta_desc') }}</td>
                      <td>
                        <div>
                          @if ($check_role == 1 || key_exists(2, $check_role))
                            <div class="float-left ml-2">
                              <i class="fas fa-cog mr-2"></i>
                              <a href="{{ route('admin.seo.editfocus', $province->id) }}" class="text-primary ">Chỉnh
                                sửa</a>
                            </div>
                          @endif

                          <div class="clear-both"></div>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                  @if ($provinces->count() == 0)
                    <tr>
                      <td colspan="5">
                        <p class="text-center mt-2">Không có dữ liệu</p>
                      </td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </form>
          </div>
          <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
            <div class="text-left d-flex align-items-center">


              <div class="display d-flex align-items-center mr-4">
                <span>Hiển thị:</span>
                <form method="get" id="paginateform" action="">
                  <select class="custom-select" id="paginateNumber" name="items">
                    <option @if (isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">
                      10
                    </option>
                    <option @if (isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif value="20">
                      20
                    </option>
                    <option @if (isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif value="30">
                      30
                    </option>
                  </select>
                </form>
              </div>


            </div>
            <div class="d-flex align-items-center">
              <div class="count-item">Tổng cộng: {{ $provinces->total() }} items</div>
              @if ($provinces)
                {{ $provinces->render('Admin.Layouts.Pagination') }}
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
@endsection

@section('Script')
  <script type="text/javascript">
    $('#tieudiem').addClass('active');
    $('#quanlydanhmuc').addClass('active');
    $('#nav-tieudiem').addClass('menu-is-opening menu-open');
  </script>
  <script>
    $('#paginateNumber').change(function(e) {
      $('#paginateform').submit();
    });
    $('.trash').click(function() {
      created_by = $(this).data('created_by');
      id = $(this).data('id');
      Swal.fire({
        title: 'Chuyển danh mục vào thùng rác',
        text: "Nhấn đồng ý để chuyển danh mục vào thùng rác !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Quay lại',
        confirmButtonText: 'Đồng ý'
      }).then((result) => {
        if (result.isConfirmed) {
          // window.location.href = "/admin/classified-group/trash-item/" + id + "/" + created_by;
        }
      })
    });
    $('.moveToTrash').click(function() {
      var id = $(this).data('id');
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
          $('#action_list').val("trash");
          $('#formtrash').submit();
        }
      });
    });
  </script>

  <script src="js/table.js"></script>
@endsection

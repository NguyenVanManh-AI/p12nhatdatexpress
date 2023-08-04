@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý trang tĩnh | Trang tĩnh')

@section('Content')
  <x-admin.breadcrumb active-label="Quản lý trang tĩnh" :parents="[
      [
          'label' => 'Admin',
          'route' => 'admin.thongke',
      ],
      [
          'label' => 'Trang tĩnh',
      ],
  ]" />

  <section class="content">
    <div class="container-fluid">
      <x-admin.table-filter
        search-place-holder="Từ khóa..."
        add-route="{{ route('admin.static.page.add') }}"
        date-range-filter
      />

      <div class="table table-responsive table-vertical table-hover">
        <table class="table table-bordered">
          <thead class="thead-main">
            <tr>
              <th scope="row" class="active">
                <input type="checkbox" class="select-all checkbox" name="select-all" />
              </th>
              <th scope="col">STT</th>
              <th scope="col" width="10%">Thứ tự</th>
              <th scope="col" width="15%">Hình ảnh</th>
              <th scope="col" width="30%">Tiêu đề</th>
              <th scope="col">Thông tin</th>
              <th scope="col">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @forelse($staticPages as $item)
              <tr>
                <td class=" active">
                  <input type="checkbox" value="{{ $item->id }}" data-name="{{ $item->page_title }}"
                    class="select-item checkbox" name="select_item[]" />
                  <input type="text" hidden name="select_item_created[{{ $item->id }}]"
                    value="{{ \Crypt::encryptString($item->created_by) }}">
                </td>
                <td class="text-center">
                  {{ ($staticPages->currentPage() - 1) * $staticPages->perPage() + $loop->index + 1 }}
                </td>
                <td class="input_rounded">
                  <input type="text" value="{{ $item->show_order }}" name="show_order[{{ $item->id }}]">
                </td>
                <td>
                  @if ($item->image_url)
                    <a data-fancybox="image_{{ $item->id }}"
                      data-src="{{ strpos($item->image_url, 'http') === 0 ? $item->image_url : asset($item->image_url) }}">
                      <img
                        src="{{ strpos($item->image_url, 'http') === 0 ? $item->image_url : asset($item->image_url) }}"
                        width="100%" style="object-fit: cover" />
                    </a>
                  @endif
                </td>
                <td class="title_role">
                  <p class="text-wrap" style="word-break: break-word">{{ $item->page_title }}</p>
                  {{-- <p><b>Nhóm:</b> {{ $item->group_title }}</p> --}}
                </td>
                <td>
                  <p><span class="text-gray mr-2">Ngày tạo:</span>{{ date('d/m/Y H:i:s', $item->created_at) }}
                  </p>
                  <p>
                    <span class="text-gray mr-2">Cập nhật:</span>
                    {{ date('d/m/Y H:i:s', $item->updated_at ?? $item->created_at) }}
                  </p>
                  {{-- <div>
                    <label class="mr-2">
                      <input class="checkboxItem" id="is_highlight_{{ $item->id }}" type="checkbox" value="1"
                        name="is_highlight[{{ $item->id }}]"
                        {{ old('is_highlight') == 1 || $item->is_highlight == 1 ? 'checked' : '' }}>
                    </label>
                    <label for="is_highlpight_{{ $item->id }}">Nổi bật</label>
                  </div> --}}
                </td>
                <td class="text-left">
                  <div class="flex-column">
                    <x-admin.action-button
                      action="update"
                      :check-role="$check_role"
                      :item="$item"
                      url="{{ route('admin.static.page.edit', $item->id) }}"
                    />
                    <x-admin.action-button
                      action="duplicate"
                      :check-role="$check_role"
                      :item="$item"
                      url="{{ route('admin.static.page.duplicate-multiple', ['ids' => $item->id]) }}"
                    />
                    <x-admin.action-button
                      action="delete"
                      :check-role="$check_role"
                      :item="$item"
                      url="{{ route('admin.static.page.delete-multiple', ['ids' => $item->id]) }}"
                    />
                  </div>
                </td>
              </tr>
            @empty
              <td colspan="7">Chưa có dữ liệu</td>
            @endforelse
          </tbody>
        </table>
      </div>

      <x-admin.table-footer
        :check-role="$check_role"
        :lists="$staticPages"
        count-trash="{{ $countTrash }}"
        view-trash-url="{{ route('admin.static.page.trash') }}"
        delete-url="{{ route('admin.static.page.delete-multiple') }}"
        duplicate-url="{{ route('admin.static.page.duplicate-multiple') }}"
        update-url="{{ route('admin.static.page.update-order-multiple') }}"
      />
    </div>
  </section>
@endsection

@section('Script')
  <script src="js/table.js" type="text/javascript"></script>
  <script>
    function duplicateItem(id, created) {
      Swal.fire({
        title: 'Xác nhận nhân bản',
        text: "Sau khi nhân bản sẽ có thêm 1 bản ghi tương tự!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Quay lại',
        confirmButtonText: 'Đồng ý'
      }).then((result) => {
        if (result.isConfirmed) {
        }
      })
    }
    // duplicate action
    $('.dropdown-item.duplicateItem').click(function() {
      const selectedArray = getSelected();
      Swal.fire({
        title: 'Xác nhận nhân bản',
        text: "Sau khi nhân bản sẽ có thêm 1 bản ghi tương tự!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Quay lại',
        confirmButtonText: 'Đồng ý'
      }).then((result) => {
        if (result.isConfirmed) {
          if (selectedArray)
            $('#formAction').attr('action', $('#formAction').attr('action') + '?action=duplicate').submit();
        }
      });
    })
  </script>
@endsection

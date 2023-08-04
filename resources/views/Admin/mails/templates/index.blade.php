@extends('Admin.Layouts.Master')

@section('Title', 'Quản lý mẫu Mail | Cấu hình hệ thống')

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
        add-route="{{ $check_role == 1 || key_exists(1, $check_role) ? route('admin.templates.create') : null }}" />

      <div class="table table-responsive table-vertical table-hover">
        <table
          class="table table-bordered js-table__has-multiple-action"
        >
          <thead class="thead-main text-center">
            <tr>
              <th class="text-center">
                <input type="checkbox" class="select-all checkbox" name="select-all" />
              </th>
              <th scope="col">Thứ tự</th>
              <th scope="col">Action</th>
              <th scope="col">Tiêu đề</th>
              <th scope="col" class="w-300px">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <input type="hidden" name="action" id="action_list" value="">
            @forelse ($templates as $item)
              <tr>
                <td class=" active">
                  <input type="checkbox" class="select-item checkbox order{{ $item->id }}" name="select_item[]"
                    value="{{ $item->id }}" />
                </td>
                <td class="input_rounded">
                  <input type="text" class="show_order" name="show_order[{{ $item->id }}]"
                    value="{{ $item->show_order }}">
                  <input type="hidden" class="select-item checkbox" name="select_item_created[{{ $item->id }}]"
                    value="{{ \Crypt::encryptString($item->created_by) }}" />
                </td>
                <td class="">
                  @if ($item->template_action)
                    <span class="mail_code">{{ $item->template_action }}</span>
                  @endif
                </td>
                <td>{{ $item->template_title }}</td>
                <td>
                  <div class="flex-column">
                    <x-admin.action-button
                      action="update"
                      :check-role="$check_role"
                      :item="$item"
                      url="{{ route('admin.templates.edit', $item) }}"
                    />
                    <x-admin.action-button
                      action="delete"
                      :check-role="$check_role"
                      :item="$item"
                      url="{{ route('admin.templates.delete-multiple', ['ids' => $item->id]) }}"
                    />
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td class="text-center" colspan="5">
                  Chưa có dữ liệu
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <x-admin.table-footer
        :check-role="$check_role"
        :lists="$templates"
        count-trash="{{ $countTrash }}"
        view-trash-url="{{ route('admin.templates.trash') }}"
        delete-url="{{ route('admin.templates.delete-multiple') }}"
        update-url="{{ route('admin.templates.update-order-multiple') }}"
      />
  </section>
@endsection

@section('Script')
  <script src="{{ asset('system/js/table.js') }}" type="text/javascript"></script>
@endsection

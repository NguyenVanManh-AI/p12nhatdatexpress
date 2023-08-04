@extends('Admin.Layouts.Master')

@section('Title', 'Quản lý thùng rác mẫu Mail | Cấu hình hệ thống')

@section('Content')
  <x-admin.breadcrumb active-label="Thùng rác" :parents="[
      [
          'label' => 'Admin',
          'route' => 'admin.thongke',
      ],
      [
          'label' => 'Cấu hình hệ thống',
      ],
      [
          'label' => 'Quản lý mẫu Mail',
          'route' => 'admin.templates.index',
      ], 
  ]" />
  <section class="content">
    <div class="container-fluid">
      <x-admin.table-filter
        search-place-holder="Tiêu đề..."
      />

      <div class="table table-responsive table-vertical table-hover">
        <table
          class="table table-bordered js-table__has-multiple-action"
        >
          <thead class="thead-main text-center">
            <tr>
              <th class="w-70px">
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
                <td class="text-center active">
                  <input type="checkbox" class="select-item checkbox order{{ $item->id }}" name="select_item[]"
                    value="{{ $item->id }}" />
                </td>
                <td class="text-center">
                  {{ $item->show_order }}
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
                      action="restore"
                      :check-role="$check_role"
                      :item="$item"
                      url="{{ route('admin.templates.restore-multiple', ['ids' => $item->id]) }}"
                    />
                    <x-admin.action-button
                      action="force-delete"
                      :check-role="$check_role"
                      :item="$item"
                      url="{{ route('admin.templates.force-delete-multiple', ['ids' => $item->id]) }}"
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
        force-delete-url="{{ route('admin.templates.force-delete-multiple') }}"
        restore-url="{{ route('admin.templates.restore-multiple') }}"
      />
    </div>
  </section>
@endsection

@section('Script')
  <script src="{{ asset('system/js/table.js') }}" type="text/javascript"></script>
@endsection

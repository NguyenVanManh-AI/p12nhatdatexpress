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
                  <div class="flex-column">
                    <x-admin.action-button
                      action="update"
                      :check-role="$check_role"
                      :item="$province"
                      url="{{ route('admin.seo.provinces.edit', $province) }}"
                    />
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <x-admin.table-footer
        :check-role="$check_role"
        :lists="$provinces"
        hide-action
      />
    </div>
  </section>
@endsection

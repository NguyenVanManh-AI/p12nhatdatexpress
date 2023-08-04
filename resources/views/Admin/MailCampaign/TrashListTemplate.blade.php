@extends('Admin.Layouts.Master')

@section('Title', 'Thùng rác | Chiến dịch email')

@section('Style')
  <link rel="stylesheet" type="text/css" href="{{ asset('system/css/admin-project.css') }}">
@endsection

@section('Content')
  <div class="row m-0 px-3 pt-3">

    <ol class="breadcrumb mt-1">
      <li class="recye px-2 pt-1 check">
        <a href="{{ route('admin.email-campaign.list-template') }}">
          <i class="fa fa-th-list mr-1"></i>Danh sách
        </a>
      </li>
    </ol>
  </div>
  <h4 class="text-center font-weight-bold">THÙNG RÁC MẪU EMAIL</h4>
  <section class="content hiden-scroll">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="table-responsive">
            <table class="table table-bordered text-center table-custom" id="table" style="min-width: 1050px">
              <thead>
                <tr>
                  <th scope="row" class="active" width="3%">
                    {{-- chọn tất cả --}}
                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                  </th>
                  <th scope="col" width="3%">ID</th>
                  <th scope="col" width="30%">Tiêu đề</th>
                  <th scope="col" width="12%">Ngày tạo</th>
                  <th scope="col" width="10%">Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @forelse($list as $item)
                  <tr>
                    <td class="active">
                      <input type="checkbox" class="select-item" value="{{ $item->id }}" name="select_item[]">
                      <input type="hidden" class="select-item" value="{{ \Crypt::encryptString($item->created_by) }}"
                        name="select_item_created[{{ $item->id }}]">
                    </td>
                    <td>{{ $item->id }}</td>
                    <td>
                      {{ $item->template_title }}
                    </td>
                    <td>
                      {{ date('d/m/Y H:i', $item->created_at) }}
                    </td>
                    <td>
                      <div class="flex-column">
                        <x-admin.restore-button
                          :check-role="$check_role"
                          url="{{ route('admin.mail-campaign.templates.restore-multiple', ['ids' => $item->id]) }}"
                        />
      
                        <x-admin.force-delete-button
                          :check-role="$check_role"
                          url="{{ route('admin.mail-campaign.templates.force-delete-multiple', ['ids' => $item->id]) }}"
                        />
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9">Chưa có dữ liệu</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <x-admin.table-footer
            :check-role="$check_role"
            :lists="$list"
            force-delete-url="{{ route('admin.mail-campaign.templates.force-delete-multiple') }}"
            restore-url="{{ route('admin.mail-campaign.templates.restore-multiple') }}"
          />
        </div>
      </div>
    </div>
  </section>
@endsection

@section('Script')
  <script src="js/table.js"></script>
@endsection

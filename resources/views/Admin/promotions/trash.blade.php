@extends('Admin.Layouts.Master')

@section('Title', 'Danh sách thùng rác mã khuyến mãi | Mã khuyến mãi')

@section('Content')
  <x-admin.breadcrumb active-label="Quản lý thùng rác mã khuyến mãi" :parents="[
      [
          'label' => 'Admin',
          'route' => 'admin.thongke',
      ],
      [
          'label' => 'Mã khuyến mãi',
          'route' => 'admin.promotion.index',
      ],
  ]" />

  <section class="content">
    <div class="container-fluid">
      <x-admin.table-filter
        search-place-holder="Nhập mã..."
        date-range-filter
      />

      <div class="row">
        <div class="col-12">
          <div class="table-responsive">
            <table class="table table-bordered text-center table-custom" id="table" style="min-width: 1050px">
              <thead>
                <tr class="contact-table">
                  <th scope="col" style="width: 3%"><input type="checkbox" class="select-all"></th>
                  <th scope="col" style="width: 6%">STT</th>
                  <th scope="col" style="width: 6%">Mã</th>
                  <th scope="col" style="width: 13%">Đã dùng/Tổng số</th>
                  <th scope="col" style="width: 6%">Giá trị</th>
                  <th scope="col" style="width: 12%">Quyền sử dụng</th>
                  <th scope="col" style="width: 23%">Thông tin</th>
                  <th scope="col" style="width: 8%">Loại</th>
                  <th scope="col" style="width: 12%">Cài đặt</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($promotions as $item)
                  <tr>
                    <td class="active">
                      <input type="checkbox" class="select-item" value="{{ $item->id }}" name="select_item[]">
                      <input type="hidden" class="select-item" value="{{ \Crypt::encryptString($item->created_by) }}"
                        name="select_item_created[{{ $item->id }}]">
                    </td>
                    <td class="font-weight-bold">{{ $item->id }}</td>
                    <td class="font-weight-bold">{{ $item->promotion_code }}</td>
                    <td>
                      <span class="font-weight-bold">{{ $item->used }}/<span
                          class="text-danger">{{ $item->num_use }}</span></span>
                    </td>
                    <td>
                      @if ($item->promotion_unit == 0)
                        {{ $item->value }}%
                      @else
                        {{ $item->value }}đ
                      @endif
                    </td>
                    <td>
                      @if ($item->is_all == 1)
                        <span>Tất cả</span>
                      @elseif($item->is_private == 1)
                        <span>Nhận trên trang</span>
                      @else
                        <span>Người dùng</span>
                        <span>[ID: {{ $item->user_id_use }}]</span>
                      @endif
                    </td>
                    <td class="text-left">
                      <span class="font-weight-bold">Ngày tạo:
                      </span><span>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y h:i') }}</span><br>
                      <span class="font-weight-bold">Ngày bắt đầu:
                      </span><span>{{ \Carbon\Carbon::parse($item->date_from)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y h:i') }}</span><br>
                      <span class="font-weight-bold">Ngày kết thúc:
                      </span><span>{{ \Carbon\Carbon::parse($item->date_to)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y h:i') }}</span>
                    </td>
                    <td>
                      @if ($item->promotion_type == 1)
                        Nạp coin
                      @else
                        Mua gói
                      @endif
                    </td>
                    <td>
                      <div class="flex-column">
                        <x-admin.action-button
                          action="restore"
                          :check-role="$check_role"
                          :item="$item"
                          url="{{ route('admin.promotion.restore-multiple', ['ids' => $item->id]) }}"
                        />
                        <x-admin.action-button
                          action="force-delete"
                          :check-role="$check_role"
                          :item="$item"
                          url="{{ route('admin.promotion.force-delete-multiple', ['ids' => $item->id]) }}"
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
            :lists="$promotions"
            force-delete-url="{{ route('admin.promotion.force-delete-multiple') }}"
            restore-url="{{ route('admin.promotion.restore-multiple') }}"
          />
        </div>
      </div>
    </div>
  </section>
@endsection

@section('Script')
  <script src="js/table.js"></script>
@endsection

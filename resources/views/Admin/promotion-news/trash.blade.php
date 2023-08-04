@extends('Admin.Layouts.Master')

@section('Title', 'Thùng rác bài viết khuyến mãi | Mã khuyến mãi')

@section('Content')
  <x-admin.breadcrumb active-label="Thùng rác bài viết khuyến mãi" :parents="[
    [
        'label' => 'Admin',
        'route' => 'admin.thongke',
    ],
    [
        'label' => 'Bài viết khuyến mãi',
        'route' => 'admin.promotion-news.index',
    ],
  ]" />

  <section class="content">
    <div class="container-fluid">
      <x-admin.table-filter search-place-holder="Nhập mã..."
        date-range-filter />

      <div class="row">
        <div class="col-12">
          <div class="table table-responsive table-vertical table-hover">
            <table class="table table-bordered">
              <thead class="thead-main">
                <tr>
                  <th scope="col" style="width: 3%"><input type="checkbox" class="select-all"></th>
                  <th scope="col" style="width: 4%">ID</th>
                  <th scope="col" style="width: 12%">Hình đại diện</th>
                  <th scope="col" style="width: 18%">Tiêu đề</th>
                  <th scope="col" style="width: 11%">Mã đính kèm</th>
                  <th scope="col" style="width: 10%">Loại</th>
                  <th scope="col" style="width: 25%">Hạn dùng</th>
                  <th scope="col" style="width: 13%">Cài đặt</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($news as $item)
                  <tr>
                    <td class="active">
                      <input type="checkbox" class="select-item" value="{{ $item->id }}" name="select_item[]">
                      <input type="hidden" class="select-item" value="{{ \Crypt::encryptString($item->created_by) }}"
                        name="select_item_created[{{ $item->id }}]">
                    </td>
                    <td class="font-weight-bold">{{ $item->id }}</td>
                    <td class="font-weight-bold">
                      <img src="{{ $item->getImageUrl() }}" class="object-cover mh-120px mw-120px" alt="">
                    </td>
                    <td>
                      <h5 class="text-center text-main fs-normal">
                        {{ $item->news_title }}
                      </h5>
                      <div>
                        <div class="flex-start">
                          <span class="mr-2 icon-small-size">
                            <i class="fas fa-edit"></i>
                          </span>
                          <span class="mr-1">
                            Ngày viết:
                          </span>
                          <span class="text-light-cyan">
                            {{ formatFromTimestamp($item->created_at, 'd/m/Y') }}
                          </span>
                        </div>
                        <div class="flex-start">
                          <span class="mr-2 icon-small-size">
                            <i class="fas fa-user"></i>
                          </span>
                          <span class="mr-1">
                            Tác giả:
                          </span>
                          <span class="text-light-cyan">
                            {{ $item->createdBy ? $item->createdBy->getFullName() : '' }}
                          </span>
                        </div>
                      </div>
                    </td>

                    <td class="font-weight-bold">
                      @if ($item->promotion)
                        {{ data_get($item->promotion, 'promotion_code') }}
                        <br>
                        <span class="font-weight-bold">Đã nhận {{ data_get($item->promotion, 'used') }}/<span
                            class="text-danger">{{ data_get($item->promotion, 'num_use') }}</span></span>
                      @else
                        Không đính kèm
                      @endif
                    </td>
                    <td>
                      @if ($item->promotion)
                        {{ data_get($item->promotion, 'promotion_type') == 0 ? 'Mua gói giảm' : 'Nạp tiền tặng' }} {{ data_get($item->promotion, 'value') }}%
                      @else
                        Không đính kèm
                      @endif
                    </td>
                    <td class="text-left">
                      @if ($item->promotion)
                        <span class="font-weight-bold">Ngày bắt đầu:
                        </span>
                        <span>
                          {{ formatFromTimestamp(data_get($item->promotion, 'date_from')) }}
                        </span><br>
                        <span class="font-weight-bold">Ngày kết thúc:
                        </span>
                        <span>
                          {{ formatFromTimestamp(data_get($item->promotion, 'date_to')) }}
                        </span>
                      @else
                        Không đính kèm mã
                      @endif
                    </td>
                    <td>
                      <div class="flex-column">
                        <x-admin.action-button
                          action="restore"
                          :check-role="$check_role"
                          :item="$item"
                          url="{{ route('admin.promotion-news.restore-multiple', ['ids' => $item->id]) }}"
                        />
                        <x-admin.action-button
                          action="force-delete"
                          :check-role="$check_role"
                          :item="$item"
                          url="{{ route('admin.promotion-news.force-delete-multiple', ['ids' => $item->id]) }}"
                        />
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <x-admin.table-footer :check-role="$check_role" :lists="$news"
            force-delete-url="{{ route('admin.promotion-news.force-delete-multiple') }}"
            restore-url="{{ route('admin.promotion-news.restore-multiple') }}"
          />
        </div>
      </div>
    </div>
  </section>
@endsection

@section('Script')
  <script src="js/table.js"></script>
@endsection

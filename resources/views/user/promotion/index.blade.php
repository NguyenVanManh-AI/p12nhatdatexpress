@extends('user.layouts.master')
@section('content')
  <div class="p-3">
    <div class="table-scroll p-0">
      <table class="list-code-offer">
        <thead>
          <tr>
            <th>STT</th>
            <th>Mã</th>
            <th>Giá trị</th>
            <th>Loại </th>
            <th>Hạn sử dụng</th>
            <th>Tình trạng</th>
            <th>Tùy chọn</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($vouchers as $item)
            <tr class="type-code">
              <td class="text-center">
                {{ ($vouchers->currentPage() - 1) * $vouchers->perPage() + $loop->index + 1 }}
              </td>
              <td class="text-main">
                {{ data_get($item->promotion, 'promotion_code') }}
              </td>
              <td>
                {{ $item->getPromotionTypePercent() }}
              </td>
              <td class="{{ data_get($item->promotion, 'promotion_type') ? 'text-danger' : 'text-success' }}">
                {{ $item->getPromotionTypeTitle() }}
              </td>
              <td>
                {{ formatFromTimestamp(data_get($item->promotion, 'date_to')) }}
              </td>
              <td>
                @if ($item->getStatusLabel())
                  <span class="badge badge-{{ $item->getStatusClass() }}">
                    {{ $item->getStatusLabel() }}
                  </span>
                @endif
              </td>
              <td>
                @if ($item->canUse())
                  @if (data_get($item->promotion, 'promotion_type') == 1)
                    <a href="{{ route('user.deposit', $item->voucher_code) }}">
                      <i class="fas fa-check"></i>
                      Sử dụng mã
                    </a>
                  @endif
                @endif
              </td>
            </tr>
          @empty
            <td colspan="7">
              Không có dữ liệu
            </td>
          @endforelse
        </tbody>
      </table>
    </div>

    <x-common.table-footer
      :lists="$vouchers"
    />
  </div>
@endsection

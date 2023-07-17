@extends('user.layouts.master')
@section('title', 'Quản lý chiến dịch')
@section('content')
    <div class="transaction-history">
        <table class="table-introduce">
            <tbody>
            <tr class="title">
                <th>STT</th>
                <th>Mô hình</th>
                <th>Thiết bị</th>
                <th>Vị trí</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Phí giao dịch(coins)</th>
                <th>Trạng thái</th>
            </tr>
            @php $i = 1; @endphp
            @foreach($banners as $item)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$item->group_name}}</td>
                    @if($item->banner_type == 'D')
                        <td>Desktop</td>
                    @else
                        <td>Mobile</td>
                    @endif
                    <td>{{$item->banner_name}}</td>
                    <td>{{vn_date($item->date_from)}}</td>
                    <td>{{vn_date($item->date_to)}}</td>
                    <td>{{number_format($item->total_coin_amount)}}</td>
                    @if($item->is_confirm == 0)
                        <td class="text-warning">Chưa duyệt</td>
                    @elseif(($item->is_confirm == 1))
                        <td class="text-success">Đã duyệt</td>
                    @else
                        <td class="text-danger">Hủy</td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="table-pagination alpha">
        <div class="left"></div>
        <div class="right">
            {{ $banners->render('user.page.pagination') }}
        </div>
    </div>
@endsection

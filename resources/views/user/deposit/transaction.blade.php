@extends('user.layouts.master')
@section('title', 'Giao dịch')
@section('content')
    <div class="transaction-history mb-1">
        <table class="table-introduce">
            <tbody>
            <tr class="title">
                <th>STT</th>
                <th>Thời gian</th>
                <th>Mã giao dịch</th>
                <th>Hình thức</th>
                <th>Ghi chú</th>
                <th>Số tiền</th>
                <th>Tình trạng</th>
            </tr>
            @foreach($deposit_list as $deposit)
                <tr>
                    <td class="STT">{{$deposit->num}}</td>
                    <td>{{vn_date($deposit->deposit_time)}}</td>
                    <td>{{$deposit->deposit_code}}</td>
                    @if($deposit->deposit_type == 'I')
                        <td>Mua gói</td>
                    @elseif($deposit->deposit_type == 'S')
                        <td>Mua dịch vụ</td>
                    @elseif($deposit->deposit_type == 'B')
                        <td>Mua banner</td>
                    @elseif($deposit->deposit_type == 'C')
                        <td>Nạp coin</td>
                    @endif
                    <td>{{$deposit->deposit_note}}</td>
                    <td>{{number_format($deposit->deposit_amount)}}</td>
                    @if($deposit->deposit_status == 0)
                        <td class="text-secondary">Chưa xử lý</td>
                    @elseif($deposit->deposit_status == 1)
                        <td class="text-success ">Đã xác nhận </td>
                    @else($deposit->deposit_status == 2)
                        <td class="text-danger">Không xác nhận </td>
                    @endif

                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="table-pagination alpha">
            <div class="right">
                {{ $deposit_list->render('user.page.pagination') }}
            </div>
        </div>
    </div>
@endsection


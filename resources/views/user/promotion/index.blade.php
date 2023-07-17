@extends('user.layouts.master')
@section('content')
    <div class="table-scroll">
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
            @foreach($vouchers as $voucher)
                <tr class="type-code">
                    <td>{{$voucher->id}}</td>
                    <td class="code">{{$voucher->voucher_code}}</td>
                    @if($voucher->voucher_type == 0)
                        <td>-{{$voucher->voucher_percent}}%</td>
                        <td class="type-discount">Giảm giá trị thanh toán</td>
                    @else
                        <td>+{{$voucher->voucher_percent}}%</td>
                        <td class="type-donate">Tặng giá trị nạp</td>
                    @endif
                    <td>{{vn_date($voucher->end_date)}}</td>
                    @if($voucher->end_date >= time())
                        @if($voucher->amount > $voucher->amount_used)
                            <td class="not-used">Sử dụng</td>
                            <td>
                                <a href="{{$voucher->voucher_type==1?route('user.deposit', $voucher->voucher_code):'#'}}" class="use-code-1"><i class="fas fa-check"></i> Sử dụng mã</a>
                            </td>
                        @else
                            <td class="used">Đã sử dụng</td>
                            <td></td>
                        @endif
                    @else
                        <td class="expired">Hết hạn</td>
                        <td></td>
                    @endif

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="table-pagination">
        <div class="left"></div>
        <div class="right">
            {{ $vouchers->render('user.page.pagination') }}
        </div>

    </div>

@endsection

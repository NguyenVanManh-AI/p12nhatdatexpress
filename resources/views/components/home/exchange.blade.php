<div class="widget widget-height widget-currency c-mb-10">
    <div class="gold-price widget-height relative">
        <table>
            <tr class>
                <th class="bg-warning text-white c-w-90">Giá vàng</th>
                <th class="bg-darken text-white">{{ date('d/m/Y', strtotime(Carbon\Carbon::now())) }}</th>
                <th class="bg-darken text-white">ĐVT: 1,000 Đ</th>
            </tr>
            <tr class="h-36">
                <td class="bg-gray">SJC</td>
                <td>Mua {{$giavangsjcmua}}</td>
                <td>Bán {{$giavangsjcban}}</td>
            </tr>
            <tr class="h-36">
                <td class="bg-gray">DOJI</td>
                <td>Mua {{$giavangdoijmua}}</td>
                <td>Bán {{$giavangdoijban}}</td>
            </tr>
        </table>
    </div>

    <div class="exchange-rate">
        <table>
            <tr class>
                <th class="bg-info text-white c-w-90">Tỷ giá</th>
                <th class="bg-darken text-white">{{ date('d/m/Y', strtotime(Carbon\Carbon::now())) }}</th>
                <th class="bg-darken text-white">ĐVT: VNĐ</th>
            </tr>
            <tr class="h-36">
                <td class="bg-gray">USD</td>
                <td>Mua {{$giausdmua}}</td>
                <td>Bán {{$giausdban}}</td>
            </tr>
            <tr class="h-36">
                <td class="bg-gray">EUR</td>
                <td>Mua {{$giaeumua}}</td>
                <td>Bán {{$giaeumua}}</td>     
            </tr>
        </table>
    </div>
</div>


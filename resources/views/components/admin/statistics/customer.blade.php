<div class="customer-statistic">
    <div class="row">
        <div class="col-md-4">
            <div class="small-box bg-blue-medium">
                <div class="p-2 text-bold small-box-footer bg-blue-bold">Khách hàng đăng ký tư vấn</div>
                <div class="inner pb-4">
                    <h3 class="large">{{number_format($total_customer, 0, '.', '.')}}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box">
                <div class="p-2 text-bold small-box-footer">Liên hệ phát sinh trong
                    <span class="dropdown">
                        <a class="nav-link d-inline-block p-0 pr-3 customer-week-choose" data-toggle="dropdown">{{ $week_customer->title }}</a>
                        <div class="dropdown-menu">
                            <a href="{{route('admin.thongke.api.current_customer_week')}}" class="dropdown-item customer-week-options">Tuần</a>
                            <a href="{{route('admin.thongke.api.current_customer_month')}}" class="dropdown-item customer-week-options">Tháng</a>
                            <a href="{{route('admin.thongke.api.current_customer_year')}}" class="dropdown-item customer-week-options">Năm</a>
                        </div>
                    </span>
                </div>
                <div class="inner">
                    <h3 id="customer-week-value">{{ $week_customer->data }}</h3>
                    <p class="text-center mb-0">
                        <span id="customer-week-direction">{{ $week_customer->percent > 0 ? 'Tăng' : 'Giảm'}}</span>
                        <span class="{{ $week_customer->percent > 0 ? 'text-green' : 'text-red'  }}" id="customer-week-percent">{{ abs($week_customer->percent) }}%</span> so với
                        <span class="customer-week-choose text-lowercase">{{ $week_customer->title }}</span> trước
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="small-box">
                <div class="p-2 text-bold small-box-footer">Nhà đất bán</div>
                <div class="inner pb-4">
                    <h3>{{number_format($customer_sell, 0, '.', '.')}}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="small-box">
                <div class="p-2 text-bold small-box-footer">Nhà đất cho thuê</div>
                <div class="inner pb-4">
                    <h3>{{number_format($customer_rent, 0, '.', '.')}}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="data-chart">
        <div class="chart-title text-center">
            <h5 class="mb-1">Biểu đồ tiếp cận khách hàng theo
                <span class="dropdown">
                    <a class="nav-link d-inline-block p-0 pr-3" data-toggle="dropdown" id="customer-choose">Tháng</a>
                    <div class="dropdown-menu">
                        <a href="{{route('admin.thongke.api.customer_week')}}" class="dropdown-item customer-options">Tuần</a>
                        <a href="{{route('admin.thongke.api.customer_month')}}" class="dropdown-item customer-options">Tháng</a>
                        <a href="{{route('admin.thongke.api.customer_year')}}" class="dropdown-item customer-options">Năm</a>
                    </div>
                </span>
            </h5>
            <p>(Bao gồm: tuần, tháng, năm)</p>
        </div>
        <!-- solid sales graph -->
        <div class="card">
            <div class="card-body">
                <canvas class="chart" id="customer-chart" style="min-height: 250px; height: 420px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{asset('system/js/admin-chart/customer-chart.js')}}"></script>
@endpush

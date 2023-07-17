<div class="revenue-statistics">
    <div class="row">
        <div class="col-md-4">
            <div class="small-box bg-danger">
                <div class="p-2 text-bold bg-black small-box-footer">Tổng doanh thu</div>
                <div class="inner pb-4">
                    <h3>{{number_format($total_revenue, 0, '.', '.')}}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box">
                <div class="p-2 text-bold small-box-footer">Doanh thu từ nạp Coin</div>
                <div class="inner pb-4">
                    <h3>{{number_format($coin_revenue, 0, '.', '.')}}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box">
                <div class="p-2 text-bold small-box-footer">Doanh thu từ hình thức khác</div>
                <div class="inner pb-4">
                    <h3>{{number_format($other_revenue, 0, '.', '.')}}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="small-box">
                <div class="p-2 text-bold small-box-footer">Doanh thu từ Coin theo
                    <span class="dropdown">
                        <a class="nav-link d-inline-block p-0 pr-3 revenue-coin-choose text-capitalize" data-toggle="dropdown">{{ $coin_revenue_week->title }}</a>
                        <div class="dropdown-menu">
                            <a href="{{route('admin.thongke.api.current_revenue_week', 1)}}" class="dropdown-item revenue-coin-options">Tuần</a>
                            <a href="{{route('admin.thongke.api.current_revenue_month', 1)}}" class="dropdown-item revenue-coin-options">Tháng</a>
                            <a href="{{route('admin.thongke.api.current_revenue_year', 1)}}" class="dropdown-item revenue-coin-options">Năm</a>
                        </div>
                    </span>
                </div>
                <div class="inner">
                    <h3 id="revenue-value">{{ $coin_revenue_week->data }}</h3>
                    <p class="text-center mb-0">
                        <span id="revenue-direction">{{ $coin_revenue_week->percent > 0 ? 'Tăng' : 'Giảm'}}</span>
                        <span class="{{ $coin_revenue_week->percent > 0 ? 'text-green' : 'text-red'  }}" id="revenue-percent">{{ abs($coin_revenue_week->percent) }}%</span> so với
                        <span class="revenue-coin-choose text-lowercase">{{ $coin_revenue_week->title }}</span> trước
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="small-box">
                <div class="p-2 text-bold small-box-footer">Doanh thu từ Hình thức khác
                    <span class="dropdown">
                        <a class="nav-link d-inline-block p-0 pr-3 revenue-other-choose" data-toggle="dropdown">{{ $other_revenue_month->title }}</a>
                        <div class="dropdown-menu">
                            <a href="{{route('admin.thongke.api.current_revenue_week', 2)}}" class="dropdown-item revenue-other-options">Tuần</a>
                            <a href="{{route('admin.thongke.api.current_revenue_month', 2)}}" class="dropdown-item revenue-other-options">Tháng</a>
                            <a href="{{route('admin.thongke.api.current_revenue_year', 2)}}" class="dropdown-item revenue-other-options">Năm</a>
                        </div>
                    </span>
                </div>
                <div class="inner">
                    <h3 id="revenue-other-value">{{ $other_revenue_month->data }}</h3>
                    <p class="text-center mb-0">
                        <span id="revenue-other-direction">{{ $other_revenue_month->percent > 0 ? 'Tăng' : 'Giảm'}}</span>
                        <span class="{{ $other_revenue_month->percent > 0 ? 'text-green' : 'text-red'  }}" id="revenue-other-percent">{{ abs($other_revenue_month->percent) }}%</span> so với
                        <span class="revenue-other-choose text-lowercase">{{ $other_revenue_month->title }}</span> trước
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="data-chart">
        <div class="chart-title text-center">
            <h5 class="mb-1">Biểu đồ doanh thu theo
                <span class="dropdown">
                    <a class="nav-link d-inline-block p-0 pr-3" data-toggle="dropdown" id="revenue-choose">Tháng</a>
                    <div class="dropdown-menu">
                            <a href="{{route('admin.thongke.api.total_revenue_week')}}" class="dropdown-item revenue-options">Tuần</a>
                            <a href="{{route('admin.thongke.api.total_revenue_month')}}" class="dropdown-item revenue-options">Tháng</a>
                            <a href="{{route('admin.thongke.api.total_revenue_year')}}" class="dropdown-item revenue-options">Năm</a>
                    </div>
                </span>
            </h5>
            <p>(Bao gồm: tuần, tháng, năm)</p>
        </div>

        <!-- solid sales graph -->
        <div class="card">
            <!-- <div class="card-header border-0">
            <h3 class="card-title text-bold">
              Thành viên
            </h3>
          </div> -->
            <div class="card-body">
                <canvas class="chart" id="revenue-chart" style="min-height: 250px; height: 420px; max-width: 100%;"></canvas>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

@push('scripts')
    <script src="{{asset('system/js/admin-chart/revenue-chart.js')}}"></script>
@endpush

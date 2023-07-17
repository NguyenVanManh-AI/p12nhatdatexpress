<x-admin.user.statistics></x-admin.user.statistics>
<div class="data-chart">
    <div class="chart-title text-center">
        <h5 class="mb-1">Biểu đồ tăng trưởng thành viên theo
            <span class="dropdown">
                <a class="nav-link d-inline-block p-0 pr-3" data-toggle="dropdown" id="member-choose">Tháng</a>
                <div class="dropdown-menu">
                    <a href="{{route('admin.thongke.api.member_week')}}" class="dropdown-item member-options">Tuần</a>
                    <a href="{{route('admin.thongke.api.member_month')}}" class="dropdown-item member-options">Tháng</a>
                    <a href="{{route('admin.thongke.api.member_year')}}" class="dropdown-item member-options">Năm</a>
                </div>
            </span>
        </h5>
        <p>(Bao gồm: tuần, tháng, năm)</p>
    </div>
    <!-- solid sales graph -->
    <div class="card">
        <div class="card-body">
            <canvas class="chart" id="line-chart" style="min-height: 250px; height: 420px; max-width: 100%;"></canvas>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{asset('system/js/admin-chart/member-chart.js')}}"></script>
@endpush

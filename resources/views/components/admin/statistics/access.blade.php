<div class="row data-access">
    <div class="col-md-4">
        <div class="small-box bg-green">
            <div class="p-2 text-bold text-center">Số người truy cập hiện tại</div>
            <div class="inner">
                <h3 class="large">{{number_format($access_today, 0, '.', '.')}}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-blue-medium">
            <div class="p-2 text-bold text-center">Tổng số người truy cập</div>
            <div class="inner">
                <h3 class="large">{{number_format($access_total, 0, '.', '.')}}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-orange">
            <div class="p-2 text-bold text-center">Số người dùng truy cập theo
                <span class="dropdown">
                    <a class="nav-link d-inline-block p-0 pr-3 access-choose" data-toggle="dropdown">Tháng</a>
                    <div class="dropdown-menu">
                        <a href="{{route('admin.thongke.api.access_week')}}" class="dropdown-item access-options">Tuần</a>
                        <a href="{{route('admin.thongke.api.access_month')}}" class="dropdown-item access-options">Tháng</a>
                        <a href="{{route('admin.thongke.api.access_year')}}" class="dropdown-item access-options">Năm</a>
                    </div>
              </span>
            </div>
            <div class="inner">
                <h3 id="access-value" class="large">{{ $access_month->data }}</h3>
                <p class="text-center mb-0 font-weight-normal">
                    <span id="access-direction" class="font-weight-bold">{{ $access_month->percent > 0 ? 'Tăng' : 'Giảm'}}</span>
                    <span class="font-weight-bold" id="access-percent">{{ abs($access_month->percent) }}%</span> so với
                    <span class="access-choose text-lowercase">{{ $access_month->title }}</span> trước
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{asset('system/js/admin-chart/access.js')}}"></script>
@endpush

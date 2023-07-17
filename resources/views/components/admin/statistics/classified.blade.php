<div class="other-statistic">
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-blue-medium">
                <div class="p-2 text-bold small-box-footer bg-blue-bold">Tổng số tin đăng</div>
                <div class="inner pb-4">
                    <h3 class="large">{{number_format($total_classified, 0, '.', '.')}}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box">
                <div class="p-2 text-bold small-box-footer">Tin đăng mới trong
                    <span class="dropdown cursor-pointer">
                        <a class="nav-link d-inline-block p-0 pr-3 classified-total-month-choose" data-toggle="dropdown">{{ $classified_current->title }}</a>
                        <div class="dropdown-menu">
                            <a href="{{route('admin.thongke.api.current_classified_week')}}" class="dropdown-item classified-total-month-options">Tuần</a>
                            <a href="{{route('admin.thongke.api.current_classified_month')}}" class="dropdown-item classified-total-month-options">Tháng</a>
                            <a href="{{route('admin.thongke.api.current_classified_year')}}" class="dropdown-item classified-total-month-options">Năm</a>
                        </div>
                    </span>
                </div>
                <div class="inner">
                    <h3 id="classified-total-month-value">{{ $classified_current->data }}</h3>
                    <p class="text-center mb-0">
                        <span id="classified-total-month-direction">{{ $classified_current->percent > 0 ? 'Tăng' : 'Giảm'}}</span>
                        <span class="{{ $classified_current->percent > 0 ? 'text-green' : 'text-red'  }}" id="classified-total-month-percent">{{ abs($classified_current->percent) }}%</span> so với
                        <span class="classified-total-month-choose text-lowercase">{{ $classified_current->title }}</span> trước
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box">
                <div class="p-2 text-bold small-box-footer">Nhà đất bán trong
                    <span class="dropdown cursor-pointer">
                        <a class="nav-link d-inline-block p-0 pr-3 classified-sell-month-choose" data-toggle="dropdown">{{ $classified_sell_current->title }}</a>
                        <div class="dropdown-menu">
                            <a href="{{route('admin.thongke.api.current_classified_week', [1, 2])}}" class="dropdown-item classified-sell-month-options">Tuần</a>
                            <a href="{{route('admin.thongke.api.current_classified_month', [1, 2])}}" class="dropdown-item classified-sell-month-options">Tháng</a>
                            <a href="{{route('admin.thongke.api.current_classified_year', [1, 2])}}" class="dropdown-item classified-sell-month-options">Năm</a>
                        </div>
                    </span>
                </div>
                <div class="inner">
                    <h3 id="classified-sell-month-value">{{ $classified_sell_current->data }}</h3>
                    <p class="text-center mb-0">
                        <span id="classified-sell-month-direction">{{ $classified_sell_current->percent > 0 ? 'Tăng' : 'Giảm'}}</span>
                        <span class="{{ $classified_sell_current->percent > 0 ? 'text-green' : 'text-red'  }}" id="classified-sell-month-percent">{{ abs($classified_sell_current->percent) }}%</span> so với
                        <span class="classified-sell-month-choose text-lowercase">{{ $classified_sell_current->title }}</span> trước
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box">
                <div class="p-2 text-bold small-box-footer">Nhà đất cho thuê trong
                    <span class="dropdown cursor-pointer">
                        <a class="nav-link d-inline-block p-0 pr-3 classified-rent-month-choose" data-toggle="dropdown">{{ $classified_rent_current->title }}</a>
                        <div class="dropdown-menu">
                            <a href="{{route('admin.thongke.api.current_classified_week', [1, 10])}}" class="dropdown-item classified-rent-month-options">Tuần</a>
                            <a href="{{route('admin.thongke.api.current_classified_month', [1, 10])}}" class="dropdown-item classified-rent-month-options">Tháng</a>
                            <a href="{{route('admin.thongke.api.current_classified_year', [1, 10])}}" class="dropdown-item classified-rent-month-options">Năm</a>
                        </div>
                    </span>
                </div>
                <div class="inner">
                    <h3 id="classified-rent-month-value">{{ $classified_rent_current->data }}</h3>
                    <p class="text-center mb-0">
                        <span id="classified-rent-month-direction">{{ $classified_rent_current->percent > 0 ? 'Tăng' : 'Giảm'}}</span>
                        <span class="{{ $classified_rent_current->percent > 0 ? 'text-green' : 'text-red'  }}" id="classified-rent-month-percent">{{ abs($classified_rent_current->percent) }}%</span> so với
                        <span class="classified-rent-month-choose text-lowercase">{{ $classified_rent_current->title }}</span> trước
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="slick">
        @foreach($group_classified as $group)
        <div class="small-box">
            <div class="p-2 text-bold small-box-footer">{{$group->group_name}}</div>
            <div class="inner">
                <h3>{{number_format($group->classified_count, 0, '.', '.')}}</h3>
                <p class="text-center mb-0">{{$group->percent > 0 ? 'Tăng' : 'Giảm'}}
                    <span class="text-{{$group->percent > 0 ? 'green' : 'red'}}">{{$group->percent}}%</span> so với tháng trước</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="slick">
        @foreach($province_classified as $province)
            <div class="small-box">
                <div class="p-2 text-bold small-box-footer">{{$province->province_name}}</div>
                <div class="inner">
                    <h3>{{number_format($province->classified_count, 0, '.', '.')}}</h3>
                    <p class="text-center mb-0">{{$province->percent > 0 ? 'Tăng' : 'Giảm'}} <span class="text-{{$province->percent > 0 ? 'green' : 'red'}}">{{$province->percent}}%</span> so với tháng trước</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="data-chart">
        <div class="chart-title text-center">
            <h5 class="mb-1">Biểu đồ tăng trưởng website theo
                <span class="dropdown cursor-pointer">
                <a class="nav-link d-inline-block p-0 pr-3" id="classified-choose" data-toggle="dropdown">Tháng</a>
                <div class="dropdown-menu">
                    <a href="{{route('admin.thongke.api.classified_week')}}" class="dropdown-item classified-options">Tuần</a>
                    <a href="{{route('admin.thongke.api.classified_month')}}" class="dropdown-item classified-options">Tháng</a>
                    <a href="{{route('admin.thongke.api.classified_year')}}" class="dropdown-item classified-options">Năm</a>
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
                <canvas class="chart" id="website-chart" style="min-height: 250px; height: 420px; max-width: 100%;"></canvas>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

@push('scripts')
    <script src="{{asset('frontend/plugins/slickjs/slick.js')}}"></script>
    <script src="{{asset('system/js/admin-chart/classified-chart.js')}}"></script>
@endpush

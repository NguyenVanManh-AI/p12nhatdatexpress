<div class="row member-rank mb-5">
    <div class="col-md-6">
        <div class="small-box">
            <div class="p-2 pl-4 text-bold small-box-footer text-left bg-black">Top 100 thành viên nạp Coin nhiều nhất
                <span class="dropdown float-right">
                    <a class="nav-link d-inline-block p-0 pr-3 member-charge-choose" data-toggle="dropdown">Tất cả</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{route('admin.thongke.api.current_charge_week')}}" class="dropdown-item member-charge-options">Tuần</a>
                        <a href="{{route('admin.thongke.api.current_charge_month')}}" class="dropdown-item member-charge-options">Tháng</a>
                        <a href="{{route('admin.thongke.api.current_charge_year')}}" class="dropdown-item member-charge-options">Năm</a>
                        <a href="{{route('admin.thongke.api.charge_total')}}" class="dropdown-item member-charge-options">Tất cả</a>
                    </div>
                </span>
            </div>
            <div class="inner" id="list-charge-users" data-coin-href="{{route('admin.coin.list')}}">
                <!--
                <div class="member-item">
                    <div class="index">1</div>
                    <div class="avatar">
                        <div class="image">
                            <div class="top1">
                                <img src="/images/icons/top1.png" alt="">
                            </div>
                            <img src="./images/avatar-rank.png" alt="">
                        </div>
                    </div>

                    <div class="member-info">
                        <div class="name">Nguyễn Văn A</div>
                        <div class="info">
                            <span>Số Coin đã nạp:</span>
                            <span>1.000.000</span>
                        </div>
                    </div>
                    <div class="view">
                        <a href="#" class="text-blue-light">Xem</a>
                    </div>
                </div>
                -->
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="small-box">
            <div class="p-2 pl-4 text-bold small-box-footer text-left bg-black">Top 100 thành viên đăng tin thường xuyên nhất
                <span class="dropdown float-right">
                    <a class="nav-link d-inline-block p-0 pr-3 member-classified-choose" data-toggle="dropdown">Tất cả</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{route('admin.thongke.api.current_classified_charge_week')}}" class="dropdown-item member-classified-options">Tuần</a>
                        <a href="{{route('admin.thongke.api.current_classified_charge_month')}}" class="dropdown-item member-classified-options">Tháng</a>
                        <a href="{{route('admin.thongke.api.current_classified_charge_year')}}" class="dropdown-item member-classified-options">Năm</a>
                        <a href="{{route('admin.thongke.api.classified_total')}}" class="dropdown-item member-classified-options">Tất cả</a>
                    </div>
                </span>
            </div>
            <div class="inner" id="list-classified-users" data-classified-href="{{route('admin.classified.list')}}"></div>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{asset('system/js/admin-chart/member-rank.js')}}"></script>
@endpush

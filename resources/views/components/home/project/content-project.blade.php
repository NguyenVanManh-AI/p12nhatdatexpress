<link rel="stylesheet" href="{{asset('system/plugins/ion-rangeslider/css/ion.rangeSlider.min.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/slick.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/plusb.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/responsive.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/responsiveb.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/responsiveh.css')}}">
<link rel="stylesheet" href="{{asset('system/css/project-view.css')}}">
<section>
    <div class="project-banner-slide slick-slider" style="height: 500px">
        @foreach(json_decode($project->image_url) as $item)
            <img src="{{$item}}">
        @endforeach
    </div>
    <div>
        <div class="after-banner justify-content-around" style="position: static;width: 100%">
            <div class="item">
                <a href="#tongquan">
                    <i class="fas fa-chart-bar"></i>Tổng quan
                </a>
            </div>
            @if($project->location_descritpion)
                <div class="item">
                    <a href="#vitri">
                        <i class="fas fa-map-marker-alt"></i>Vị trí
                    </a>
                </div>
            @endif
            @if($project->utility_description)
                <div class="item">
                    <a href="#tienich">
                        <i class="fas fa-share-alt"></i>Tiện ích
                    </a>
                </div>
            @endif
            @if($project->ground_description)
                <div class="item">
                    <a href="#matbang">
                        <i class="fas fa-vector-square"></i>Mặt bằng
                    </a>
                </div>
            @endif
            @if($project->legal_description)
                <div class="item">
                    <a href="#phaply">
                        <i class="fas fa-file-contract"></i>Pháp lý
                    </a>
                </div>
            @endif
            @if($project->payment_description)
                <div class="item">
                    <a href="#thanhtoan">
                        <i class="fas fa-coins"></i>Thanh toán
                    </a>
                </div>
            @endif
        </div>
    </div>
    <div class="px-5 pt-4">
        <h2 class="single-title" id="tongquan"> {{$project->project_name}}</h2>
        <div class="single-meta">
            <div class="left">
                <div class="price">
                    <i class="fas fa-tags"></i>{{\App\Helpers\Helper::format_money($project->project_price) ?? \App\Helpers\Helper::format_money($project->project_rent_price) ?? "Đang cập nhật"}}
                    @if($project->project_price)
                        {{$project->unit_sell->unit_name}}
                    @elseif($project->project_rent_price)
                        {{$project->unit_rent->unit_name}}
                    @endif
                </div>
                @if($project->project_scale)
                    <div class="area">
                        <i class="fas fa-vector-square"></i>
                        {{$project->project_scale}} {{$project->unit_scale->unit_name}}

                    </div>
                @endif
                <div class="location">
                    <i class="fas fa-map-marker-alt"></i>
                    {{$project->location->district->district_name}}, {{$project->location->province->province_name}}
                </div>
            </div>
            <div class="right">
                @if(!$is_preview)
                    <span class="share-button">
                    <i class="fas fa-share-alt mr-2"></i>Chia sẻ
                    <div class="share-sub" style="display: none;">
                        <a class="item">
                            <i class="fab fa-facebook-square"></i>
                            Facebook
                        </a>
                        <a class="item">
                            <i class="fab fa-twitter-square"></i>
                            Twitter
                        </a>
                        <a class="item">
                            <i class="fab fa-pinterest-square"></i>
                            Pinterest
                        </a>
                        <a class="item">
                            <i class="zalo-icon"></i>
                            Zalo
                        </a>
                    </div>
                </span>
                @endif
                <span class="view-count mr-3">
                    <i class="fas fa-chart-bar"></i>Lượt xem: <span>{{$project->num_view ?? 0}}</span>
                </span>
            </div>
        </div>
        {{-- <div class="single-contact-buttons" style="">
            <a href="#" class="green">
                <i class="fas fa-phone-alt"></i>
                Liên hệ
            </a>
            <a href="#" class="blue">
                <i class="fas fa-user"></i>
                Đăng ký thông tin
            </a>
        </div> --}}
        <div class="project-detail">
            <div class="text-break">
                {!! $project->project_content !!}
            </div>
            @if($project->location_descritpion)
                <div class="segment" id="vitri">
                    <div class="segment-head">
                        <h4><i class="fas fa-map-marker-alt"></i>Vị trí</h4>
                    </div>
                    {!! $project->location_descritpion !!}
                </div>

            @endif
            @if($project->utility_description)
                <div class="segment" id="tienich">
                    <div class="segment-head">
                        <h4><i class="fas fa-share-alt"></i>Tiện ích</h4>
                    </div>
                    {!! $project->utility_description !!}
                </div>
            @endif
            @if($project->ground_description)

                <div class="segment" id="matbang">
                    <div class="segment-head">
                        <h4><i class="fas fa-vector-square"></i>Mặt bằng</h4>
                    </div>
                    {!! $project->ground_description !!}
                </div>
            @endif
            @if($project->legal_description)
                <div class="segment" id="phaply">
                    <div class="segment-head">
                        <h4><i class="fas fa-file-contract"></i>Pháp lý</h4>
                    </div>
                    {!! $project->legal_description !!}
                </div>
            @endif
            @if($project->payment_description)
                <div class="segment" id="thanhtoan">
                    <div class="segment-head">
                        <h4><i class="fas fa-coins"></i>Thanh toán</h4>
                    </div>
                    {!! $project->payment_description !!}
                </div>

            @endif
            <div class="table-wrapper">
                <div class="table-information table">
                    <div class="table-head">Thông tin chi tiết</div>
                    <div class="item cl-1 row-1">
                        <span class="node">{{$properties[0]->name ?? "Tên dự án"}}</span>
                        <span class="name">{{$project->project_name}}</span>
                    </div>
                    <div class="item cl-1 row-2">
                        <span class="node">{{$properties[3]->name ?? "Mô hình"}}</span>
                        <span class="name blue">{{$project->group->group_name}}</span>
                    </div>
                    <div class="item cl-1 row-3">
                        <span class="node">{{ $properties[6]->name ?? "Chủ đầu tư"}}</span>
                        <span class="name blue">{{$project->investor ?? "---"}}</span>
                    </div>
                    <div class="item cl-1 row-4">
                        <span class="node">{{$properties[9]->name ?? "Vị trí"}}</span>
                        <span
                            class="name blue">{{$project->location->district->district_name}}, {{$project->location->province->province_name}}</span>
                    </div>
                    <div class="item cl-1 row-5">
                        <span class="node">{{$properties[12]->name ?? "Tình trạng"}}</span>
                        <span
                            class="name green">{{$project->progress->progress_name}}
                            @if($is_preview != true)
                                <span class="update"> (Cập nhật)</span>
                            @endif
                        </span>
                    </div>
                    <div class="item cl-2 row-1">
                        <span class="node">{{$properties[1]->name ?? "Giá bán"}}</span>
                        <span class="name red">
                                <strong>{{$project->project_price ? "~" : ""}} {{\App\Helpers\Helper::format_money($project->project_price) ?? "Đang cập nhật"}}
                                    @if($project->project_price)
                                        {{$project->unit_sell->unit_name}}
                                    @endif
                                </strong>
                             @if($is_preview != true)
                                <span class="update"> (Cập nhật)</span>
                            @endif
                        </span>
                    </div>
                    <div class="item cl-2 row-2">
                        <span class="node">{{$properties[4]->name ?? "Giá thuê"}}</span>
                        <span class="name blue">{{\App\Helpers\Helper::format_money($project->project_rent_price) ?? "Đang cập nhật"}}
                        @if($project->project_rent_price)
                            {{$project->unit_rent->unit_name}}

                        @endif
                            @if($is_preview != true)
                                <span class="update"> (Cập nhật)</span>
                            @endif
                </span>
                    </div>
                    <div class="item cl-2 row-3">
                        <span class="node">{{$properties[7]->name ?? "Diện tích"}}</span>
                        <span class="name blue">
                            {{ $project->getAreaLabel() }}
                            {{-- {{$project->project_area_from}} - {{$project->project_area_to}} {{$project->unit_area->unit_name}} --}}
                        </span>
                    </div>
                    <div class="item cl-2 row-4">
                        <span class="node">{{$properties[10]->name ?? "Pháp lý"}}</span>
                        <span class="name red">{{$project->legal->param_name}}</span>
                    </div>
                    <div class="item cl-2 row-5">
                        <span class="node">{{$properties[13]->name ?? "Nội thất"}}</span>
                        <span class="name blue">{{$project->furniture->furniture_name ?? "---"}}</span>
                    </div>
                    <div class="item cl-3 row-1">
                        <span class="node">{{$properties[2]->name ?? "Hướng nhà"}}</span>
                        <span class="name green">{{$project->direction->direction_name}}</span>
                    </div>
                    <div class="item cl-3 row-2">
                        <span class="node">{{$properties[5]->name ?? "Quy mô"}}</span>
                        <span class="name blue"> {{$project->project_scale ?? "---"}}
                            @if($project->project_scale)
                                {{$project->unit_scale->unit_name}}
                            @endif
                        </span>
                    </div>
                    <div class="item cl-3 row-3">
                        <span class="node">{{$properties[8]->name ?? "Hỗ trợ vay"}}</span>
                        <span class="name blue">{{$project->bank_sponsor == 1 ? "Có" : "---"}}</span>
                    </div>
                    <div class="item cl-3 row-4">
                        <span class="node">{{$properties[11]->name ?? "Đường"}}</span>
                        <span class="name blue">
                            @if($project->project_road)
                                {{$project->project_road}} {{$project->unit_road->unit_name}}
                            @else
                                {{ "---" }}
                            @endif
                        </span>
                    </div>
                    <div class="item cl-3 row-5">
                        <span class="node">{{$properties[14]->name ?? "Đăng ngày"}}</span>
                        <span class="name green">
                            <strong>
                                @if($project->created_at)
                                    {{date('d/m/Y', $project->created_at)}}
                                @else
                                    {{"---"}}
                                @endif
                            </strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 100%;margin: auto;margin-top: 60px">
            <div class="table-utility table">
                <div class="table-head">
                    Hệ thống tiện ích
                </div>
                <div class="ulitily-list">
                    @forelse( $get_utility_list() as $item)
                        <div class="item">
                            <i class="{{$item->image_url}}"></i>
                            <p>{{$item->utility_name}}</p>
                        </div>
                    @empty
                       <span style="margin-top: -25px; padding-left: 12px">Không có tiện ích</span>
                    @endforelse
                </div>
            </div>
        </div>
        <div>
            <div class="single-tabs">
                <div class="tab-switchers">
                    <div class="switcher active" id="tab-switcher-1">Bản đồ</div>
                    @if($project->video)
                    <div class="switcher " id="tab-switcher-2">Video dự án</div>
                    @endif
                    @if($project->project_price && $project->bank_sponsor == 1)
                    <div class="switcher" id="tab-switcher-3">Tính lãi suất</div>
                    @endif
                </div>
                <div class="tabs">
                    <div class="tab2 d-none" id="tab-1">
                        <iframe
                            src="https://www.google.com/maps?q={{$project->location->map_latitude}},{{$project->location->map_longtitude}}&hl=vi&z=16&amp;output=embed"
                            width="100%" height="600px" style="border:0;" allowfullscreen="" loading="lazy"></iframe>

                    </div>
                    @if($project->video)
                    <div class="tab2 d-none" id="tab-2">
                        <iframe width="100%" height="600px" src="{{$project->video}}"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                    @endif
                    <div class="tab2 " id="tab-3">
                        <div class="my-3" style="width: 100%">
                            <div class="row m-0">
                                <div class="col-12 p-0 d-flex justify-content-center">
                                    <div class="d-flex align-items-center">
                                        <label class="radio-custom"><span style="font-weight: 550" class="ml-1">Số tiền trả dư nợ giảm dần</span>
                                            <input value="0" class="mt-1 mr-2" type="radio" id="pay-type-1" name="pay" checked>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="d-flex align-items-center ml-5">
                                        <label class="radio-custom ml-5"><span style="font-weight: 550" class="ml-1">Số tiền trả đều hàng tháng</span>
                                            <input value="1" class="mt-1 mr-2" type="radio" id="pay-type-2" name="pay">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="row m-0 ">
                                <div class="box-range col-12 col-sm-12 col-md-9 col-lg-9  p-0 pr-5" style="height: 50px">
                                    <div class="row m-0 mt-3">
                                        <div class="col-3 p-0 pt-1">
                                            <span class="font-weight-bold ">Giá trị nhà đất</span>
                                        </div>
                                        <div class="col-9 p-0">
                                            <input id="giatrinhadatrange" type="range" name="giatrinhadat" min="0" disabled
                                                   max="10000">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-3 col-lg-3 p-0 d-flex mt-3">
                                    <div style="width: calc(100% - 80px);">
                                        <input class="text-right pr-2 input-number-no-arrow" id="giatrinhadatinput" type="number" name="" value="1500" disabled lang="en-ES"
                                               style="width: 100%;height: 36px;outline: none">
                                    </div>
                                    <div class="p-2" style="width: 80px;height: 36px;border:1px solid #ccc; text-align: center">
                                        <i>Triệu</i>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-0 mt-3">
                                <div class="box-range col-12 col-sm-12 col-md-9 col-lg-9 p-0 pr-5" style="height: 50px">
                                    <div class="row m-0 mt-3">
                                        <div class="col-3 p-0 pt-1">
                                            <span class="font-weight-bold">Số tiền vay</span>
                                        </div>
                                        <div class="col-9 p-0">
                                            <input id="sotienvayrange" type="range" name="sotienvay" min="0" max="10000">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-3 col-lg-3 p-0 d-flex mt-3">
                                    <div style="width: calc(100% - 80px);">
                                        <input class="text-right pr-2 input-number-no-arrow" id="sotienvayinput" type="number" name="" value="1500"
                                               style="width: 100%;height: 36px;outline: none">
                                    </div>
                                    <div class="d-flex pr-2" style="width: 80px;height: 36px;border: 1px solid #ccc">
                                        <input class="pl-3 input-number-show-arrow" type="number" name="" value="70" max="100" min="0" id="nganHangHoTro"
                                               style="width: 100%;height: 36px;outline: none;border:0 !important;background: none; text-align: center">
                                        <span style="line-height: 36px">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-0 mt-3">
                                <div class="box-range col-12 col-sm-12 col-md-9 col-lg-9 p-0 pr-5" style="height: 50px">
                                    <div class="row m-0 mt-3">
                                        <div class="col-3 p-0 pt-1">
                                            <span class="font-weight-bold">Thời gian vay</span>
                                        </div>
                                        <div class="col-9 p-0">
                                            <input id="thoigianvayrange" type="range" name="thoigianvay" min="0"
                                                   max="10000">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-3 col-lg-3 p-0 d-flex mt-3">
                                    <div style="width: calc(100% - 80px);">
                                        <input class="text-right pr-2 input-number-show-arrow" id="thoigianvayinput" type="number" value="1500" min="1" max="20"
                                               style="width: 100%;height: 36px;outline: none">
                                    </div>
                                    <div class="p-2" style="width: 80px;height: 36px;border:1px solid #ccc; text-align: center">
                                        <i>Năm</i>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-0 mt-3 mb-3">
                                <div class="box-range col-12 col-sm-12 col-md-9 col-lg-9 p-0 pr-5" style="height: 50px">
                                    <div class="row m-0 mt-3">
                                        <div class="col-3 p-0 pt-1">
                                            <span class="font-weight-bold">Lãi suất</span>
                                        </div>
                                        <div class="col-9 p-0">

                                            <input id="laisuatrange" type="range" name="laisuat" min="0" max="10000">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-3 col-lg-3 p-0 d-flex mt-3">
                                    <div style="width: calc(100% - 80px);">
                                        <input class="text-right pr-2 input-number-show-arrow" id="laisuatinput" type="number" name="" value="1500"
                                               style="width: 100%;height: 36px;outline: none">
                                    </div>
                                    <div class="p-2" style="width: 80px;height: 36px;border:1px solid #ccc; text-align: center">
                                        <i>% năm</i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="chart2">
                            <div class="chart-container2" style="width: 200px">
                                <canvas id="single-chart" width="180" height="180"></canvas>
                                <div class="total" id="tongCong">2.34 tỷ</div>
                            </div>
                            <div class="chart-statistic">
                                <div class="number">
                                    <div class="item">
                                        <div class="label">Cần trả trước</div>
                                        <div class="value" style="color: #1154D4;" id="canTraTruoc">450,000,000 </div>
                                    </div>
                                    <div class="item">
                                        <div class="label">Gốc cần trả</div>
                                        <div class="value" style="color: #403D64;" id="gocCanTra">1,050,000,000 VND</div>
                                    </div>
                                    <div class="item">
                                        <div class="label">Lãi cần trả</div>
                                        <div class="value" style="color: #EA9851;" id="laiCanTra">800,000,000 VND</div>
                                    </div>
                                </div>
                                <div class="sum">
                                    <div class="label">Số tiền trả tháng đầu</div>
                                    <div class="value" style="color: #0062DC;" id="soTienTraThangDau">15,750,000 VND</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<footer>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="{{asset('frontend/js/slick.js')}}"></script>
<script src="{{asset('system/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
<script src="{{asset('frontend/js/plusb.js')}}"></script>
<script>
    data = getDataForChart();
    labels = ['Lãi cần trả', 'Cần trả trước', 'Gốc cần trả']
    chart = new Chart("single-chart", {
        type: "doughnut",
        data: {
            labels,
            datasets: [{
                backgroundColor: ['#EA9851', '#1154D4', '#403D64'],
                data,
            }]
        },
        options: {
            cutoutPercentage: 80,
            responsive: true,
            legend: {
                display: false
            },
            tooltips: {
                mode: 'index',
                intersect: false
            },
            hover: {
                mode: 'index',
                intersect: false
            }
        }
    });
    function getDataForChart(){
        lct = $('#laiCanTra').html().toString().replaceAll(',','').replace(' VND','');
        ctt = $('#canTraTruoc').html().toString().replaceAll(',','').replace(' VND','');
        gct = $('#sotienvayinput').val().toString().replaceAll(/[,|.]/g,'') * 1000000;
        return [lct, ctt, gct];
    }
    function updateChart(chart, data){
        chart.data.datasets[0].data = data
        chart.update();
    }
</script>
<script>
    function roundCurrency(number, position){
        size = (number + "").length;
        div = Math.pow(10, position);
        return Math.ceil(number / div) * div;
    }
    function formatCurrency(number) {
        if(isNaN(number)) return 0;
        number = (number+"").replace(/[,]/g,'')
        return new Intl.NumberFormat('en-ES').format(number).toString().replace(',', '.')
    }
    function convertCurrencyToString(currency) {
        return currency.toString().replace('.','')
    }
    function calcPercentLoan(totalPrice, loan){
        if (loan == 0) return 0;
        return ((loan / totalPrice) * 100).toFixed(0) == 0 ? 1 : ((loan / totalPrice) * 100).toFixed(0);
    }
    function calcUnitCost(price){
        all = (price + "").split(/[.|,]/g);
        price = all[0];
        subPrice = (all[0] + "").length > 2 ? all[0].substring(1,3) : all[0].substring(1,2);
        if (price == 0) return 0
        if (price.length > 9)
            return price.substring(0, price.length - 9) + "." + subPrice + " tỷ";
        else
            return  price.substring(0, price.length - 6) + "." + subPrice + " triệu";
    }

    function getGiaTriNhaDat(project_price, unit_id, area){
        result = 0;
        switch (unit_id) {
            case 1:
                result = project_price * area;
                break;
            case 2:
                result = project_price / 1000000 * area;
                break;
            case 3:
                result = project_price * area;
                break;
            case 4:
                result = project_price * 1000 * area;
                break;
        }
        return result;
    }
    function laiCanTra(soTienVay, soNam, laiSuatNam, option){
        laiSuatNam = laiSuatNam / 100;
        soTienVay = soTienVay * 1000000;
        laiSuatHangThang = laiSuatNam / 12;
        soThang = soNam * 12;
        tienTraHangThang = soTienVay / soThang;
        tongLaiSuat = 0;
        tongPhaiTra = 0;
        tienTraThangDau = 0;
        // Lai suat co dinh
        if (option == 1){
            tienLai1Thang = soTienVay * laiSuatNam / 12;
            tongLaiSuat = tienLai1Thang * soThang;
            tongPhaiTra = tongLaiSuat + soTienVay;
            tienTraThangDau = tienTraHangThang + tienLai1Thang;
            return {tongLaiSuat, tienTraThangDau, tongPhaiTra}
        }else {
            for (i = 1; i <= soThang; i++){
                tongLaiSuat +=  (soTienVay - (i- 1) * tienTraHangThang ) * laiSuatHangThang;
            }
            tongPhaiTra = soTienVay + tongLaiSuat;
            tienTraThangDau = (soTienVay * laiSuatHangThang) + tienTraHangThang;
            return {tongLaiSuat, tienTraThangDau, tongPhaiTra}
        }
    }
    function setCanTraTruoc(giaTriNhaDat, soTienVay, elementTarget) {
        giaTriNhaDat = convertCurrencyToString(giaTriNhaDat)
        soTienVay = convertCurrencyToString(soTienVay)
        result = giaTriNhaDat - soTienVay;
        if(result < 0)
            $(elementTarget).html(0 + " VND")
        else
            $(elementTarget).html(formatCurrency(result * 1000000).toString().replace('.',',') + " VND")
    }
    function setGocCanTra(soTienVay, elementTarget) {
        soTienVay = soTienVay.replace('.','')
        $(elementTarget).html(formatCurrency(soTienVay * 1000000).toString().replace('.',',') + " VND")
    }
    function setLaiCanTra(soTienVay, soNam, laiSuatNam, option, laiCanTraElement, traThangDauElement, tongCongElement){
        result = laiCanTra(soTienVay, soNam, laiSuatNam, option);
        $(laiCanTraElement).html(formatCurrency(result.tongLaiSuat.toFixed(0)).toString().replace('.',',') + " VND")
        $(traThangDauElement).html(formatCurrency(result.tienTraThangDau.toFixed(0)).toString().replace('.',',') + " VND")
        $(tongCongElement).html(calcUnitCost(result.tongPhaiTra).toString().replace('.',','))
    }

</script>
<script type="text/javascript">
    initGiaTriNhaDat = getGiaTriNhaDat({{$project->project_price ?? 0}}, {{$project->price_unit_id ?? 0}} ,{{$project->project_area_to ?? 0}});
    initNganHangChoVay = 70;
    initSoTienVay = parseInt((initNganHangChoVay / 100 * initGiaTriNhaDat).toString().replace(',','')).toFixed(0);
    initThoiGianVay = 10;
    initLaiSuat = 8;
    initMinVay = 0.1 * initGiaTriNhaDat;

    $('#nganHangHoTro').val(initNganHangChoVay)
    setCanTraTruoc(initGiaTriNhaDat, initSoTienVay, '#canTraTruoc')
    setGocCanTra(initSoTienVay, '#gocCanTra')

    $("#giatrinhadatrange").ionRangeSlider({
        min: 0,
        max: initGiaTriNhaDat * 2,
        disable : true,
        from: initGiaTriNhaDat,
        onChange: function (data) {
            // $('#giatrinhadatinput').val(formatCurrency(data.from));
            // setCanTraTruoc(data.from, initSoTienVay, '#canTraTruoc')
            // sotienvayrange.update({
            //     max: (initNganHangChoVay / 100) * data.from
            // })
        },
    });
    var giatrinhadat = $("#giatrinhadatrange").data("ionRangeSlider");
    $("#giatrinhadatinput").keyup(function () {
        giatrinhadat.update({
            from: $(this).val()
        })
    })
    $("#giatrinhadatinput").val(formatCurrency(initGiaTriNhaDat))

    $("#sotienvayrange").ionRangeSlider({
        min: 0.1 * initGiaTriNhaDat,
        max: initGiaTriNhaDat,
        from: initSoTienVay,
        onChange: function (data) {
            $('#sotienvayinput').val(formatCurrency(data.from));
            setGocCanTra(formatCurrency(data.from), '#gocCanTra')
            setCanTraTruoc($("#giatrinhadatinput").val(), formatCurrency(data.from), '#canTraTruoc')
            $('#nganHangHoTro').val(calcPercentLoan(initGiaTriNhaDat, data.from));
            updateChart(chart, getDataForChart())
        }
    });
    var sotienvayrange = $("#sotienvayrange").data("ionRangeSlider");
    $("#sotienvayinput").on("keyup change", function () {
        value = this.value.toString().replace('.','')
        $('#sotienvayinput').val(formatCurrency((value + "").replace('.','')));
        sotienvayrange.update({
            from: convertCurrencyToString(this.value)
        })
        setGocCanTra(formatCurrency(value), '#gocCanTra')
        setCanTraTruoc($("#giatrinhadatinput").val(), formatCurrency(value), '#canTraTruoc')
        setLaiCanTra(convertCurrencyToString($('#sotienvayinput').val()), $('#thoigianvayinput').val(), $('#laisuatinput').val(), this.value, '#laiCanTra', '#soTienTraThangDau', '#tongCong')
        updateChart(chart, getDataForChart())
        $('#nganHangHoTro').val(calcPercentLoan(initGiaTriNhaDat, convertCurrencyToString(this.value)));
    })
    $("#sotienvayinput").val(initSoTienVay)

    $('#nganHangHoTro').on("keyup change", function () {
        $("#sotienvayinput").val(formatCurrency(($(this).val() /100) * initGiaTriNhaDat ))
        sotienvayrange.update({
            from: ($(this).val() /100) * initGiaTriNhaDat,
        })
        sotienvayrange.options.onChange({from: ($(this).val() /100) * initGiaTriNhaDat,});
    })

    $("#thoigianvayrange").ionRangeSlider({
        min: 1,
        max: 20,
        from: initThoiGianVay,
        onChange: function (data) {
            $('#thoigianvayinput').val(data.from);
            updateChart(chart, getDataForChart())
            setLaiCanTra(convertCurrencyToString($('#sotienvayinput').val()), $('#thoigianvayinput').val(), $('#laisuatinput').val(), this.value, '#laiCanTra', '#soTienTraThangDau', '#tongCong')
        },
    });
    var thoigianvayrange = $("#thoigianvayrange").data("ionRangeSlider");
    $("#thoigianvayinput").on("keyup change" ,function () {
        if ($(this).val() > 20) $(this).val(20)
        if ($(this).val() < 1) $(this).val(1)
        thoigianvayrange.options.onChange({from: $(this).val()});
    })
    $("#thoigianvayinput").val(initThoiGianVay)

    $("#laisuatrange").ionRangeSlider({
        min: 0,
        max: 20,
        from: initLaiSuat,
        onChange: function (data) {
            $('#laisuatinput').val(data.from);
            setLaiCanTra(convertCurrencyToString($('#sotienvayinput').val()), $('#thoigianvayinput').val(), $('#laisuatinput').val(), this.value, '#laiCanTra', '#soTienTraThangDau', '#tongCong')
        }
    });
    var laisuatrange = $("#laisuatrange").data("ionRangeSlider");
    $("#laisuatinput").on("keyup change" ,function () {
        if ($(this).val() > 20) $(this).val(20)
        if ($(this).val() < 1) $(this).val(1)
        laisuatrange.options.onChange({from: $(this).val()});
    })
    $("#laisuatinput").val(initLaiSuat)

    $('#laiCanTra').on('DOMSubtreeModified', function () {
        updateChart(chart, getDataForChart())
    });
    $('#gocCanTra').on('DOMSubtreeModified', function (){
        updateChart(chart, getDataForChart())
        setLaiCanTra(convertCurrencyToString($('#sotienvayinput').val()), $('#thoigianvayinput').val(), $('#laisuatinput').val(), this.value, '#laiCanTra', '#soTienTraThangDau', '#tongCong')
    })
    $("input[type=radio][name=pay]:checked").each(() => {
        updateChart(chart, getDataForChart())
       setLaiCanTra(convertCurrencyToString($('#sotienvayinput').val()), $('#thoigianvayinput').val(), $('#laisuatinput').val(), this.value, '#laiCanTra', '#soTienTraThangDau', '#tongCong')
    }); // onload
    $('input[type=radio][name=pay]').on('change', function () {
        updateChart(chart, getDataForChart())
        setLaiCanTra(convertCurrencyToString($('#sotienvayinput').val()), $('#thoigianvayinput').val(), $('#laisuatinput').val(), this.value, '#laiCanTra', '#soTienTraThangDau', '#tongCong')
    })
</script>

<script type="text/javascript">
    $('#tab-switcher-1').addClass('active');
    $('#tab-switcher-2').removeClass('active');
    $('#tab-switcher-3').removeClass('active');
    $('#tab-1').removeClass('d-none');
    $('#tab-2').addClass('d-none');
    $('#tab-3').addClass('d-none');
    $('#tab-switcher-1').click(function () {
        $('#tab-switcher-1').addClass('active');
        $('#tab-switcher-2').removeClass('active');
        $('#tab-switcher-3').removeClass('active');
        $('#tab-1').removeClass('d-none');
        $('#tab-2').addClass('d-none');
        $('#tab-3').addClass('d-none');
    })
    $('#tab-switcher-2').click(function () {
        $('#tab-switcher-1').removeClass('active');
        $('#tab-switcher-2').addClass('active');
        $('#tab-switcher-3').removeClass('active');
        $('#tab-1').addClass('d-none');
        $('#tab-2').removeClass('d-none');
        $('#tab-3').addClass('d-none');
    })
    $('#tab-switcher-3').click(function () {
        $('#tab-switcher-1').removeClass('active');
        $('#tab-switcher-2').removeClass('active');
        $('#tab-switcher-3').addClass('active');
        $('#tab-1').addClass('d-none');
        $('#tab-2').addClass('d-none');
        $('#tab-3').removeClass('d-none');
    })
</script>


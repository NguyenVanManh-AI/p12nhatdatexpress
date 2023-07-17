@push('style')
{{-- <link rel="stylesheet" href="{{asset('system/plugins/ion-rangeslider/css/ion.rangeSlider.min.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/slick.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/plusb.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/responsive.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/responsiveb.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/responsiveh.css')}}"> --}}
<link rel="stylesheet" href="{{asset('system/css/project-view.css')}}">
@endpush

<div class="single-tabs">
    <div class="tab-switchers">
        <div class="switcher active" id="tab-switcher-1">Bản đồ</div>
        @if($project->video)
            <div class="switcher " id="tab-switcher-2">Video dự án</div>
        @endif
        @if($project->project_price > 0 && $project->bank_sponsor == 1)
            <div class="switcher" id="tab-switcher-3">Tính lãi suất</div>
        @endif
    </div>
    <div class="tabs p-0" id="vitri">
        <div class="tab2 d-none position-relative js-map-load-utilities" id="tab-1">
            {{-- <iframe class="mapparent"
                src="https://www.google.com/maps?q={{$project->map_latitude}},{{$project->map_longtitude}}&hl=vi&z=16&amp;output=embed"
                width="100%" height="400px" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>

            <input name="map-api" value="{!! getGoogleApiKey() !!}" type="hidden">
            <input name="latitude"  value="{{$project->map_latitude}}" type="hidden">
            <input name="longtitude"  value="{{$project->map_longtitude}}" type="hidden">
            <input name="full_address" value="{{ $project->getFullAddress() }}" type="hidden">

            <x-common.map-utilities /> --}}

            <x-common.loading class="inner map__load-utilities"/>

            <x-common.map
                id="project-detail-page__map"
                lat-name="latitude"
                long-name="longtitude"
                lat-value="{{ $project->map_latitude }}"
                long-value="{{ $project->map_longtitude }}"
                hide-hint
            />
            <x-common.map-utilities />
        </div>
        @if($project->video)
            <div class="tab2 d-none" id="tab-2">
                <iframe width="100%" height="600px" src="{{$project->video}}"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            </div>
        @endif

        @if($project->project_price > 0 && $project->bank_sponsor == 1)
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
            <div class="p-3">
                <div class="row m-0">
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
                            <input class="text-right pr-2 input-number-no-arrow" id="giatrinhadatinput" type="text" name="" value="0" disabled lang="en-ES"
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
                            <input class="text-right pr-2 input-number-no-arrow" id="sotienvayinput" type="text" name="" value="1500"
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
        @endif
    </div>
</div>

@push('scripts_children')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
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
        if (!$('#laiCanTra').html() || !$('#canTraTruoc').html() || !$('#sotienvayinput').val()) return
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
        number = (number+"").replace(/[,|.]/g,'')
        return number.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }
    function convertCurrencyToString(currency) {
        return currency.toString().replace(/[,|.]/g,'')
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
        soTienVay = convertCurrencyToString(soTienVay)
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
    initSoTienVay = formatCurrency(parseInt((initGiaTriNhaDat * ( initNganHangChoVay / 100))));
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
        from: convertCurrencyToString(initSoTienVay),
        onChange: function (data) {
            $('#sotienvayinput').val(formatCurrency((data.from).toFixed(0)));
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
@endpush

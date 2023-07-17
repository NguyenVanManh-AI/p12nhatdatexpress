@extends('Home.Layouts.Master')
@section('Title','Sự kiện | Nhà đất express')
@section('Keywords','Sự kiện | Nhà đất express')
@section('Description','Sự kiện | Nhà đất express')
@section('Image',url('frontend/images/home/image_default_nhadat.jpg'))
@section('Content')
    <div class="page-events">
        <div class="container">
            <div class="event-search">
                <div class="search-left js-select-near cursor-pointer">
                    <i class="far fa-compass"></i>
                    <span>Tìm gần tôi</span>
                </div>
                <div class="search-right group-load-address">
                    <form action="" method="get" id="form-search">
                        <x-common.select2-input
                            class="mb-0"
                            input-class="province-load-district"
                            name="province_id"
                            id="province"
                            placeholder="Tỉnh/Thành Phố"
                            :items="$provinces"
                            item-text="province_name"
                            items-current-value="{{ request()->province_id }}"
                            :show-error="false"
                        />

                        <x-common.select2-input
                            class="mb-0"
                            input-class="district-province"
                            name="district_id"
                            id="district"
                            placeholder="Quận/Huyện"
                            data-selected="{{ request()->district_id }}"
                            :show-error="false"
                        />

                        <div class="choose-time">
{{--                           <input placeholder="Thời gian" class="textbox-n" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="date" />--}}
{{--                            <input type="date">--}}
                            <input name="start_date" class="form-control float-right date_start" type="date" value="{{ \request()->query('start_date') ?? ""}}" />
{{--                            <div style="position: relative" onclick="hideTextDateStart()">--}}
{{--                                <div id="txtDateStart" style="width:200px; height: 22px;background: #fff;position: absolute;top: 9px;left: 9px">Từ ngày</div>--}}
{{--                                <input name="start_date" class="form-control float-right date_start" type="date" value="{{ \request()->query('start_date') ?? ""}}" />--}}
{{--                            </div>--}}
                        </div>
                        <input type="submit" value="Tìm kiếm">
                    </form>
                </div>
            </div>
        </div>

        <div class="list-event mb-2">
            <div class="container">
                <div class="row list_item {{$events->lastPage() == 1 ? 'mb-4' : ''}}">
                   @forelse($events as $item)
                        <x-home.event.item-event :item="$item"></x-home.event.item-event>
                    @empty
                       <div class="d-flex justify-content-center w-100">
                            <p class="py-5">Chưa có dữ liệu</p>
                       </div>
                    @endforelse
                </div>

                @if($events->lastPage() > 1)
                <x-home.layout.paginate :list="$events">
                    @slot('auto_load_page')
                        <div class="auto_load auto-paged">
                            <input type="checkbox" name="autoload" id="autoload" data-start="{{$num_collection['num_cur']}}">
                            <label for="autoload" class="m-0"><i class="fas fa-sync mr-2"></i>Tự động qua trang</label>
                        </div>
                    @endslot
                </x-home.layout.paginate>
                @endif

            </div>
        </div>
    </div>

@endsection

@section('Script')
    <script>
        $(function () {
            // Hidden Text date
            hiddenInputTextDate('#txtDateStart')

            // Click outer input auto load
            $('.auto_load').click(function () {
                $(this).find('input:checkbox').prop('checked', !$(this).find('input:checkbox').is(':checked')).trigger('change')
            })

            // Handle change input auto load
            $('#autoload').change(function () {
                $(this).parents('.auto_load').toggleClass('on');
                if(this.value){
                    no = 0;
                    let data_ajax = { _token: '{{csrf_token()}}' };
                    autoload('#autoload', '{{route('home.event.ajax_list')}}', data_ajax, '.list_item');
                }
            })

            // Handle scroll
            $(document).scroll(function (event) {
                let data_ajax = { _token: '{{csrf_token()}}' };
                autoload('#autoload', '{{route('home.event.ajax_list')}}', data_ajax, '.list_item');
            });
        })

        // Hide Text Date Start
        function hideTextDateStart(){
            $('#txtDateStart').hide();
        }

        // Call back Geo location
        async function callBackSetLocationAddress(result){
            isClicked = true;
            let province = await get_province_by_name(result.province_name, '{{route('param.get_province_name')}}' , '#province')
            await sleep(350)
            const district = await get_district_by_name( result.district_name, '{{route('param.get_district_name')}}', province['province'].id, '#district')
            {{--await sleep(500)--}}
            {{--const ward = await get_ward_by_name(result.ward_name, '{{route('param.get_ward_name')}}', district['district'].id, '#ward_search')--}}
            // await sleep(350)
            isClicked = false

            $('#form-search').trigger('submit');
        }
    </script>
@endsection

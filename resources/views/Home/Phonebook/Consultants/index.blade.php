@extends('Home.Layouts.Master')

@section('Content')
<div class="page-company pt-2">
		<div class="row main-company contact-page-home">
			<div class="col-md-3-7 sidebar-company">
				@include('Home.Phonebook.partials._search-form', [
                    'provinces' => $provinces
                ])
			</div>
			<div class="col-md-7-3 cs-list-item">
				<div class="row list-danhba list_item">
					@forelse ($consultants as $item)
					    <x-home.phone-book.consultant-item :item="$item"></x-home.phone-book.consultant-item>
					@empty
					    <p class="text-center mt-4 w-100">Chưa có dữ liệu</p>
					@endforelse
				</div>
				@if($consultants->lastPage() > 1)
				<x-home.layout.paginate :list="$consultants">
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
    // Callback map
    function callback(){
        // Init geocoder
        geocoder = new google.maps.Geocoder();
    }
    // Call back Geo location
    async function callBackSetLocationAddress(result){
        isClicked = true;
        let province = await get_province_by_name(result.province_name, '{{route('param.get_province_name')}}' , '#province')
        await sleep(350)
        const district = await get_district_by_name( result.district_name, '{{route('param.get_district_name')}}', province['province'].id, '#district')
        {{--await sleep(500)--}}
            {{--const ward = await get_ward_by_name(result.ward_name, '{{route('param.get_ward_name')}}', district['district'].id, '#ward_search')--}}
            await sleep(350)
        isClicked = false

        $('.search-company').trigger('submit');
    }

    {{--// Handle change input auto load--}}
    $('#autoload').change(function () {
        $(this).parents('.auto_load').toggleClass('on');
        if(this.value){
            no = 0;
            let data_ajax = {};
            autoload('#autoload', '{{route('home.ajax.chuyen-vien-tu-van')}}', data_ajax, '.list-danhba');
        }
    })

    // Handle scroll
    $(document).scroll(function (event) {
        let data_ajax = {};
        autoload('#autoload', '{{route('home.ajax.chuyen-vien-tu-van')}}', data_ajax, '.list_item');
    });
</script>
@endsection

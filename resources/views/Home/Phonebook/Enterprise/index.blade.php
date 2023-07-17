@extends('Home.Layouts.Master')

@section('Title', 'Danh bạ doanh nghiệp | Nhà đất express')

@section('Content')
<div class="page-company page-phone-custom pt-2">
		<div class="row main-company contact-page-home">
			<div class="col-md-3-7 sidebar-company" style="padding-left: 7.5px">
				{{-- <h3 class="search-near" style="cursor: pointer">Tìm gần tôi</h3> --}}
				@include('Home.Phonebook.partials._search-form', [
						'provinces' => $provinces
				])
{{--				<p class="desc">Bạn lo ngại chủ đầu tư và đơn vị phân phối không uy tín? </br> Bạn lo ngại khi giao dịch bất động sản và chọn mua dự án?</br>Nhà đất Express là website đầu tiên giúp người dùng tra cứu thông tin doanh nghiệp bất động sản trên môi trường online.</br>Truy cập doanh nghiệp để thấy hàng nghìn đánh giá hoặc gửi đánh giá để góp phần xây dựng cộng đồng bất động sản uy tín và chuyên nghiệp</p>--}}
			</div>
			<div class="col-md-7-3">
					<div class="row">
						<div class="col-md-12 list-company list_item js-enterprise-list-box">
							@forelse ($companies as $item)
								<x-home.phone-book.enterprise-item :item="$item" />
							@empty
					    	<p class="text-center mt-4 w-100">Chưa có dữ liệu</p>
							@endforelse
						</div>
						<div class="col-md-12">
							@if($companies->lastPage() > 1)
								<x-home.layout.paginate :list="$companies">
								@slot('auto_load_page')
									<div class="auto_load auto-paged">
										<input type="checkbox" name="autoload" id="autoload" data-start="{{$num_collection['num_cur']}}">
										<label for="" class="m-0"><i class="fas fa-sync mr-2"></i>Tự động qua trang</label>
									</div>
								@endslot
							</x-home.layout.paginate>
							@endif
						</div>
					</div>
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
            await sleep(350)
        isClicked = false

        $('.search-company').trigger('submit');
    }

    {{--// Handle change input auto load--}}
    $('#autoload').change(function () {
        $(this).parents('.auto_load').toggleClass('on');
        if(this.value){
            no = 0;
						let data_ajax = {}
						autoload('#autoload', '{{route('home.ajax.doanh-nghiep')}}', data_ajax, '.list_item');
        }
    })

    // Handle scroll
    $(document).scroll(function (event) {
				let data_ajax = {}
        autoload('#autoload', '{{route('home.ajax.doanh-nghiep')}}', data_ajax, '.list_item');
    });
</script>
@endsection

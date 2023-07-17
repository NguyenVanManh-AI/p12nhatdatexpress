<form class="search-location" action="{{route('param.set-location')}}" method="post">
    @csrf
    <input name="province_location" value="" type="hidden">
    <input name="district_location" value="" type="hidden">
    <input name="lat_location" value="" type="hidden">
    <input name="lng_location" value="" type="hidden">
    <input name="accept_location" value="0" type="hidden">
</form>

@push('scripts_children')

<script>
    $(function () {
        $('.search-near').click(function () {
            $('.loader').addClass('d-flex')
            if($(this).has('.accept-location')){
                $('input[name="accept_location"]').val(1);
            }
            getMyLocationToAddress();
        });
        $('.disable-search-near').on('click',function (){
            // $('.loader').addClass('d-flex')
            let url = '{{route('param.remove-location')}}';
            window.location.href = url;
        });
    })

    async function callbackLocationToAddress(result){
        isClicked = true;
        let province = await get_province_by_name(result.province_name, '{{route('param.get_province_name')}}' , '#province')
        const district = await get_district_by_name( result.district_name, '{{route('param.get_district_name')}}', province['province'].id, '#district')
        await sleep(200)

        // should change to call ajax

        if(province['province']['id']){
            $('input[name="province_location"]').val(province['province']['id']);
        }
        if(district['district']['id']){
            $('input[name="district_location"]').val(district['district']['id']);
        }
        $('input[name="lat_location"]').val(result.latLng.lat);
        $('input[name="lng_location"]').val(result.latLng.lng);
        isClicked = false

        $('.search-location').submit();
    }
</script>
@endpush

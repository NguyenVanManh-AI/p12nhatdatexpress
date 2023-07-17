<div class="news">

    <div class="section-title">
        <h2 class="title">Tin mới</h2>
    </div>

    <div class="list-news">

        <div class="list_news">
            @foreach($list as $item)
                <x-home.focus.new-item :new="$item" />
            @endforeach
        </div>

        <div class="pb-4 pt-2">
            <div class="auto-paged see-more">
                <input type="checkbox" id="num_cur" data-start="{{$num_collection['num_cur']}}" style="display: none">
                <label for="autoload" class="m-0 font-weight-normal">Xem thêm</label>
            </div>
        </div>

    </div>

</div>
@push('scripts_children')
    <script>
        $('.see-more').click(function () {
            var num_cur = $('.see-more #num_cur').attr("data-start");
            $.ajax({
                type: "POST",
                url: "{{route('home.focus.ajax_new')}}",
                data: {
                    '_token' : "{{csrf_token()}}",
                    num_cur
                },
                beforeSend: function(){
                    is_loading = 1;
                },
                success: function(string){
                    output = JSON.parse(JSON.stringify(string));
                    if(output.html != ''){
                        $('.see-more #num_cur').attr("data-start", parseInt(num_cur) + parseInt(output.num));
                        $('.list_news').append(output.html);
                        is_loading = 0;
                    }else{
                        $('.see-more').remove();
                    }
                },
            }).fail(function () {
                $('.see-more').remove();
            })
        })
    </script>
@endpush

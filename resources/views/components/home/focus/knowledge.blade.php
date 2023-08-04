<div class="section knowledge w-100" id="knowledge">
    <div class="section-title">
        <h2 class="title">{{ data_get($group, 'group_name') }}</h2>
        {{-- <h2 class="title">Bất động sản</h2> --}}
        <div class="tab">
            @foreach($children_group as $g)
                <a href="{{url(request()->getPathInfo().'?knowledge='.$g->id)}}">{{$g->group_name}}</a>
            @endforeach
        </div>
    </div>

    @if($ads || $list->count() > 0)
    <div class="list-knowledge mb-4">
        <x-home.focus.knowledge-item :item="$ads" :group="$group"/>
        <div class="list_knowledge">
        @foreach($list as $item)
            <x-home.focus.knowledge-item :item="$item" :group="$group" />
        @endforeach
        </div>
    </div>
    <a href="{{ route('home.focus.list-children', $group->group_url) }}" class="btn btn-cyan w-100">
        Xem thêm
    </a>
    @else
        <div class="text-center p-5 mb-4">
            <p>Chưa có dữ liệu</p>
        </div>
    @endif
</div>

@push('scripts_children')
    <script>
        $(function () {
            // Handle change input auto load
            $('#autoload').change(function () {
                $(this).parents('.auto_load').toggleClass('on');
                if(this.value){
                    no = 0;
                    let data_ajax = { _token: '{{csrf_token()}}' };
                    autoload('#autoload', '{{route('home.focus.ajax_knowledge', $group->id)}}', data_ajax, '.list_knowledge');
                }
            })
            // Handle scroll
            $(document).scroll(function (event) {
                let data_ajax = { _token: '{{csrf_token()}}' };
                autoload('#autoload', '{{route('home.focus.ajax_knowledge', $group->id)}}', data_ajax, '.list_knowledge');
            });
        })
    </script>
@endpush

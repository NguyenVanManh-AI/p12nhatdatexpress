<div class="section furniture mb-4">
    <div class="section-title">
        <h2 class="title">{{$group->group_name}}</h2>
        <a href="{{route('home.focus.list-children', $group->group_url)}}" class="view-more">Xem thêm</a>
    </div>
    @if($list->count() > 0)
    <div class="row">
        @foreach($list->slice(0,2) as $item)
        <div class="col-md-4 mb-sm-0 mb-3">
            <x-home.focus.item
                :item="$item"
            />
        </div>
        @endforeach
        <div class="col-md-4 list-using">
            <div class="border h-100 p-2">
                @foreach($list->slice(2,7) as $item)
                    <div class="using">
                        <div class="icon">
                            <i class="fas fa-circle"></i>
                        </div>
                        <a href="{{ route('home.focus.detail', [$group->group_url, $item->news_url]) }}"
                            class="text-ellipsis ellipsis-2 ">
                            {{ $item->news_title }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
        <div class="text-center p-5">
            <p>Chưa có dữ liệu</p>
        </div>
    @endif
</div>

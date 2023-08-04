<div class="section property mb-4">
    <div class="section-title">
        <h2 class="title">Bất động sản</h2>
        @if(data_get($group, 'group_url'))
            <a href="{{ route('home.focus.list-children', data_get($group, 'group_url')) }}" class="custom-view-more">Xem thêm</a>
        @endif
    </div>
    @if(count($list))
        <div class="owl-carousel owl-custom-theme owl-hover-nav owl-nav-rounded owl-drag focus-property__carousel">
            @forelse($list as $item)
                <x-home.focus.item
                    :item="$item"
                />
            @empty
            @endforelse
        </div>
    @else
        <div class="text-center p-5">
            <p>Chưa có dữ liệu</p>
        </div>
    @endif
</div>

@push('script_children')
    <script>
        $(function () {
            $('.view-more').off('click');
        })
    </script>
@endpush 

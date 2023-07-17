<div class="same-category mb-4">
    <div class="section-title">

        <h2 class="title">Cùng chuyên mục</h2>

        <a href="{{route('home.focus.list-children', $group_url)}}" class="view-more">Xem thêm</a>

    </div>

    <div class="owl-carousel owl-custom-theme owl-hover-nav owl-nav-rounded owl-drag focus-property__carousel">
        @forelse($list as $item)
            <x-home.focus.item
                :item="$item"
            />
        @empty
            <div class="text-center p-5">
                <p>Chưa có dữ liệu</p>
            </div>
        @endforelse
    </div>
</div>

@push('scripts_children')
    <script>
        $(function () {
            $('.same-category .view-more').off('click')
        })
    </script>
@endpush

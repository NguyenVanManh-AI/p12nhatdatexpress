<section
    class="estate-news js-need-load-individual"
    data-individual-url="/classifieds/more-estate-news"
>
    <div class="widget-title">
        <h3>Tin rao nhà đất</h3>
        <x-common.accept-location
            :is-individual="true"
            disable-text="Quay lại"
            enable-btn-class="btn-orange h-100"
            disable-btn-class="btn-primary h-100"
        />
    </div>
    <div class="list_item property-list ">
        <div class="property-list-wrapper list-classified-estate">
            {{-- map popup for item list --}}
            <x-common.classified.map-popup />
            <div class="js-individual-list">
                @forelse($classified as $item)
                    <x-news.classified.item :item="$item" />
                @empty
                    <h6 class="text-center my-3 w-100">Chưa có dữ liệu</h6>
                @endforelse
            </div>
        </div>
    </div>

    <x-common.loading class="inner inner-block"/>

    <div class="list-more">
        <span class="show-more-estate js-individual-load-more {{ $onLastPage ? 'd-none' : '' }}">Xem thêm</span>
        <a href="{{route('home.classified.list','nha-dat-ban')}}" class="btn-blue btn-border-left"><img src="../frontend/images/icon-home.png"><span>Nhà đất bán</span></a>
        <a href="{{route('home.classified.list','nha-dat-cho-thue')}}" class="btn-green btn-border-right"><img src="../frontend/images/icon-money.png"><span>Nhà đất cho thuê</span></a>
    </div>
</section>

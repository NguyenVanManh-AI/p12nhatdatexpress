{{-- map popup for item list --}}
<x-common.classified.map-popup />

<div class="single-relate section js-need-load-individual"
    data-individual-url="{{ $individualUrl }}"
>
    <div class="head-divide">
        <div class="left">
            <h3>Tin đăng liên quan</h3>
        </div>
        <x-common.accept-location
            :is-individual="true"
            enable-btn-class="btn-orange h-100"
            disable-btn-class="btn-primary h-100"
        />
    </div>
    <div class="single-relate-list js-individual-list">
        @forelse($classifieds as $classified)
            <x-news.classified.item :item="$classified" />
        @empty
            <x-home.classified.add-classified-button />
        @endforelse
    </div>

    <x-common.loading class="inner inner-block"/>
{{--    <a href="#" class="single-relate-more">>> Xem thêm</a>--}}
</div>

@if(count($classified_slideshow))
<div class="mb-3">
    <section class="owl-carousel owl-hover-nav owl-drag classified-slide__carousel w-100">
        @foreach($classified_slideshow as $item)
            <x-classified.slider-item
                :item="$item"
                :show-view-tag="true"
            />
        @endforeach
    </section>
</div>
@endif

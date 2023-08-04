@if($list->count() !=0)
<div class="mb-5">
    <div class="head title-classified-watch mb-3">
        <h3>Tin đăng đã xem</h3>
    </div>

    <div class="owl-carousel owl-hover-nav owl-drag classified-slide__carousel w-100">
        @foreach($list as $item)
            <x-classified.owl-carousel-item :item="$item"/>
        @endforeach
    </div>
</div>
@endif
<style>
    .slick-track{
        /*height: 400px;*/
    }
    .classified-curent .item{
        position: relative;
        /* height: 350px; */
        border: 1px solid rgba(222, 222, 222, 0.58);

    }
    .classified-curent .item .thumbnail{
        max-height: 50%;
        height: 50%;
        /*padding: 1px;*/
    }
    .classified-curent .item .desc{
        border: none;
    }
    .classified-curent .item .desc .title a{
        font-size: 14px;
    }
    .classified-curent .item .thumbnail img{
     height: 100%;
     object-fit: cover;
    }
</style>

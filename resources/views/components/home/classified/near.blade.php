<div class="near widget-height section c-mb-10">
    <div class="near-head head-center">
        <h3>
            {{$group==2?"Nhà đất bán":""}}
            {{$group==10?"Nhà đất cho thuê":""}}
            {{$group==18?"Cần mua - cần thuê":""}}
            gần đây</h3>
    </div>
    <div class="place">
        <span class="js-search-near button"><i class="fas fa-map-marker-alt"></i> Vị trí của tôi</span>
        <span class="note">Cần xác định vị trí để hiển thị chính xác thông tin</span>
    </div>
    <div class="near-list">
       @foreach($list as $i)
        <x-home.classified.near-item  :item="$i" />
        @endforeach
    </div>
</div>

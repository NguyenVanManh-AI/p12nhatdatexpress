<div class="near section c-mb-10">
    <div class="near-head head-center">
        <h3>{{ data_get($group, 'group_name', 'Dự án') }} gần đây</h3>
    </div>
    <div class="place" id="place_box">
        {{-- maybe need check location session --}}
        <span class="button js-search-near">
            <i class="fas fa-map-marker-alt"></i>
            Vị trí của tôi
        </span>
       <span class="note">Cần xác định vị trí để hiển thị chính xác thông tin</span>
    </div>
    <div class="near-list" id="append-near-project">
        @forelse($list as $item)
            <x-home.project.near-item :item="$item" />
        @empty
            <p class="d-flex justify-content-center align-items-center">Chưa có dữ liệu</p>
        @endforelse
    </div>
</div>

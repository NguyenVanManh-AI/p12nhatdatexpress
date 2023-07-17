<div class="glbtags global-tag-section border">
  <div class="global-tags fs-13 overflow-hidden bg-gray-f1 d-flex flex-wrap">
    @foreach ($provinces as $province)
      <div class="global-tag tag-item flex-center p-1">
        {{-- <a class="text-dark text-ellipsis w-100 fs-13" href="{{ route('home.classified.list', ['group_url' => 'nha-dat-ban', 'province_id' => $item->id]) }}"> --}}
        <a class="text-dark text-ellipsis w-100 fs-13" href="{{ route('home.location.province-classified', ['province_url' => $province->province_url]) }}">
          Nhà đất
          <strong>{{ $province->province_name }}</strong>
        </a>
      </div>
    @endforeach
  </div>

  <div class="show-more">
    <a href="javascript:void(0);">Hiện thêm</a>
  </div>
</div>

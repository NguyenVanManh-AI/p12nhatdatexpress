@if($haveAccess)
  @if($isButton)
    <a href="{{ $url }}" class="btn btn-sm btn-info mb-2 mr-2" title="Chỉnh sửa">
      <i class="fas fa-edit"></i>
    </a>
  @else
    <div class="ml-2 text-left">
      <span class="icon-small-size mr-1 text-dark">
        <i class="fas fa-cog"></i>
      </span>
      <a href="{{ $url }}" class="text-primary" title="Chỉnh sửa">Chỉnh sửa</a>
    </div>
  @endif
@endif

@if($haveAccess)
  @switch($action)
    @case(\App\Enums\AdminActionEnum::UPDATE)
      @if($isButton)
        <a href="{{ $url }}" class="btn btn-sm btn-info mb-2 mr-2" title="Chỉnh sửa">
          <i class="fas fa-edit"></i>
        </a>
      @else
        <div class="ml-2 text-left">
          <span class="icon-small-size mr-1 text-dark">
            <i class="fas fa-cog"></i>
          </span>
          <a href="{{ $url }}" class="link-flat" title="Chỉnh sửa">Chỉnh sửa</a>
        </div>
      @endif
      @break
    @case(\App\Enums\AdminActionEnum::DUPLICATE)
      @if($isButton)
        <div class="ml-2 text-left">
          <form action="{{ $url }}" class="d-inline-block" method="POST">
            @csrf
            <button type="button" class="btn btn-sm btn-info mb-2 mr-2 submit-accept-alert"
              data-action="nhân bản" title="Nhân bản">
              <i class="fas fa-copy"></i>
            </button>
          </form>
        </div>
      @else
        <div class="text-left ml-2">
          <form action="{{ $url }}" class="d-inline-block" method="POST">
            @csrf
            <button type="button" class="btn btn-link link-flat btn-sm p-0 submit-accept-alert"
              data-action="nhân bản" title="Nhân bản">
              <span class="icon-small-size mr-1 text-dark">
                <i class="fas fa-copy"></i>
              </span>
              Nhân bản
            </button>
          </form>
        </div>
      @endif
      @break
    @case(\App\Enums\AdminActionEnum::DELETE)
      @if($isButton)
        <div class="ml-2 text-left">
          <form action="{{ $url }}" class="d-inline-block" method="POST">
            @csrf
            <button type="button" class="btn btn-sm btn-danger mb-2 mr-2 submit-accept-alert"
              data-action="xóa" title="Xóa">
              <i class="fas fa-trash"></i>
            </button>
          </form>
        </div>
      @else
        <div class="text-left ml-2">
          <form action="{{ $url }}" class="d-inline-block" method="POST">
            @csrf
            <button type="button" class="btn btn-link link-red-flat btn-sm p-0 submit-accept-alert"
              data-action="xóa" title="Xóa">
              <span class="icon-small-size mr-1 text-dark">
                <i class="fas fa-times"></i>
              </span>
              Xóa
            </button>
          </form>
        </div>
      @endif
      @break
    @case(\App\Enums\AdminActionEnum::RESTORE)
      @if($isButton)
        <div class="ml-2 text-left">
          <form action="{{ $url }}" class="d-inline-block" method="POST">
            @csrf
            <button type="button" class="btn btn-sm btn-success mb-2 submit-accept-alert" data-action="khôi phục"
              title="Khôi phục">
              <i class="fas fa-redo"></i>
            </button>
          </form>
        </div>
      @else
        <div class="text-left ml-2">
          <form action="{{ $url }}" class="d-inline-block" method="POST">
            @csrf
            <button type="button" class="btn btn-link link-flat btn-sm p-0 submit-accept-alert"
              data-action="khôi phục" title="Khôi phục">
              <span class="icon-small-size mr-1 text-dark">
                <i class="fas fa-redo"></i>
              </span>
              Khôi phục
            </button>
          </form>
        </div>
      @endif
      @break
    @case(\App\Enums\AdminActionEnum::FORCE_DELETE)
      @if($isButton)
        <div class="ml-2 text-left">
          <form action="{{ $url }}" class="d-inline-block" method="POST">
            @csrf
            <button type="button" class="btn btn-sm btn-danger mb-2 mr-2 submit-accept-alert"
              data-action="xóa hẳn" title="Xóa hẳn">
              <i class="fas fa-trash-alt"></i>
            </button>
          </form>
        </div>
      @else
        <div class="text-left ml-2">
          <form action="{{ $url }}" class="d-inline-block" method="POST">
            @csrf
            <button type="button" class="btn btn-link link-red-flat btn-sm p-0 submit-accept-alert"
              data-action="xóa hẳn" title="Xóa hẳn">
              <span class="icon-small-size mr-1 text-dark">
                <i class="fas fa-times"></i>
              </span>
              Xóa hẳn
            </button>
          </form>
        </div>
      @endif
      @break
    @default
  @endswitch
@endif

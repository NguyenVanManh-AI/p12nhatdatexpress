@if($haveAccess)
  @if($isForm)
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
@endif

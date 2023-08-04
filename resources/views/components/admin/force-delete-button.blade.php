@if($haveAccess)
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
@endif

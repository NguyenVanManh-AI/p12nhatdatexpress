@if($haveAccess)
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
@endif

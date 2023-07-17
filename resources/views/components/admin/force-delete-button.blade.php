@if($check_role == 1 || key_exists(7, $check_role))
  @if(!$isButton)
    <div class="mt-2 text-left">
      <a href="javascript:void(0);" class="mb-2 js-force-delete-button text-danger"
          data-url="{{ $url }}"
          title="Xóa hẳn"
          data-id="{{ $id }}">
        <i class="fas fa-trash-alt mr-1"></i>
        Xóa hẳn
      </a>
    </div>
  @else
    <div class="ml-2 text-left">
      <a href="javascript:void(0);" class="btn btn-sm btn-danger mb-2 mr-2 js-force-delete-button"
        data-url="{{ $url }}"
        title="Xóa hẳn"
        data-id="{{ $id }}">
      <i class="fas fa-trash-alt"></i>
    </a>
    </div>
  @endif
@endif

{{-- <form action="" class="d-inline-block" method="POST">
  @csrf
  <button type="button" class="btn btn-sm btn-danger mb-2 mr-2 submit-accept-alert"
    data-action="xóa hẳn" title="Xóa hẳn">
    <i class="fas fa-trash-alt"></i>
  </button>
</form> --}}

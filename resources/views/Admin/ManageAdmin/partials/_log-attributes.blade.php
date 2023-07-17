<div class="modal fade" id="manage-admin-history-attributes-{{ $history->id }}" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header flex-center bg-sub-main">
        <h4 class="modal-title text-white fs-18 p-0">Hành động: {{ $history->getActionLabel() }}</h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body text-left">
        <h6>Bảng:</h6>
        <p class="ml-4">
          {{ $history->historyable()->getRelated()->getTable() }} -
          {{ $history->historyable_id }}
        </p>
        <h6>Thuộc tính:</h6>
        <div class="ml-4">
          <div class="row">
            @foreach ($history->attributes as $column => $value)
              <div class="col-sm-6">
                <p>
                  <strong>
                    {{ $column }}:
                  </strong>
                  @if($column == 'updated_at' || $column == 'created_at')
                    {{ timestampToDateTime($value) }}
                  @else
                    {!! $value !!}
                  @endif
                </p>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">
          Đóng
        </button>
      </div>
    </div>
  </div>
</div>

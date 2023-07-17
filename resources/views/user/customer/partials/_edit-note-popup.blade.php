<div class="modal fade" id="customer-edit-note-{{ $customer->id }}" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header flex-center bg-sub-main">
        <h4 class="modal-title text-white fs-18 p-0">Chỉnh sửa ghi chú</h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('user.customers.update-note', $customer->id) }}">
        <div class="modal-body">
          <x-common.textarea-input
            name="note"
            rows="5"
            placeholder="Ghi chú"
            value="{{ $customer->note }}"
          />
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">
            Lưu
          </button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Đóng
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

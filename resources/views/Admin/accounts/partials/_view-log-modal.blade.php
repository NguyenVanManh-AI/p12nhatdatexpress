<div class="modal fade" id="admin-account__view-log-popup" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header flex-center bg-sub-main">
        <h4 class="modal-title text-white fs-18 p-0">Hoạt động</h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <x-common.loading class="inner" />

        <div class="admin-account__log-table">
          <div class="table table-responsive table-stripped">
            
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

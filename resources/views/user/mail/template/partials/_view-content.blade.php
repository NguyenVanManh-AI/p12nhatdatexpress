<div class="modal fade" id="user-mail-view-content-{{ $userMail->id }}" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header flex-center bg-sub-main">
        <h4 class="modal-title text-white fs-18 p-0">{{ $userMail->mail_header }}</h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p class="card-text view-mail-content-area">
          {!! $userMail->mail_content !!}
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">
          Đóng
        </button>
      </div>
    </div>
  </div>
</div>

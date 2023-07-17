<div class="modal fade" id="modalCreateNotify" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-modal="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    Tạo thông báo
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.notify.make_notify')}}" class="form form-chat form-popup" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="">Người nhận</label>
                        <select class="custom-select" name="user_id" required></select>
                    </div>
                    <div class="form-group">
                        <label for="">Loại thông báo</label>
                        <select name="mailbox_type" id="mailbox_type" class="custom-select" required>
                            <option value="">Chọn loại thông báo</option>
                            <option value="I">Thông báo tin đăng</option>
                            <option value="A">Thông báo tài khoản</option>
                            <option value="S">Thông báo từ hệ thống</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Tiêu đề</label>
                        <input type="text" name="mail_title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Nội dung</label>
                        <textarea name="mail_content" id="mail_content" cols="30" rows="6" class="form-control"></textarea>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-send btn-success mr-2">Tạo thông báo</button>
                        <button class="btn btn-cancel btn-danger">Hủy</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    //triggered when modal is about to be shown
    $('#modalCreateNotify').on('show.bs.modal', function(e) {

        //get data-id attribute of the clicked element
        var userId = $(e.relatedTarget).data('user-id');
        var username = $(e.relatedTarget).data('user-username');

        //populate the textbox
        $(e.currentTarget).find('select[name="user_id"]').empty().append(new Option(`[ID: ${userId}] ${username}`, userId, true));
    });
</script>


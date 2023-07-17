<div class="modal fade show position-absolute" id="modalSendMail2" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
     aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle10">
                    Gửi mail
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" class="form form-send-mail form-popup" method="post">
                    <div class="form-group">
                        <label for="">Người nhận</label>
                        <input type="text" class="form-control" required="">
                    </div>
                    <div class="form-group">
                        <label for="">Loại thông báo</label>
                        <select name="" class="custom-select" required="">
                            <option value="">Chọn loại thông báo</option>
                            <option value="">option</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Tiêu đề</label>
                        <input type="text" class="form-control" required="">
                    </div>
                    <div class="form-group">
                        <label for="">Nội dung</label>
                        <textarea name="" cols="30" rows="6" class="form-control"></textarea>
                    </div>
                    <div class="form-row align">
                        <div class="col-md-6 ass">
                            <button type="submit" class="btn btn-send btn-success">Send</button>
                        </div>
                        <div class="col-md-6 ass">
                            <button type="button" class="btn btn-cancel btn-danger" data-dismiss="modal">Cancal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

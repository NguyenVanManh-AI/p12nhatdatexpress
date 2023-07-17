<div id="preview" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header flex-center bg-sub-main">
        <h4 class="modal-title text-white fs-18">Xem trước</h4>
        <button type="button" class="close custom-close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <x-common.loading class="inner" />

        <div class="row">
            <div class="col-12">
                <div class="owl-carousel owl-custom-theme owl-dot-orange owl-hover-nav owl-nav-rounded owl-drag classified__preview-carousel w-100"></div>
                <div class="preview-content p-1 m-1">
                    <div class="preview__detail-box">
                    </div>
                    {{-- <div class="single-detail">
                        <p class="preview-title"></p>
                        <p class="preview-description"></p>
                        <div class="table-information table">
                            <div class="table-head">Thông tin chi tiết</div>
                            <div class="item cl-1 row-1">
                                <span class="node">Tin chính chủ</span>
                                <span class="name green preview-verify"></span>
                            </div>
                            <div class="item cl-1 row-2">
                                <span class="node">Miễn môi giới</span>
                                <span class="name green preview-broker"></span>
                            </div>
                            <div class="item cl-1 row-3">
                                <span class="node">Thuộc dự án</span>
                                <span class="name blue preview-project"></span>
                            </div>
                            <div class="item cl-1 row-4">
                                <span class="node">Mô hình</span>
                                <span class="name red preview-paradigm"></span>
                            </div>
                            <div class="item cl-1 row-5">
                                <span class="node">Vị trí</span>
                                <span class="name preview-address-2"></span>
                            </div>
                            <div class="item cl-2 row-1">
                                <span class="node">Giá bán</span>
                                <span class="name red preview-price-2"></span>
                            </div>
                            <div class="item cl-2 row-2">
                                <span class="node">Diện tích</span>
                                <span class="name red preview-area-2"></span>
                            </div>
                            <div class="item cl-2 row-3">
                                <span class="node">Phòng ngủ</span>
                                <span class="name preview-bedroom"></span>
                            </div>
                            <div class="item cl-2 row-4">
                                <span class="node">Phòng vệ sinh</span>
                                <span class="name preview-toilet"></span>
                            </div>
                            <div class="item cl-2 row-5">
                                <span class="node">Tình trạng</span>
                                <span class="name preview-progress"></span>
                            </div>
                            <div class="item cl-3 row-1">
                                <span class="node">Hướng</span>
                                <span class="name blue preview-direction"></span>
                            </div>
                            <div class="item cl-3 row-2">
                                <span class="node">Pháp lý</span>
                                <span class="name">Sổ hồng</span>
                            </div>
                            <div class="item cl-3 row-3">
                                <span class="node">Nội thất</span>
                                <span class="name preview-furniture"></span>
                            </div>
                            <div class="item cl-3 row-4">
                                <span class="node">Đăng ngày</span>
                                <span class="name green preview-show-time"></span>
                            </div>
                            <div class="item cl-3 row-5">
                                <span class="node"></span>
                                <span class="name"></span>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
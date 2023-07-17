@extends('Home.Layouts.Master')
@section('Title', $popup_home->meta_title??'Trang chủ | Nhà đất express')
@section('Keywords',$popup_home->meta_key??'Trang chủ | Nhà đất express')
@section('Description',$popup_home->meta_desc??$popup_home->header_text_block)
@section('Image',asset('system/img/home-config/'.$popup_home->desktop_header_image??'frontend/images/home/image_default_nhadat.jpg'))
@section('Style')
    <link rel="stylesheet" href="{{asset('frontend/css/home.css')}}">
@endsection
@section('Content')
    {{-- <x-home.banner-top/> --}}
    <x-news.home.banner-top/>

    <x-home.urgent-sale/>
    <div class="row main-content">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-7-3">
                    <x-home.estate-news/>
                </div>
                <div class="col-md-3-7">
                    <x-home.event/>
                    <x-home.company/>
                    <x-home.consultants/>
                    <x-home.exchange/>
                    <x-home.survey/>
                </div>
            </div>
        </div>
    </div>
    <x-home.high-light-area/>
    <x-home.focus />

    <!-- /.content-wrapper -->
    <!-- Popup -->
    @section('popup')
        <div class="use-code-payment">
            <div class="logo-page-payment">
                <img src="/frontend/images/logo_modal.png" alt="">
                <div class="close-button-usecode"><i class="fas fa-times"></i></div>
            </div>
            <div class="content-fixel-payment">
                <div class="show-code-payment list">
                    <h5 class="code"><span class="title-cole">Làm mới tin:</span>FD325622</h5>
                    <span class="count-coin">-300 Coin</span>
                </div>
                <!-- <button class="btn reload-use-code-payment ">Làm mới</button> -->
                <a class="btn reload-use-code-payment ">Làm mới</a>
                <div class="button-reload-post">
                    <div class="holder holder-off">
                        <div class="under">
                            <div class="on">ON</div>
                            <div class="handler"></div>
                            <div class="off">OFF</div>
                        </div>
                    </div>
                    <div class="auto-reload-post">Tự động làm mới tin</div>
                </div>
                <div class="notication-reload-post">Tin rao vặt sẽ được làm mới sau 24h</div>
                <div class="price-reload-post">
                    <div class="price-reload-left">Phí làm mới tin thường: <span class="red-text">20 Coin</span></div>
                    <div class="price-reload-right">Phí làm mới tin Vip, Nổi bật: <span class="red-text">35 % phí đăng tin mới </span>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="modalNotify" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
             aria-modal="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- <h5 class="modal-title" id="exampleModalLongTitle">
                            Thông báo
                        </h5> -->
                        <img src="/frontend/images/logo_modal.png" alt="">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="form form-notify form-popup" method="post">
                            <div class="form-group">
                                <label for="">Người nhận</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">Loại thông báo</label>
                                <select name="" class="custom-select" required>
                                    <option value="">Chọn loại thông báo</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Tiêu đề</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">Nội dung</label>
                                <textarea name="" cols="30" rows="6" class="form-control"></textarea>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-send btn-success">Send</button>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-cancel btn-danger">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalUpdateProject" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
             aria-modal="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- <h5 class="modal-title" id="exampleModalLongTitle">
                            Chỉnh sửa dự án
                        </h5> -->
                        <img src="/frontend/images/logo_modal.png" alt="">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="form form-add form-popup" method="post">
                            <div class="form-group">
                                <label for="">Tình trạng</label>
                                <select name="" class="custom-select">
                                    <option value="">Chờ duyệt</option>
                                    <option value="">Đã duyệt</option>
                                    <option value="">Hết hạn</option>
                                    <option value="">Không duyệt</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Tiêu đề</label>
                                <input type="text" class="form-control"
                                       value="Đất nền Mallorca chỉ từ 13.5 triệu trên/m2 sổ đỏ trao tay">
                            </div>
                            <div class="form-group">
                                <label for="">Chuyên mục</label>
                                <select name="" class="custom-select">
                                    <option value="">Nhà đất bán</option>
                                    <option value="">Nhà đất cho thuê</option>
                                    <option value="">Dự án</option>
                                    <option value="">Cần mua</option>
                                    <option value="">Cần thuê</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Ngày hết hạn</label>
                                <input type="date" class="form-control" value="">
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-send btn-success">Lưu</button>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-cancel btn-danger">Hủy</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="recycle-bin" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
             aria-modal="false" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- <h5 class="modal-title" id="exampleModalLongTitle">
                            Thùng rác
                        </h5> -->
                        <img src="/frontend/images/logo_modal.png" alt="">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="list">
                            <?php for ($i = 1;
                                       $i < 12;
                                       $i++) { ?>
                            <div class="item">
                                <div class="name">
                                    Shantira Beach Resort & Spa
                                </div>
                                <div class="buttons">
                                    <a href="#" class="recycle-restore text-blue"><i
                                            class="fas fa-trash-restore-alt"></i>Khôi phục</a>
                                    <a href="#" class="recycle-remove text-red"><i class="fas fa-times"></i>Xóa</a>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection
    <!-- Mobile menu -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    <!-- ./wrapper -->
    @if($popup_home->popup_status==0 && $popup_home->popup_time_expire > \Carbon\Carbon::now())
    <div id="popup_home" class="popup">
        <a href="{{$popup_home->popup_link}}" target="_blank">
            <img src="{{ !empty($popup_home->popup_image) ? asset('system/img/home-config/'.$popup_home->popup_image) : asset('frontend/images/home/image_default_nhadat.jpg')}}">
        </a>
        <div class="close-button"><i class="fas fa-times"></i></div>
    </div>
    @endif
    {{--View map classified --}}
    <div id="location_classified"></div>
    {{--end viewmap--}}
@endsection

@section('Script')
    <script>
        $("body").on("click", ".popup .close-button", function (event) {
            event.preventDefault();
            let popup = $(this).parents(".popup");
            popup.hide();
            $("#layout").hide();
            $("#location_classified").empty()
        });

        $("body").on("click", "#layout", function (event) {
            event.preventDefault();
            $("#map-fixed").hide();
            // $('#popup_home').hide()
            $("#location_classified").empty()
        });

        $("body").on("click", ".location_classified", function (event) {
            event.preventDefault();
            viewMap($(this).data('id'));
        });

    </script>
    <script>
        $(document).ready(function () {
            //dd
            if (!localStorage) {
                console.log('Trình duyệt không hỗ trợ localStorage');
            }
            var check_popup = '{{$popup_home->popup_status??1}}';
            if (check_popup == '0') {
                var time_popup = '{{$time_popup}}';
                var data_popup = localStorage.getItem('time_data_popup');
                if (data_popup == null) {
                    $('#popup_home').show();
                    $("#layout").show();
                    localStorage.setItem('time_data_popup', {{time()+$time_popup}});
                } else {
                    var time = parseInt('{{time()}}');
                    var data = localStorage.getItem('time_data_popup');
                    if (time >= data) {
                        $('#popup_home').show();
                        $("#layout").show();
                        localStorage.setItem('time_data_popup', time + parseInt(time_popup));
                    }
                }
            }
            var name = localStorage.getItem('name');

        });

    </script>
@endsection

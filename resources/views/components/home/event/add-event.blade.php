<div id="create-event" class="popup add-event-popup-page js-has-map-address">
    <div class="title">
        Thêm sự kiện
    </div>

    <div class="wrapper">
        <form action="{{route('home.event.add')}}" method="post" enctype="multipart/form-data">
            @csrf
            @include('user.events._form', [
                'event' => new \App\Models\Event\Event,
                'provinces' => $provinces,
                'serviceFee' => $specialist_event_fee,
            ])

            <input type="submit" value="Gửi duyệt">
            <p class="last">* Sự kiện có thể được kiểm duyệt lên tới 48 giờ trước khi hiển thị trên website</p>
        </form>

        <div class="close-button"><i class="fas fa-times"></i></div>
    </div>
</div>

@push('scripts_children')
    <script>
        $(function () {
            // should change
            $("#event_title").on('change', function() {
                changeToSlug(this, '#event_url')
            })

            $("body").on("click", ".holder-custom", function (event) {
                // Early retun
                if( {{auth('user')->user()->coint_amount ?? 0 }} < {{$specialist_event_fee->service_fee}} ){
                    Swal.fire({
                        icon: 'error',
                        title: 'Không đủ coin',
                        text: 'Vui lòng nạp thêm coin để sử dụng tính năng này!',
                        confirmButtonColor: '#3085d6',
                    })
                    return;
                }

                event.preventDefault();
                if ($(this).hasClass("holder-off") == true) {
                    $(this).removeClass("holder-off");
                    $(this).addClass("holder-on");
                } else {
                    $(this).removeClass("holder-on");
                    $(this).addClass("holder-off");
                }

                $('#create-event #is_highlight').val($(this).hasClass('holder-on') ? 1 : 0)
            });

            $('#locationRequest select').change(function () {
                // if (!isClicked)
                setGoogleMap(
                    '#locationRequest #road',
                    '#locationRequest #ward',
                    '#locationRequest #district',
                    '#locationRequest #province',
                    '#locationRequest [name="address"]',
                )
            })
            // Handle change district get province
            $('#locationRequest #district').change( async function (){
                get_province_by_district(this, '#locationRequest select#province')
            })
            // Handle change address
            $('#locationRequest input[type="text"]').blur(function () {
                if ($(this).val() === '')
                    return;
                setGoogleMap(
                    '#locationRequest #road',
                    '#locationRequest #ward',
                    '#locationRequest #district',
                    '#locationRequest #province',
                    '#locationRequest [name="address"]',
                )
            })

            $('#create-event #event_title').keyup(function () {
                $('#create-event #meta_title').val($(this).val())
                $('#create-event #meta_keyword').html($(this).val())
            })

            // Custom js frontend
            // should change to global
            $('input[name="select_image_header_url"]').on('change', function (event) {
                // readURL(this, ".image_header_url");
                $(".image_header_url").parents(".upload-image").removeClass('opacity-hide');

                let file = this.files[0];

                if (file = this.files[0]) {
                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function () {
                        $('.upload-images .image_header_url').attr("src", reader.result)
                        $('.upload-images .banner-image').attr('href', reader.result)
                        $('.upload-images input[name="image_header_url"]').val(reader.result)
                    }
                }
            });
            $('input[name="select_image_invitation_url"]').on('change', function (event) {
                $(".image_invitation_url").parents(".upload-image").removeClass('opacity-hide');

                let file = this.files[0];
                if (file = this.files[0]) {
                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function () {
                        $('.upload-images .image_invitation_url').attr("src", reader.result)
                        $('.upload-images .invite-image').attr('href', reader.result)
                        $('.upload-images input[name="image_invitation_url"]').val(reader.result)
                    }
                }
            });

        })

        function addEvent(event) {
            @if(!auth('user')->check())
                event.preventDefault();
                toastr.error('Vui lòng đăng nhập');
            @elseif(auth('user')->user()->user_type_id == 3)
                $("#create-event").show();
                $("#layout").show();

                let initLocation = {
                    lat: parseFloat($('.add-event-popup-page [name="map_latitude"]').val() || 0),
                    lng: parseFloat($('.add-event-popup-page [name="map_longtitude"]').val() || 0),
                }

                initMap('event-form__map', '.add-event-popup-page [name="map_latitude"]', '.add-event-popup-page [name="map_longtitude"]', initLocation);
            @else
                Swal.fire({
                    icon: 'error',
                    title: 'Không thể thêm sự kiện',
                    text: 'Chỉ tài khoản doanh nghiệp mới thêm được sự kiện!',
                    confirmButtonColor: '#3085d6',
                })
            @endif
        }
    </script>
@endpush


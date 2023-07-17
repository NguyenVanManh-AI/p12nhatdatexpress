@extends('user.layouts.master')
@section('title', 'Chỉnh sửa sự kiện')

@section('content')
  <section class="content">
    <div class="container-fluid">
      {{-- should change --}}
      <div id="create-event"
        class="row add-event__page js-has-map-address"
        style="display: block; position: relative; width: 100% !important; transform: unset; top: 0 !important; left: 0">
        <div class="col-12 d-flex justify-content-center w-100">
          <div class="title" style="width: 98%">
            Sửa sự kiện
          </div>
        </div>
        <div class="col-12 wrapper">
          <form class="add-event__page" action="{{ route('user.events.edit', $event) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @include('user.events._form', [
              'event' => $event,
              'provinces' => $provinces,
              'serviceFee' => $serviceFee,
            ])

            <div class="text-center">
              <button class="btn btn-light-cyan px-4">Lưu</button>
            </div>
            <p class="last">* Sự kiện có thể được kiểm duyệt lên tới 48 giờ trước khi hiển thị trên website</p>
          </form>
        </div>
      </div>
    </div>
    <!-- Modal -->
    {{-- <div class="modal fade" id="modalCoverImage" tabindex="-1" role="dialog" aria-labelledby="modalFile"
      aria-hidden="true">
      <div class="modal-dialog modal-file" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Chọn ảnh</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <iframe
              src="{{ url('responsive_filemanager/filemanager/dialog.php') }}?multiple=0&type=1&field_id=image_header_url}}"
              frameborder="0" style="width: 100%; height: 70vh"></iframe>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalInvationImage" tabindex="-1" role="dialog" aria-labelledby="modalFile"
      aria-hidden="true">
      <div class="modal-dialog modal-file" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Chọn ảnh</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <iframe
              src="{{ url('responsive_filemanager/filemanager/dialog.php') }}?multiple=0&type=1&field_id=image_invitation_url}}"
              frameborder="0" style="width: 100%; height: 70vh"></iframe>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          </div>
        </div>
      </div>
    </div> --}}
  </section>
@endsection

@section('script')
  <script type="text/javascript" src="{{ asset('frontend/js/custom.js') }}"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key={!!$google_api_key!!}&callback=callback&libraries=places&v=weekly" defer></script>
  <script type="text/javascript" src="{{ asset('common/js/new-map.js') }}"></script>

  <script src="{{asset('user/js/classified.js')}}"></script>

  <script type="text/javascript">
      let initLocation = {
          lat: parseFloat($('.add-event__page [name="map_latitude"]').val()),
          lng: parseFloat($('.add-event__page [name="map_longtitude"]').val()),
      }

      function callback() {
          initMap('event-form__map', '.add-event__page [name="map_latitude"]', '.add-event__page [name="map_longtitude"]', initLocation);
      }
  </script>

  <script>
    let isClicked = false;

    // should change
    $("#event_title").on('change', function() {
      changeToSlug(this, '#event_url')
    })

    // // map - Chinh
    // $('#locationRequest input[type="text"]').blur(function() {
    //   if ($(this).val() === '') return;
    //   setGoogleMap('#road', '#ward', '#district', '#province')
    // })
    // $('#locationRequest select').change(function() {
    //   if (!isClicked)
    //     setGoogleMap('#road', '#ward', '#district', '#province')
    // })

    function changeImageHeader(element) {
      $('.image_header_url').prop('src', element.value)
    }

    function changeImageInvitation(element) {
      $('.image_invitation_url').prop('src', element.value)
    }

    $(function() {
      $('#event_title').keyup(function() {
        $('#meta_title').val($(this).val())
        $('#meta_keyword').html($(this).val())
      })

      @if (old('image_header_url') ?? $event->image_header_url)
        $('.image_header_url').prop('src', '{{ asset(old('image_header_url') ?? $event->image_header_url) }}')
      @endif

      @if (old('image_invitation_url') ?? $event->image_invitation_url)
        $('.image_invitation_url').prop('src',
          '{{ asset(old('image_invitation_url') ?? $event->image_invitation_url) }}')
      @endif

      @if (
          (old('province_id') || data_get($event->location, 'province_id')) &&
              (old('district_id') || data_get($event->location, 'district_id')))
        get_district('#province', '{{ route('param.get_district') }}', '#district',
          {{ old('province_id') ?? data_get($event->location, 'province_id') }},
          {{ old('district_id') ?? data_get($event->location, 'district_id') }})
      @endif

      $('#locationRequest select').change(function() {
        if (!isClicked)
          setGoogleMap('#address', '#ward', '#district', '#province')
      })

      // Handle change address
      $('#locationRequest input[type="text"]').blur(function() {
        if ($(this).val() === '')
          return;
        setGoogleMap('#address', '#ward', '#district', '#province')
      })
    });
  </script>

  <script>
    // Get content // Get content tinymce
    // getTinyContentWithoutHTML('event_content', 'blur', '#event_content')

    $(function() {
      $("body").on("click", ".holder-custom", function(event) {
        // Early retun
        if ({{ auth('user')->user()->coint_amount ?? 0 }} < {{ data_get($serviceFee, 'service_fee') }}) {
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

      $('#locationRequest select').change(function() {
        if (!isClicked)
          setGoogleMap('#locationRequest #road', '#locationRequest #ward', '#locationRequest #district',
            '#locationRequest #province')
      })
      // Handle change district get province
      $('#locationRequest #district').change(async function() {
        get_province_by_district(this, '#locationRequest select#province')
      })
      // Handle change address
      $('#locationRequest input[type="text"]').blur(function() {
        if ($(this).val() === '')
          return;
        setGoogleMap('#locationRequest #road', '#locationRequest #ward', '#locationRequest #district',
          '#locationRequest #province')
      })

      $('#create-event #event_title').keyup(function() {
        $('#create-event #meta_title').val($(this).val())
        $('#create-event #meta_keyword').html($(this).val())
      })

      // Custom js frontend
      $('input[name="select_image_header_url"]').on('change', function(event) {
        // readURL(this, ".image_header_url");
        $(".image_header_url").parents(".upload-image").removeClass('opacity-hide');

        let file = this.files[0];

        if (file = this.files[0]) {
          let reader = new FileReader();
          reader.readAsDataURL(file);
          reader.onload = function() {
            $('.upload-images .image_header_url').attr("src", reader.result)
            $('.upload-images .banner-image.js-fancy-box').attr('href', reader.result)
            $('.upload-images input[name="image_header_url"]').val(reader.result)
          }
        }
      });
      $('input[name="select_image_invitation_url"]').on('change', function(event) {
        $(".image_invitation_url").parents(".upload-image").removeClass('opacity-hide');

        let file = this.files[0];
        if (file = this.files[0]) {
          let reader = new FileReader();
          reader.readAsDataURL(file);
          reader.onload = function() {
            $('.upload-images .image_invitation_url').attr("src", reader.result)
            $('.upload-images .invite-image.js-fancy-box').attr('href', reader.result)
            $('.upload-images input[name="image_invitation_url"]').val(reader.result)
          }
        }
      });

      @if (old('province_id', data_get($event->location, 'province_id')) &&
              old('district_id', data_get($event->location, 'district_id')))
        isClicked = true;
        get_district('#province', '{{ route('param.get_district') }}', '#create-event #district',
          {{ old('province_id', data_get($event->location, 'province_id')) }},
          {{ old('district_id', data_get($event->location, 'district_id')) }})
        isClicked = false;
      @endif
    })
  </script>

  <script>
    @if (count($errors) > 0)
      toastr.error("Vui lòng kiểm tra các trường");
    @endif
  </script>

@endsection

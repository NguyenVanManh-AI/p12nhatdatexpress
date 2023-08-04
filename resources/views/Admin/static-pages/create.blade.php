@extends('Admin.Layouts.Master')
@section('Title', 'Thêm trang tĩnh | Trang tĩnh')
@section('Style')
  <style>
    /* checkbox */
    .form-control-409 {
      --form-control-color: #004680;
      font-size: 1.7rem;
    }

    .form-control-409 input {
      -webkit-appearance: none;
      appearance: none;
      background-color: #fff;
      margin: 0;
      font: inherit;
      color: currentColor;
      width: 0.8em;
      height: 0.8em;
      border: 2px solid #c3c3c6;
      border-radius: 0;
      transform: translateY(-.075em);
      display: grid;
      place-content: center
    }

    .form-control-409 input::before {
      content: "";
      width: .42em;
      height: .42em;
      transform: scale(0);
      transition: 120ms transform ease-in-out;
      box-shadow: inset 1em 1em var(--form-control-color);
    }

    .form-control-409 input:checked::before {
      transform: scale(1)
    }
  </style>
@endsection
@section('Content')
  <x-admin.breadcrumb active-label="Thêm trang tĩnh" :parents="[
      [
          'label' => 'Admin',
          'route' => 'admin.thongke',
      ],
      [
          'label' => 'Trang tĩnh',
      ],
      [
          'label' => 'Quản lý trang',
          'route' => 'admin.static.page.index',
      ],
  ]" />

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <form method="post" action="{{ route('admin.static.page.store') }}">
            @csrf
            @include('admin.static-pages.partials._form', [
              'item' => new \App\Models\StaticPage([]),
            ])

            <div class="form-group">
              <div class="d-flex justify-content-center my-2">
                <button class="btn btn-success mr-3">Hoàn tất</button>
                <input type="button" class="btn btn-outline-secondary" id="reset_btn" value="Làm lại" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFile" aria-hidden="true">
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
              src="{{ url('responsive_filemanager/filemanager/dialog.php') }}?multiple=0&type=1&field_id=image_url}}"
              frameborder="0" style="width: 100%; height: 70vh"></iframe>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('Script')
  <script>
    jQuery(document).ready(function($) {
      $(function() {
        // Reset form
        $('#reset_btn').click(function() {
          $('input[type="text"]').val('').attr('value', '')
          $('input[type="checkbox"]').prop('checked', false)
          $('.select2').prop('selectedIndex', 0).trigger('change');
          $('textarea').val('')
          tinymce.get('page_content').setContent('')
          // tinymce.get('page_description').setContent('')
          $('#page_description').val('')
          $('#google_url_seo').html("")
          $('#google_description_seo').html("")
          $('#google_title_seo').html("")
          updateValue();
          toastr.success('Làm mới thành công');
          var body = $("html, body");
          body.stop().animate({
            scrollTop: 0
          }, 500, 'swing');
        })
        // Reset form
        $('.clear-input').click(function() {
          $(this).parent().find('input').val('')
        })

        $('#page_title').keyup(function(e) {
          changeToSlug(this, '#page_url');
          $('#page_url').trigger('keyup')
          $('#meta_title').val($(this).val()).trigger('keyup')
          $('#meta_key').val($(this).val()).trigger('keyup')
        })

        $('#page_description').on('change keyup', function() {
          $('#meta_desc').val($(this).val()).trigger('change')
        })

        // getTinyContentWithoutHTML('page_description', 'blur', '.input-seo#meta_desc', 200)

        // page_url
        $('#page_url, #meta_title, #meta_desc').on('keyup change', function(e) {
          $(this).siblings().find('b.character_count').html(e.target.value.length)
          // page_url
          if ($(this).attr('id') === 'page_url') {
            $('#google_url_seo').html(e.target.value)
            $(this).blur(function() {
              $('#google_url_seo').html($(this).val())
            })
          }
          // meta_title
          else if ($(this).attr('id') === 'meta_title') {
            $('#google_title_seo').html(e.target.value)
            $(this).blur(function() {
              $('#google_title_seo').html($(this).val())
            })
          }
          // description
          else if ($(this).attr('id') === 'meta_desc') {
            $('#google_description_seo').html(e.target.value)
            $(this).blur(function() {
              $('#google_description_seo').html($(this).val())
            })
          }
        })
        updateValue();
      });
    });

    function updateValue() {
      $('#page_url, #meta_title, #meta_desc').each(function(e) {
        $(this).siblings().find('b.character_count').html($(this).val().length)
        // page_url
        if ($(this).attr('id') === 'page_url') {
          $('#google_url_seo').html($(this).val())
          $(this).blur(function() {
            $('#google_url_seo').html($(this).val())
          })
        }
        // meta_title
        else if ($(this).attr('id') === 'meta_title') {
          $('#google_title_seo').html($(this).val())
          $(this).blur(function() {
            $('#google_title_seo').html($(this).val())
          })
        }
        // description
        else if ($(this).attr('id') === 'meta_desc') {
          $('#google_description_seo').html($(this).val())
          $(this).blur(function() {
            $('#google_description_seo').html($(this).val())
          })
        }
      })
    }
  </script>
  <script>
    // toastr.options = {
    //     "preventDuplicates": true
    // }
    @if (count($errors) > 0)
      toastr.error("Vui lòng kiểm tra các trường");
    @endif
  </script>
@endsection

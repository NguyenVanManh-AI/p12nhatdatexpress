@extends('Admin.Layouts.Master')

@section('Title', 'Cập nhật | Tiêu điểm')

@section('Style')
  <link rel="stylesheet" type="text/css" href="{{ asset('system/css/admin-project.css') }}">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/css/selectize.default.min.css'>
@endsection

{{-- old code should check change --}}
@section('Content')
  <section>
    <x-admin.breadcrumb
      active-label="Chỉnh sửa tiêu điểm"
      :parents="[
        [
          'label' => 'Admin',
          'route' => 'admin.thongke'
        ],
        [
          'label' => 'Tiêu điểm',
          'route' => 'admin.news.index'
        ]
      ]"
    />

    <form action="{{ route('admin.news.update', $news) }}" method="post"
      enctype="multipart/form-data">
      @csrf
      <div class="row m-0 px-2 pb-3">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
          <div class="row">
            <div class="col-12 pb-3">
              <label>Tiêu đề <span class="required"></span></label>
              <input id="news_title" value="{{ old('news_title') ?? $news->news_title }}" class="form-control"
                type="text" name="news_title" required>
              @if ($errors->has('news_title'))
                <small class="text-danger">
                  {{ $errors->first('news_title') }}
                </small>
              @endif
            </div>
            <div class="col-12">
              <label>Video bài đăng</label>
              <input value="{{ old('video_url') ?? $news->video_url }}" class="form-control" type="text"
                name="video_url" placeholder="Nhập đường dẫn video youtube">
              @if ($errors->has('video_url'))
                <small class="text-danger">
                  {{ $errors->first('video_url') }}
                </small>
              @endif
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
          <x-common.textarea-input
            label="Tóm tắt"
            name="news_description"
            value="{{ old('news_description', $news->news_description) }}"
            rows="4"
            input-class="h-117px"
            placeholder="Tóm tắt..."
            required
          />
        </div>

        <div class="col-md-6 p-2">
          <x-common.select2-input label="Danh mục" name="group_id" :items="$groups" item-text="group_name"
            placeholder="Chọn danh mục" items-current-value="{{ $news->group_id }}" required />
        </div>

        <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
          <label>Thẻ tag</label>
          <input type="text" id="input-tags" value="{{ old('news_tag') ?? $news->tag_list }}" name="news_tag">
          @if ($errors->has('news_tag'))
            <small class="text-danger">
              {{ $errors->first('news_tag') }}
            </small>
          @endif
        </div>

        <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
          <div class="">
            <label>Audio</label>
            <div class="d-flex w-70">
              <audio class="mr-3" id="audio" controls style="height: 38px;width: 70%">
                <source src="{{ url('system/audio/news/' . $news->audio_url) }}" id="src" />
              </audio>
              <div class="button-choose-image text-center" style="width: 30%;border:1px solid #ccc">
                <p>Chọn file</p>
                <input type="file" name="audio_url" accept="audio/*" id="upload" style="width: 100%">
              </div>
            </div>
            @if ($errors->has('audio_url'))
              <small style="font-size: 100%" class="text-danger">
                {{ $errors->first('audio_url') }}
              </small>
            @endif
          </div>
        </div>

        <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
          <div class="row">
            <div class="col-lg-10 col-md-12">
              <label>Ảnh đại diện <span class="required"></span></label><br>
              <div class="input-group">
                <div style="position: relative;width: 100%">
                  <div class="input-group-prepend clear-input h-100" style="cursor: pointer;position: absolute"
                    onclick="resetValue()">
                    <label class="input-group-text" for="validatedInputGroupSelect">
                      <img src="images/icons/icon_clear.png">
                    </label>
                  </div>
                  <div class="input-group-prepend h-100" data-toggle="modal" data-target="#modalFile"
                    style="cursor: pointer;position: absolute;left: calc(100% - 100px);">
                    <label class="input-group-text" style="cursor: pointer;border-radius: 0 !important; ">
                      <span style="font-weight:300">Chọn hình</span>
                    </label>
                  </div>
                  <input class="form-control pl-5" id="image_url" name="image_url"
                    onchange="previewOneImgFromInputText(this,'#add-image')" style="width: 100%;border-radius: .25rem">
                </div>
              </div>
              @if ($errors->has('image_url'))
                <small style="font-size: 100%" class="text-danger">
                  {{ $errors->first('image_url') }}
                </small>
              @endif
            </div>

            <div class="col-lg-2 col-md-6">
              <label>&nbsp;</label>
              <br />
              <div class="form-row add-gallery justify-content-center" id="add-image"
                style="border:1px solid #ccc;object-fit: cover;
                            height: 39px; border-radius: 5px">
              </div>
            </div>
          </div>
        </div>

        <div class="col-12  p-2">
          <label>Nội dung <span class="required"></span></label>
          <textarea class="js-admin-tiny-textarea" id="news_content" name="news_content" style="width: 100%;height: 300px">{{ old('news_content') ?? $news->news_content }}</textarea>
          @if ($errors->has('news_content'))
            <small class="text-danger">
              {{ $errors->first('news_content') }}
            </small>
          @endif
        </div>

        <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
          <label>Từ khóa <span class="required"></span></label>
          <input value="{{ old('meta_key') ?? $news->meta_key }}" class="seo form-control" id="meta_key"
            type="text" name="meta_key" placeholder="Nhập từ khóa ...">
          @if ($errors->has('meta_key'))
            <small class="text-danger">
              {{ $errors->first('meta_key') }}
            </small>
          @endif
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
          <label>Tiêu đề <span class="required"></span></label>
          <input value="{{ old('meta_title') ?? $news->meta_title }}" class="seo form-control meta_title"
            id="meta_title" type="text" name="meta_title" placeholder="Nhập tiêu đề ...">
          @if ($errors->has('meta_title'))
            <small class="text-danger">
              {{ $errors->first('meta_title') }}
            </small>
          @endif
        </div>

        <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
          <label>Đường dẫn <span class="required"></span></label>
          <input value="{{ old('news_url') ?? $news->news_url }}" class="seo form-control meta_url" id="news_url"
            type="text" name="meta_url" placeholder="Nhập đường dẫn tĩnh ...">
          @if ($errors->has('meta_url'))
            <small class="text-danger">
              {{ $errors->first('meta_url') }}
            </small>
          @endif
        </div>

        <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
          <label>Mô tả <span class="required"></span></label>
          <input value="{{ old('meta_desc') ?? $news->meta_desc }}" class="seo form-control" id="meta_desc"
            type="text" name="meta_desc" placeholder="Nhập mô tả ...">
          @if ($errors->has('meta_desc'))
            <small class="text-danger">
              {{ $errors->first('meta_desc') }}
            </small>
          @endif
          @if ($errors->has('meta_desc'))
            <small class="text-danger">
              {{ $errors->first('meta_desc') }}
            </small>
          @endif
        </div>
        <div class="col-6 mt-2">
          <button type="submit" class="btn btn-primary float-right" style="border-radius: 0;">Hoàn tất</button>
        </div>
        <div class="col-6 mt-2">
          <button type="button" class="btn btn-secondary" style="border-radius: 0;"
            onclick="window.location.reload()">Làm lại</button>
        </div>
      </div>
    </form>
  </section>
  <div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFile"
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
            src="{{ url('responsive_filemanager/filemanager/dialog.php') }}?multiple=0&multiple_selection=false&type=1&field_id=image_url&fldr={{ \Illuminate\Support\Facades\Auth::guard('admin')->id() }}/focus"
            frameborder="0" style="width: 100%; height: 70vh"></iframe>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content -->
@endsection
@section('Script')
  <script src='https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/js/standalone/selectize.min.js'></script>

  <script>
    @if (count($errors) > 0)
      toastr.error("Vui lòng kiểm tra các trường");
    @endif
    @if ($news->image_url)
      previewOneImgFromInputText('#image_url', '#add-image', '', '{{ $news->image_url }}')
    @endif

    @if (isset($news->image_ads_url) && $news->image_ads_url)
      previewOneImgFromInputText('#image_ads_url', '#add-image-ads', '', '{{ $news->image_ads_url }}')
    @endif

    @if (old('image_url'))
      previewOneImgFromInputText('#image_url', '#add-image', '', '{{ old('image_url') }}')
    @endif
    @if (old('image_ads_url'))
      previewOneImgFromInputText('#image_ads_url', '#add-imag-ads', '', '{{ old('image_ads_url') }}')
    @endif
  </script>

  {{-- load audio --}}
  <script type="text/javascript">
    function handleFiles(event) {
      var files = event.target.files;
      $("#src").attr("src", URL.createObjectURL(files[0]));
      document.getElementById("audio").load();
    }

    document.getElementById("upload").addEventListener("change", handleFiles, false);
  </script>
  <script>
    function toSlug(str) {
      // Chuyển hết sang chữ thường
      str = str.toLowerCase();

      // xóa dấu
      str = str
        .normalize('NFD') // chuyển chuỗi sang unicode tổ hợp
        .replace(/[\u0300-\u036f]/g, ''); // xóa các ký tự dấu sau khi tách tổ hợp

      // Thay ký tự đĐ
      str = str.replace(/[đĐ]/g, 'd');

      // Xóa ký tự đặc biệt
      str = str.replace(/([^0-9a-z-\s])/g, '');

      // Xóa khoảng trắng thay bằng ký tự -
      str = str.replace(/(\s+)/g, '-');

      // Xóa ký tự - liên tiếp
      str = str.replace(/-+/g, '-');

      // xóa phần dư - ở đầu & cuối
      str = str.replace(/^-+|-+$/g, '');

      // return
      return str;
    }

    $(function() {
      $('#news_title').keyup(function(e) {
        $('.meta_title').val(e.target.value).trigger('keyup')
        $('#meta_key').val(e.target.value)
      })

      $('.meta_title').on('keyup', function() {
        $('.meta_url').val(toSlug($('.meta_title').val()));
      });

      getTinyContentWithoutHTML('news_content', 'blur', '#meta_desc', 200)
    })
  </script>


  <script type="text/javascript">
    $("#input-tags").selectize({
      delimiter: ",",
      persist: false,
      plugins: ["remove_button"],
      create: function(input) {
        return {
          value: input,
          text: input
        };
      }
    });
  </script>
@endsection

<div class="row">
  <x-common.alert />
</div>
<div class="row">
  <div class="col-lg-6 col-md-12">
    <div class="form-group">
      <label for="action">Tên action <span class="text-danger">*</span></label>
      <input type="text" class="form-control" {{ isset($template) ? 'disabled' : '' }} name="template_action" id="action" value="{{ old('template_action', $template->template_action ?? '') }}">
      @if($errors->has('template_action'))
        <small class="text-danger">
          {{$errors->first('template_action')}}
        </small>
      @endif
    </div>
  </div>
  <div class="col-lg-6 col-md-12">
    <div class="form-group">
      <label for="title">Tiêu đề <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="template_title" id="title" value="{{ old('template_title', $template->template_title ?? '') }}">
      @if($errors->has('template_title'))
        <small class="text-danger">
          {{$errors->first('template_title')}}
        </small>
      @endif
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6 col-md-12">
    <div class="form-group">
      <label for="title_mail">Tiêu đề mail</label>
      <input type="text" class="form-control" name="template_mail_title" id="title_mail" value="{{ old('template_mail_title', $template->template_mail_title ?? '') }}">
      @if($errors->has('template_mail_title'))
        <small class="text-danger">
          {{$errors->first('template_mail_title')}}
        </small>
      @endif
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <div class="form-group">
      <label for="mail_content">Nội dung <span class="text-danger">*</span></label>
      <textarea class="js-admin-tiny-textarea" name="template_content">{!! old('template_content', $template->template_content ?? '') !!}</textarea>
      @if($errors->has('template_content'))
        <small class="text-danger">
          {{$errors->first('template_content')}}
        </small>
      @endif
    </div>
  </div>
</div>
<div class="row justify-content-center">
  <button type="submit" class="btn btn-success mr-3">Hoàn tất</button>
  <input type="reset" value="Làm lại" class="btn btn-outline-secondary text-dark" />
</div>
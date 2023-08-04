@extends('Admin.Layouts.Master')

@section('Title', 'Thêm bài viết khuyến mãi | Mã khuyến mãi')

@section('Style')
  <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection

@section('Content')
  <x-admin.breadcrumb active-label="Thêm bài viết khuyến mãi" :parents="[
      [
          'label' => 'Admin',
          'route' => 'admin.thongke',
      ],
      [
          'label' => 'Bài viết khuyến mãi',
          'route' => 'admin.promotion-news.index',
      ],
  ]" />

<section class="content">
  <div class="container-fluid">
    <div class="px-3 pb-3">
      <form action="{{ route('admin.promotion-news.store') }}" method="post" enctype="multipart/form-data"
        id="add-news-promotion">
        @csrf
        <div class="row p-3">
          @include('admin.promotion-news.partials._form')

          <div class="add-category col-12 p-0 mtdow10">
            <div class="row m-0 mt-3">
              <div class="col-6">
                <button class="btn btn-primary float-right" style="border-radius: 0;">Hoàn tất</button>
              </div>
              <div class="col-6">
                <button class="btn btn-secondary" style="border-radius: 0;" onclick="resetForm()">Làm lại</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@section('Script')
  <script type="text/javascript">
    imgInp.onchange = evt => {
      const [file] = imgInp.files
      if (file) {
        blah2.src = URL.createObjectURL(file)
        $('#blah').show();
      }
    }
  </script>
@endsection

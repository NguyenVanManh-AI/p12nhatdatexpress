@extends('Admin.Layouts.Master')

@section('Title', 'Sửa bài viết khuyến mãi | Mã khuyến mãi')

@section('Style')
  <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection

@section('Content')
  <x-admin.breadcrumb active-label="Chỉnh sửa bài viết khuyến mãi" :parents="[
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
        <form action="{{ route('admin.promotion-news.update', $promotion->id) }}" method="post" enctype="multipart/form-data" id="add-news-promotion">
          @csrf
          <div class="row p-3">
            @include('admin.promotion-news.partials._form')
          </div>
          <div class="text-center">
            <button class="btn btn-primary rounded-0">
              Hoàn tất
            </button>
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

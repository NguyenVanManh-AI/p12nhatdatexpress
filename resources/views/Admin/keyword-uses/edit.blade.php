@extends('Admin.Layouts.Master')
@section('Title', 'Chỉnh sửa từ khóa nổi bật')

@section('Content')
  <x-admin.breadcrumb
    active-label="Chỉnh sửa từ khóa nổi bật"
    :parents="[
      [
        'label' => 'Admin',
        'route' => 'admin.thongke'
      ],
      [
        'label' => 'Từ khóa nổi bật',
        'route' => 'admin.keywords.index'
      ]
    ]"
  />
  <section class="content pt-4">
    <div class="container-fluid">
      <form action="{{ route('admin.keywords.update', $keyword) }}" method="POST">
        @csrf
        @include('admin.keyword-uses.partials._form', [
          'keyword' => $keyword,
        ])

        <div class="text-center mt-4">
          <button class="btn btn-success px-4">Lưu</button>
        </div>
      </form>
    </div>
  </section>
@endsection


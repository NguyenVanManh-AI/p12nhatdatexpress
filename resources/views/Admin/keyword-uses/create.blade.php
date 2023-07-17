@extends('Admin.Layouts.Master')
@section('Title', 'Thêm từ khóa nổi bật')

@section('Content')
  <x-admin.breadcrumb
    active-label="Thêm từ khóa nổi bật"
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
      <form action="{{ route('admin.keywords.store') }}" method="POST">
        @csrf
        @include('admin.keyword-uses.partials._form', [
          'keyword' => $keyword,
        ])

        <div class="text-center mt-4">
          <button class="btn btn-success px-4">Thêm</button>
        </div>
      </form>
    </div>
  </section>
@endsection

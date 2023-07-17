@extends('Admin.Layouts.Master')
@section('Title', 'Chỉnh sửa SEO vị trí')

@section('Content')
  <x-admin.breadcrumb active-label="Chỉnh sửa SEO vị trí" :parents="[
      [
        'label' => 'Admin',
        'route' => 'admin.thongke',
      ],
      [
        'label' => 'SEO vị trí',
        'route' => 'admin.seo.provinces.index',
      ],
    ]"
  />
  <section class="content">
    <div class="container-fluid">
      <form action="{{ route('admin.seo.provinces.update', $province) }}" method="POST">
        @csrf
        @include('Admin.Seo.provinces.partials._form', [
          'province' => $province,
        ])

        <div class="text-center mt-4">
          <button class="btn btn-success px-4">Lưu</button>
        </div>
      </form>
    </div>
  </section>
@endsection


@extends('Admin.Layouts.Master')
@section('Title', 'Admin | Nhadatexpress')

@section('Content')
  <section class="content pt-4">
    <div class="container-fluid">
      <x-admin.breadcrumb
        active-label="Dashboard"
        :parents="[
          [
            'label' => 'Admin',
            'route' => 'admin.dashboard.index'
          ]
        ]"
      />
    </div>
  </section>
@endsection

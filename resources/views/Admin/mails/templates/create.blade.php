@extends('Admin.Layouts.Master')

@section('Title', 'Thêm mới mẫu mail | Cấu hình hệ thống')

@section('Content')
    <x-admin.breadcrumb active-label="Thêm mới mẫu mail" :parents="[
        [
            'label' => 'Admin',
            'route' => 'admin.thongke',
        ],
        [
            'label' => 'Mẫu mail',
            'route' => 'admin.templates.index',
        ],
    ]" />

    <section class="content">
        <div class="container-fluid">
            <form class="mb-3" action="{{route('admin.templates.store')}}" method="post">
                @csrf
                @include('Admin.SystemConfig.components._form')
            </form>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('Script')
    <script>
        @if(count($errors) > 0)
        toastr.error("Vui lòng kiểm tra các trường");
        @endif
    </script>
@endsection

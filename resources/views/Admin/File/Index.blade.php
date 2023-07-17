@extends('Admin.Layouts.Master')
@section('Content')
<section class="content">
    <div class="container-fluid">
        <iframe src="{{url('responsive_filemanager/filemanager/dialog.php')}}" frameborder="0" style="width: 100%; height: 88vh"></iframe>
    </div>
</section>
@endsection


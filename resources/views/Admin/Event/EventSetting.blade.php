@extends("Admin.Layouts.Master")
@section('Title', 'Thiết lập | Sự kiện')
@section('Content')
    <div class="card-header border-bottom mt-3" style="border-bottom: 0px !important">
        <h5 class="m-0 text-center font-weight-bold" >THIẾT LẬP</h5>
    </div>
    <ul class="list-group list-group-flush" style="overflow-x: hidden;">
    </ul>
@endsection
@section('Style')

@endsection
@section('Script')
    <script>
        @if(count($errors) > 0)
        toastr.error("Vui lòng kiểm tra các trường");
        @endif
    </script>

@endsection

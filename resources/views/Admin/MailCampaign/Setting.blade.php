@extends('Admin.Layouts.Master')

@section('Content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="{{route('admin.email-campaign.set-up')}}" method="post">
                        @csrf
                        @foreach($notes as $item)
                            <div class="form-group w-100 p-2 my-2">
                                <label for="">{{$item->config_name}}</label>
                                <textarea name="{{$item->config_code}}" id="{{$item->config_code}}" class="js-admin-tiny-textarea">{{$item->config_value}}</textarea>
                            </div>
                        @endforeach

                        <div class="form-group d-flex justify-content-center mb-4">
                            <button class="btn btn-primary no-border no-radius">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

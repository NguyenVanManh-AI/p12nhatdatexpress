@extends('Admin.Layouts.Master')

@section('Content')
    <section class="content">
        <div class="container-fluid">

                    <form action="" method="post">
                        @csrf
                        <div class="row">

                        @foreach($notes as $item)
                                <div class="col-4">
                            @if($item->config_code == 'C013')
                                <div class="form-group w-100 p-2 my-2">
                                    <label for="">{{$item->config_name}}</label>
                                    <select name="{{$item->config_code}}" class="form-control">
                                    @foreach($param['level'] as $i)
                                    <option {{$item->config_value == $i->id?"selected":""}} value="{{$i->id}}">{{$i->level_name}}</option>
                                    @endforeach
                                </select>
                                </div>
                            @else
                                <div class="form-group w-100 p-2 my-2">
                                    <label for="">{{$item->config_name}}</label>
                                    <input class="form-control" name="{{$item->config_code}}" id="{{$item->config_code}}" value="{{$item->config_value}}">
                                </div>
                            @endif
                                </div>
                        @endforeach

                        </div>
                        <div class="form-group d-flex justify-content-center mb-4">
                            <button class="btn btn-primary no-border no-radius">Lưu</button>
                        </div>
                    </form>

        </div>
    </section>
@endsection

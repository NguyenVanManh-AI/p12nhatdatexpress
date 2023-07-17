@extends('Home.Layouts.Master')

@section('Title', $page->page_title)

@section('Content')
  <div class="pt-2">
    {{-- <h2 class="pt-3 w-90 pl-5 pr-5 text-center">{{$page->page_title}}</h2>
    <div class="image-large mr-auto text-center" style="margin: 0 auto">
      <img src="{{$page->image_url}}">
    </div> --}}
    {!! $page->page_content !!}
  </div>
@endsection

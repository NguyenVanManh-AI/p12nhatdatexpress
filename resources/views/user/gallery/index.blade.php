@extends('user.layouts.master')
@section('title', 'Thư viện')
@section('css')
    <style>
        iframe {
            box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;

        }
        iframe {
            min-height: calc(100vh - var(--header-mobile-height) - var(--footer-mobile-height) - 49px);
        }
        @media screen and (min-width: 768px) {
            iframe {
                min-height: calc(100vh - var(--header-height) - var(--footer-height) - 49px);
            }
        }
    </style>
@endsection
@section('content')
    <div class="library h-100" >
        <iframe src="{{ url('responsive_filemanager/filemanager/dialog.php') }}" frameborder="0" class="w-100"></iframe>
    </div>
@endsection

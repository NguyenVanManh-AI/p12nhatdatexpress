@extends('user.layouts.master')

@section('title', 'Tạo mail mới')

@section('content')
    <x-user.breadcrumb
        active-label="Tạo mail mới"
    />

    <div class="mail-configuration px-3 pb-3">
        <form action="{{route('user.post-template-mail')}}" method="post">
            @csrf
            @include('user.mail.template._form', [
                'template' => $template,
                'guide' => $guide
            ])
        </form>

        @include('user.mail.template._mail-list', [
            'temlate_mail' => $temlate_mail
        ])
    </div>
@endsection

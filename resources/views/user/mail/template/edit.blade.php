@extends('user.layouts.master')

@section('title', 'Sửa mẫu mail')

@section('content')
    <x-user.breadcrumb
        active-label="Sửa mẫu mail"
    />

    <div class="mail-configuration px-3 pb-3">
        <form action="{{route('user.post-edit-template-mail', $template->id)}}" method="post">
            @csrf
            @include('user.mail.template._form', [
                'template' => $template
            ])
        </form>

        @include('user.mail.template._mail-list', [
            'temlate_mail' => $temlate_mail
        ])
    </div>
@endsection

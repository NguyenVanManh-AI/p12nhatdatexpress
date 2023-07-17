@extends('user.layouts.master')
@section('content')
    <div class="p-2">
        <div class="col-12 bg-white p-3 mb-2">
            <h5>Chi tiết thư</h5>
        </div>
        <div class="col-12 bg-white p-3">
            <div class="mail-title ">
                <p class="text-bold text-break">{{$mailbox_detail->mail_title}} - <span
                        class="text-gray">{{ \App\Helpers\Helper::getHumanTimeWithPeriod($mailbox_detail->send_time) }}</span></p>
            </div>
            <div class="mail-content border-bottom text-break">
                {!! $mailbox_detail->mail_content !!}
            </div>
            <div class="text-right pt-2">
                <a href="{{URL::previous()}}" class="btn btn-express-search">Quay về</a>
            </div>
        </div>
    </div>
@endsection

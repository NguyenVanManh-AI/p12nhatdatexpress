<div class="social-network-history-account">
    <!-- <div class="title-history-update title-update-account"> -->
    <div class="title-update-account">
        <h5>Lịch sử cập nhật</h5>
    </div>
    <div class="history-update-content">
        <div class="list-item-history-update">
            <div class="item-history-update">
                @foreach($user_logs as $user_log)
                    <div class="content-item-history-update">
                        <div class="icon-left-history flex-center mr-2">
                            <i class="text-center rounded-circle {{ $user_log->log_icon }} p-0"></i>
                        </div>
                        <div class="right-history">
                            <p class="mr-4">{{$user_log->log_content}}  {{$user_log->log_message}}</p>
                            <p class="time-update-right text-right ml-5"><i class="far fa-clock "></i> {{date('H:i:s d/m/Y', $user_log->log_time)}}</p>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>

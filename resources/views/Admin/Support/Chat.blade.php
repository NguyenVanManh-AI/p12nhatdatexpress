@extends('Admin.Layouts.Master')
@section('Content')
    <h3 class=" text-center" style="padding: 5px">Hỗ trợ</h3>
    <div id="app">
        <div class="messaging">
            <div class="inbox_msg">
                <div class="inbox_people">
                    <div class="headind_srch">
                        <div class="recent_heading">
                            <h4>Lịch sử</h4>
                        </div>
                        <div class="srch_bar">
                            <div class="stylish-input-group">
                                <input type="text" id="keyword" class="search-bar" placeholder="&#xf002; Từ khóa tìm kiếm" style="font-family: Arial, 'FontAwesome'">
{{--                                <span class="input-group-addon">--}}
{{--                                    <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>--}}
{{--                                </span>--}}
                            </div>
                        </div>
                    </div>
                    <div class="inbox_chat">
                        @foreach($chat_session as $item)
                            <a href="{{route('admin.support.list',$item->chat_code)}}">
                                <div id="session-chat-{{$item->chat_code}}" @class(['chat_list', 'session-chat', 'active_chat' => $item->selected])>
                                    <div class="chat_people">
                                        <div class="chat_img"> <img src="{{asset($item->image_url??"")}}" alt="{{$item->fullname??""}}"> </div>
                                        <div class="chat_ib">
                                            <h5>{{$item->fullname??""}}
                                                @if($item->unread > 0)
                                                <span id="lasted-time-{{$item->chat_code}}" class="chat_date lasted-time">
                                                    <span class="badge badge-danger message-count" id="count-{{$item->chat_code}}">{{$item->unread}}</span>
                                                </span>
                                                @else
                                                <span id="lasted-time-{{$item->chat_code}}" class="chat_date lasted-time">
                                                    {{date('d/m/Y',$item->lasted_message['time'])}}
                                                </span>
                                                @endif
                                            </h5>
                                            <p id="lasted-message-{{$item->chat_code}}" class="lasted-message"> {{$item->lasted_message['type'] ? "Bạn: " : ''}} {{$item->lasted_message['message']}}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @if($chat_session->count() > 0)
                <div class="d-flex justify-content-between px-4 py-2" style="border-bottom: 1px solid #ccc">
                    <div class="d-flex align-items-center">
                        <img style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover" src="{{asset($selected->image_url??"")}}" alt="">
                        <h5 class="m-0 text-muted">{{$selected->fullname??""}}</h5>
                    </div>

                    <a v-if="!is_close" id="btnClose" href="{{route('admin.support.close', $chat_code)}}" class="btn btn-danger rounded d-flex justify-content-center align-items-center"><i class="fas fa-times font-weight-bold"></i> </a>

                </div>
                @endif
                <div class="mesgs">
                    <div class="msg_history" id="msg_history">
                        <div class="item" v-for="message in messages">
                            <div v-if="!message.type" class="incoming_msg mb-4">
                                {{-- <div class="incoming_msg_img"> <img style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover" src="{{asset($item->image_url)}}" alt="sunil"> </div> --}}
                                <div class="received_msg">
                                    <div class="received_withd_msg">
                                        <p>@{{ message.chat_message }}</p>
                                        <span class="time_date"> @{{ message.chat_time  }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="outgoing_msg" v-else>
                                <div class="sent_msg">
                                    <p>@{{ message.chat_message }}</p>
                                    <span class="time_date"> @{{ message.chat_time  }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-1" v-if="is_close">
                        <p style="color: rgba(0,0,0,0.51)">Cuộc hội thoại đã được kết thúc</p>
                    </div>

                </div>
                    <div class="type_msg">
                        <div class="input_msg_write">
                            <input id="inputMessage" v-model="message" @keyup.enter="sendMessage" type="text" class="write_msg" v-bind:disabled="is_close == 1" placeholder="Nhập nội dung" />
                            <button id="btnSend" @click="sendMessage" @class([ 'msg_send_btn' ]) type="button" :class="{'bg-gray' : is_close}"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('Style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet">

    <link href="{{asset('system/css/chat.css')}}" type="text/css" rel="stylesheet">
@endsection


@section('Script')
    <script src="//cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js" ></script>
    <script src="{{asset('system/js/notify.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/2.4.0/socket.io.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.0/echo.iife.js"></script>
    <script>
        new Vue({
            el: "#app",
            data() {
                return {
                    id: {{auth('admin')->id()}},
                    message: "",
                    is_close: {{ isset($is_end) ? (int)$is_end : false }},
                    messages: <?php echo json_encode($messages); ?> ,
                }
            },
            methods: {
                sendMessage() {
                    if(!this.is_close) {
                        axios.post('{{route('admin.support.store')}}', {
                            chat_message: this.message,
                            user_id: '{{$selected->user_id}}',
                            _token: '{{csrf_token()}}',
                            chat_code: {{$chat_code}},
                            type: 1,
                        }).then((result) => {
                            this.messages.push(result.data.message);
                            let time = result.data.message.chat_time
                            setTimeout(() => {
                                this.scrollEnd()
                                $(`#lasted-time-{{$chat_code}}`).html(time.split(" ")[1])
                                $(`#count-{{$chat_code}}`).remove()
                            }, 200)
                        })
                        this.message = ""
                    }
                },
                scrollEnd(){
                    var objDiv = document.getElementById("msg_history");
                    objDiv.scrollTop = objDiv.scrollHeight;
                },
                notifyMessage(){
                    playNotify('{{asset('system/audio/notify/messager.mp3')}}');
                }
            },
            mounted() {
                const echo = new Echo({
                    broadcaster: "socket.io",
                    host: window.location.hostname + ':6001'
                })

                echo.private("chat-session-{{$chat_code}}").listen('CloseSession', (event) => {
                    this.is_close = true
                })

                echo.private('chat-admin-{{auth('admin')->id()}}')
                    .listen('MessageSent', (event) => {

                        if (event.message.user_id == {{$selected->user_id}} && event.message.chat_code == {{$selected->chat_code}}){
                            this.messages.push(event.message);
                            setTimeout(() => {
                                this.notifyMessage()
                                this.scrollEnd()
                            }, 200)
                        }else{
                            let target = $('#session-chat-' + event.message.chat_code)
                            if(target.length > 0){
                                let count = target.find('.message-count').length + 1
                                target.find('.lasted-time').html(`<span class="badge badge-danger message-count">${count}</span>`)
                                target.find('.lasted-message').html(event.message.chat_message)
                            }else{
                                $('.inbox_chat').prepend(`
                                <a href="{{route('admin.support.list','')}}/${event.message.chat_code}">
                                    <div id="session-chat-${event.message.chat_code}" @class(['chat_list', 'session-chat'])>
                                        <div class="chat_people">
                                            <div class="chat_img"> <img src="${event.message?.user_detail?.image_url}" alt=""> </div>
                                            <div class="chat_ib">
                                                <h5>${event.message?.user_detail?.fullname}
                                                    <span id="lasted-time-${event.message.chat_code}" class="chat_date lasted-time">
                                                       <span class="badge badge-danger message-count" id="count-${event.message.chat_code}">1</span>
                                                    </span>
                                                </h5>
                                                <p id="lasted-message-${event.message.chat_code}" class="lasted-message"> ${event.message.chat_message}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                `)
                            }
                        }
                    });

                this.scrollEnd();
            },
        })

        $("#keyword").keyup(function () {
            $(".inbox_chat > a").hide().filter(":contains(" + $(this).val() + ")").show();
        });

        $('#btnClose').click(function (e) {
            e.preventDefault()
            let url = e.currentTarget.href
            Swal.fire({
                title: 'Xác nhận kết thúc',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(url, {
                        _token : '{{csrf_token()}}'
                    })
                }
            });
        })
    </script>
@endsection

@extends('user.layouts.master')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet" />
    <style>
        .container{max-width:1170px; margin:auto; border-radius: 5px}
        img{ max-width:100%;}
        .inbox_people {
            background: #f8f8f8 none repeat scroll 0 0;
            float: left;
            overflow: hidden;
            width: 40%; border-right:1px solid #c4c4c4;
        }
        .inbox_msg {
            border: 1px solid #c4c4c4;
            clear: both;
            overflow: hidden;
        }
        .top_spac{ margin: 20px 0 0;}

        .recent_heading {float: left; width:40%;}
        .srch_bar {
            display: inline-block;
            text-align: right;
            width: 60%;
        }
        .headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

        .recent_heading h4 {
            color: #05728f;
            font-size: 21px;
            margin: auto;
        }
        .srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
        .srch_bar .input-group-addon button {
            background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
            border: medium none;
            padding: 0;
            color: #707070;
            font-size: 18px;
        }
        .srch_bar .input-group-addon { margin: 0 0 0 -27px;}

        .chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
        .chat_ib h5 span{ font-size:13px; float:right;}
        .chat_ib p{ font-size:14px; color:#989898; margin:auto}
        .chat_img {
            float: left;
            width: 11%;
        }
        .chat_ib {
            float: left;
            padding: 0 0 0 15px;
            width: 88%;
        }

        .chat_people{ overflow:hidden; clear:both;}
        .chat_list {
            border-bottom: 1px solid #c4c4c4;
            margin: 0;
            padding: 18px 16px 10px;
        }
        .inbox_chat { height: 550px; overflow-y: scroll;}

        .active_chat{ background:#ebebeb;}

        .incoming_msg_img {
            display: inline-block;
            width: 6%;
        }
        .received_msg {
            display: inline-block;
            padding: 0 0 0 10px;
            vertical-align: top;
            width: 92%;
        }
        .received_withd_msg p {
            background: #ebebeb none repeat scroll 0 0;
            border-radius: 3px;
            color: #646464;
            font-size: 14px;
            margin: 0;
            padding: 5px 10px 5px 12px;
            width: 100%;
            word-break: break-word;
        }
        .time_date {
            color: #747474;
            display: block;
            font-size: 12px;
            margin: 8px 0 0;
        }
        .received_withd_msg { width: 57%;}
        .mesgs {
            float: left;
            padding: 30px 15px 0 25px;
            width: 100%;
        }

        .sent_msg p {
            background: #05728f none repeat scroll 0 0;
            border-radius: 3px;
            font-size: 14px;
            margin: 0; color:#fff;
            padding: 5px 10px 5px 12px;
            width:100%;
            word-break: break-word;
        }
        .outgoing_msg{ overflow:hidden; margin:26px 0 26px; padding-right: 12px}
        .sent_msg {
            float: right;
            width: 46%;
        }
        .input_msg_write input {
            background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
            border: medium none;
            color: #4c4c4c;
            font-size: 15px;
            min-height: 48px;
            width: 100%;
            outline: none;
            padding-right: 40px;
        }

        .type_msg {border-top: 1px solid #c4c4c4;position: relative;}
        .msg_send_btn {
            /*background: #05728f none repeat scroll 0 0;*/
            background: #21337f none repeat scroll 0 0;
            border: medium none;
            border-radius: 50%;
            color: #fff;
            cursor: pointer;
            font-size: 17px;
            height: 33px;
            position: absolute;
            right: 0;
            top: 11px;
            width: 33px;
        }
        /*.messaging { padding: 0 0 50px 0;}*/
        .msg_history {
            height: 600px;
            overflow-y: scroll;
            padding-bottom: 15px;
        }

        /*Rating*/
        .star-rating__checkbox {
            position: absolute;
            overflow: hidden;
            clip: rect(0 0 0 0);
            height: 1px;
            width: 1px;
            margin: -1px;
            padding: 0;
            border: 0;
        }
        .star-rating__star {
            display: inline-block;
            padding: 3px;
            vertical-align: middle;
            line-height: 1;
            font-size: 1.5em;
            color: #ababab;
            transition: color 0.2s ease-out;
        }
        .star-rating__star:hover {
            cursor: pointer;
        }
        .star-rating__star.is-selected {
            color: #ffd700;
        }
        .star-rating__star.is-disabled:hover {
            cursor: default;
        }
        /*Rating popup*/
        .rate {
            float: left;
            height: 46px;
            padding: 0 10px;
            flex-direction:row-reverse;
        }
        .rate:not(:checked) > input {
            position:absolute;
            top:-9999px;
        }
        .rate:not(:checked) > label {
            float:right;
            width:1em;
            overflow:hidden;
            white-space:nowrap;
            cursor:pointer;
            font-size:30px;
            color:#ccc;
        }
        .rate:not(:checked) > label:before {
            content: '★ ';
        }
        .rate > input:checked ~ label {
            color: #ffc700;
        }
        .rate:not(:checked) > label:hover,
        .rate:not(:checked) > label:hover ~ label {
            color: #deb217;
        }
        .rate > input:checked + label:hover,
        .rate > input:checked + label:hover ~ label,
        .rate > input:checked ~ label:hover,
        .rate > input:checked ~ label:hover ~ label,
        .rate > label:hover ~ input:checked ~ label {
            color: #c59b08;
        }
    </style>
@endsection
@section('content')
    <div id="app" class="container pt-1">
        <div class="d-flex justify-content-between align-items-center px-2">
            <div class="d-flex justify-content-start align-items-center">
                <img style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%" src="{{asset("system/img/avatar-admin/$admin->image_url")}}" alt=""><p class="m-0 mt-2 ml-2 py-3 font-weight-bold">{{ optional($admin)->admin_fullname }}</p>
            </div>
            <div class="d-flex align-items-center">
                <a href="{{route('user.support')}}" v-on:click.prevent="leaveSession($event)" class="text-danger"><i class="fas fa-times font-weight-bold"></i> </a>
            </div>
        </div>
        <div class="messaging">
            <div class="inbox_msg">
                <div class="mesgs">
                    <div class="msg_history" id="msg_history">
                        <div v-if="messages.length > 0">
                            <div v-for="message in messages">
                            <div v-if="message.type" class="incoming_msg">
                                <div class="incoming_msg_img">
                                    <img style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover" src="{{asset("system/img/avatar-admin/$admin->image_url")}}" alt="">
                                </div>
                                <div class="received_msg">
                                    <div class="received_withd_msg">
                                        <p>@{{ message.chat_message }}</p>
                                        <span class="time_date"> @{{ message.chat_time }}</span>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="outgoing_msg">
                                <div class="sent_msg">
                                    <p>@{{ message.chat_message }}</p>
                                    <span class="time_date"> @{{ message.chat_time }}</span>
                                </div>
                            </div>
                        </div>
                            <div class="text-center mb-1" v-if="is_close">
                                <p style="color: rgba(0,0,0,0.51)">Cuộc hội thoại đã được kết thúc</p>
                            </div>
                        </div>
                        <div v-else class="d-flex flex-column align-items-center justify-content-center">
                            <div class="no-post text-center d-flex flex-column align-items-center justify-content-center">
                                <img src="{{asset('frontend/images/profile/no-post.png')}}" alt="">
                                <div class="text-center text-muted">
                                    <h4 class="mt-2">Bạn cần hỗ trợ về vấn đề kỹ thuật ?</h4>
                                    <h5>Hãy nhập nội dung và nhấn gửi. Hỗ trợ viên sẽ hỗ trợ bạn sớm nhất có thể.</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="type_msg">
                        <div class="input_msg_write">
                            <input v-model="message" @keyup.enter="sendMessage" type="text" class="write_msg" :disabled="is_close == 1" placeholder="Nhập nội dung" />
                            <button @click="sendMessage" :class="{'bg-gray' : is_close}" class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottom-boxchat d-flex flex-column align-items-center justify-content-center py-3">
                <div v-if="messages.length > 0">
                    <span>Đánh giá cuộc hội thoại</span>
                    <div class="star-rating">
                        <label class="star-rating__star" v-for="rating in ratings" :class="{'is-selected': ((value >= rating) && value != null), 'is-disabled': disabled}"
                               v-on:click="set(rating)" v-on:mouseover="star_over(rating)" v-on:mouseout="star_out">
                            <input class="star-rating star-rating__checkbox" type="radio" v-on:click="changeRating($event)" :value="rating" :name="name" v-model="value" :disabled="disabled">★
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="//cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js" ></script>
    <script src="{{asset('system/js/notify.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/2.4.0/socket.io.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.0/echo.iife.js"></script>
    <script>
        function update_rating(value, is_end){
            axios.post('{{route('user.rating-session', $chat_code )}}', {
                rating: value,
                admin_id: '{{$admin->id}}',
                _token: '{{csrf_token()}}',
                chat_code: '{{$chat_code}}',
                is_end: is_end
            }).then((result) => {
                if(result.data.status == 'success'){
                    Swal.fire({
                        icon: 'success',
                        title: 'Đánh giá thành công'
                    })
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi không xác định'
                    })
                }
            })
        }
        new Vue({
            el: "#app",
            data() {
                return {
                    id: {{auth('user')->id()}},
                    message: "",
                    is_close: false,
                    messages: <?php echo json_encode($history); ?> ,

                    // rating
                    'name': 'rating',
                    'value': {{optional($history)[0]->rating ?? 0}},
                    'disabled': false,
                    'required': false,
                    temp_value: null,
                    ratings: [1, 2, 3, 4, 5]
                }
            },
            methods: {
                sendMessage() {
                    if(!this.is_close) {
                        axios.post('{{route('user.store-message')}}', {
                            chat_message: this.message,
                            admin_id: '{{$admin->id}}',
                            _token: '{{csrf_token()}}',
                            chat_code: {{$chat_code}},
                            type: 0,
                        }).then((result) => {
                            this.messages.push(result.data.message);
                            setTimeout(() => {
                                this.scrollEnd()
                            }, 200)
                        })
                        this.message = ""
                    }
                },
                changeRating(event){
                    update_rating(event.target.value, 0)
                },
                leaveSession(event){
                    event.preventDefault();
                    let url = event.target.parentNode.href;
                    if (!this.value && this.messages.length > 0){
                        Swal.fire({
                            title: 'Đánh giá cuộc hội thoại',
                            // allowOutsideClick: false,
                            html: `
                                <div class="d-flex justify-content-center">
                                    <div class="rate">
                                        <input type="radio" id="star5" name="rate" value="5" />
                                        <label for="star5" title="5 sao">5 stars</label>
                                        <input type="radio" id="star4" name="rate" value="4" />
                                        <label for="star4" title="4 sao">4 stars</label>
                                        <input type="radio" id="star3" name="rate" value="3" />
                                        <label for="star3" title="3 sao">3 stars</label>
                                        <input type="radio" id="star2" name="rate" value="2" />
                                        <label for="star2" title="2 sao">2 stars</label>
                                        <input type="radio" id="star1" name="rate" value="1" />
                                        <label for="star1" title="1 sao">1 star</label>
                                    </div>
                                </div>
                            `
                        }).then(async function (result) {
                            if (result.isConfirmed) {
                                await update_rating($('input[name="rate"]:checked').val(), 1)
                                window.location.href = url
                            }
                        })
                    }else{
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
                                window.location.href = url
                            }
                        });
                    }
                },
                /*
                 * Behaviour of the stars on mouseover.
                 */
                star_over: function(index) {
                    var self = this;

                    if (!this.disabled) {
                        this.temp_value = this.value;
                        return this.value = index;
                    }

                },

                /*
                 * Behaviour of the stars on mouseout.
                 */
                star_out: function() {
                    var self = this;

                    if (!this.disabled) {
                        return this.value = this.temp_value;
                    }
                },

                /*
                 * Set the rating.
                 */
                set: function(value) {
                    var self = this;

                    if (!this.disabled) {
                        this.temp_value = value;
                        return this.value = value;
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

                echo.private('chat-user-{{auth('user')->user()->user_code}}')
                    .listen('MessageSent', (event) => {
                        // console.log('RECIVED in user', event)
                        this.messages.push(event.message);
                        setTimeout(() => {
                            this.notifyMessage()
                            this.scrollEnd()
                        }, 200)
                    });
                this.scrollEnd();
            },
        })
    </script>
@endsection

@extends('user.layouts.master')
@section('title', 'Hỗ trợ kĩ thuật')

@section('content')
    <div class="technical-assistance">
        <h2 class="title text-center pt-2">Nhân viên chăm sóc khách hàng</h2>
        <div class="list-staff">
            @foreach($customer_care as $cc)
                <div class="item text-center">
                    <div class="avatar">
                        <a href="#"><img style="width: 120px;border-radius: 50%;height: 120px;object-fit: cover;" src="{{asset("system/img/avatar-admin/$cc->image_url")}}"></a>
                    </div> 
                    <div class="name">
                        <h3>{{$cc->admin_fullname}}</h3>
                    </div>
                    <div class="rating">
                        @if($cc->rating > 0)
                            @for($i = 1; $i <= $cc->rating; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        @else
                            @for($i = 1; $i <= 5; $i++)
                                <i class="far fa-star"></i>
                            @endfor
                        @endif
                    </div>
                    <div class="status">
                        <span @class([
                        'icon'
                        ]) id="status-admin-{{$cc->id}}"><i class="fas fa-circle"></i></span>
                        <span id="text-status-admin-{{$cc->id}}" class="info-status">Đang offline</span>
                    </div>
                    <div class="chat">
                        <a id="btn-chat-{{$cc->id}}" href="javascript:{}">Chat
                            <span class="icon-chat"><i class="far fa-comment-dots"></i></span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="customer-care">
            <div class="title-care text-center">
                <h4>Chat với nhân viên CSKH hoặc để lại thông tin theo mẫu dưới</h4>
                <h4 class="title-red">Hotline hỗ trợ 24/7: 090.999.2638</h4>
            </div>
            <form action="{{route('user.post-support')}}" method="post">
                @csrf
                <div class="select-help">
                    <p>Chọn mục cần hỗ trợ*</p>
                    <div>
                        <select name="mail_type" id="select-acc">
                            <option value="" disabled>Chọn danh mục hỗ trợ</option>
                            @foreach($mail_types as $key => $value)
                                @if(old('mail_type') == $key)
                                    <option value="{{$key}}" selected>{{$value}}</option>
                                @else
                                    <option value="{{$key}}">{{$value}}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="d-block">{{show_validate_error($errors, 'mail_type')}}</div>
                    </div>


                </div>

                <div class="care-question">
                    <div class="form-group">
                        <input type="text" name="mail_title" class="form-control mb-2" placeholder="Tiêu đề" value="{{old('mail_title')}}">
                        {{show_validate_error($errors, 'mail_title')}}
                        <textarea id="content-care" name="mail_content" class="form-control" placeholder="Nhập nội dung">{{old('mail_content')}}</textarea>
                        {{show_validate_error($errors, 'mail_content')}}
                    </div>

                    <div class="more-question">
                        <div class="content">
                            <ul>
                                <li>Thắc mắc của Khách hàng sẽ được giải đáp trong 24h.</li>
                                <li>Vui lòng kiểm tra <a class="italic-blue" href="{{ route('user.mailbox') }}">Hòm thư</a></li>
                            </ul>
                        </div>
                        <div class="send">
                            <input type="submit" class="btn btn-success" value="Gửi">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/2.4.0/socket.io.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.0/echo.iife.js"></script>
    <script>
        $(function (){
            const echo = new Echo({
                broadcaster: "socket.io",
                host: window.location.hostname + ':6001'
            })

            echo.join('online')
                .here((users) => {
                    users.map((user) => updateJoin(user))
                })
                .joining((user) => {
                    console.log('joining', user);
                    updateJoin(user)
                })
                .leaving((user) => {
                    console.log('leaving',user);
                    updateLeave(user)
                });
        })

        function updateJoin(user){
            $(`#status-admin-${user.id}`).addClass('active')
            $(`#text-status-admin-${user.id}`).html('Đang online')
            $(`#btn-chat-${user.id}`).addClass('active').prop('href', `{{route('user.generate-chat', ['', ''])}}/${user.id}`)
        }
        function updateLeave(user){
            $(`#status-admin-${user.id}`).removeClass('active')
            $(`#text-status-admin-${user.id}`).html('Đang offline')
            $(`#btn-chat-${user.id}`).removeClass('active').prop('href', 'javascript:{}')
        }
    </script>
@endsection

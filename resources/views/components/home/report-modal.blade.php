<div id="report_content" style="display: none" class="popup">
    <div class="wrapper">
        <div class="title">
            Báo cáo vi phạm
        </div>
        <form action="" method="POST">
            @csrf
            <div class="radio-list">
                @foreach($report_content as $key => $i)
                <div class="group">
                    <input {{$key == 0?"checked":""}} type="radio" value="{{$i->id}}" name="report_type" id="content{{$i->id}}">
                    <label for="content{{$i->id}}">{{$i->content}}</label>
                </div>
                @endforeach
                @if($errors->has('report_type'))
                    <small class="text-danger">
                        {{ $errors->first('report_type') }}
                    </small>
                @endif
            </div>

            <x-common.textarea-input
                name="report_content"
                rows="5"
                placeholder="Mô tả chi tiết"
                value="{{ old('report_content') }}"
            />

            <div class="mb-3">
                <x-common.captcha />
            </div>

            <div class="foot">
                <input type="submit" class="submit_report" value="Gửi">
            </div>
        </form>
        <i class="close-report fas fa-times"></i>
    </div>
</div>
<div id="report_comment" style="display: none" class="popup">
    <div class="wrapper">
        <div class="title">
            Báo cáo vi phạm bình luận
        </div>
        <form action="" method="POST">
            @csrf
            <div class="radio-list">
                @foreach($report_comment as $key=> $i)
                <div class="group">
                    <input  {{$key == 0?"checked":""}} type="radio" value="{{$i->id}}" name="report_type" id="comment{{$i->id}}">
                    <label for="comment{{$i->id}}">{{$i->content}}</label>
                </div>
                @endforeach
                @if($errors->has('report_type'))
                    <small class="text-danger">
                        {{ $errors->first('report_type') }}
                    </small>
                @endif
            </div>

            <x-common.textarea-input
                name="report_content"
                rows="5"
                placeholder="Mô tả chi tiết"
                value="{{ old('report_content') }}"
            />

            <div class="mb-3">
                <x-common.captcha />
            </div>

            <div class="foot">
                <input type="submit" class="submit_report" value="Gửi">
            </div>
        </form>
        <i class="close-report fas fa-times"></i>
    </div>
</div>
<div id="report_persolnal" style="display: none" class="popup">
    <div class="wrapper">
        <div class="title">
            Báo cáo vi phạm trang cá nhân
        </div>
        <form action="" method="POST">
            @csrf
            <div class="radio-list">
                @foreach($report_persolnal as $key=>$i)
                <div class="group">
                    <input  {{$key == 0?"checked":""}} type="radio" name="report_type" value="{{$i->id}}" id="persolnal{{$i->id}}">
                    <label for="persolnal{{$i->id}}">{{$i->content}}</label>
                </div>
                @endforeach
                @if($errors->has('report_type'))
                    <small class="text-danger">
                        {{ $errors->first('report_type') }}
                    </small>
                @endif
            </div>

            <x-common.textarea-input
                name="report_content"
                rows="5"
                placeholder="Mô tả chi tiết"
                value="{{ old('report_content') }}"
            />

            <div class="mb-3">
                <x-common.captcha />
            </div>

            <div class="foot">
                <input type="submit"  class="submit_report" value="Gửi">
            </div>
        </form>
        <i class="close-report fas fa-times"></i>
    </div>
</div>

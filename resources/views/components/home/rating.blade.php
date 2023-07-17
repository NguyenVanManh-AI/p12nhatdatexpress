<input type="hidden" id="passComponent" value="{{$ip}}">
<input type="hidden" id="request" value="{{ \request()->ip() }}">
<div class="project-review">
    <div class="head">
        <i class="fas fa-user-circle"></i>
        Đánh giá
    </div>
    <div class="review-result">
        <div class="result-compact">
            <div class="number">{{$avg_star}}</div>
            <div class="star">
                @for($i = 1; $i <= $avg_star; $i++)
                    <i class="fas fa-star"></i>
                @endfor
                @for($i = 5 - $avg_star; $i > 0; $i--)
                    <i class="far fa-star"></i>
                @endfor
            </div>
            <div class="review-count">
                {{$rate->count()}} đánh giá
                <input type="hidden" value="{{$rate->count()}}">
            </div>
        </div>
        <div class="result">
            <div class="result-group">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <div class="result-bar">
                    <div class="result-bar-inside"></div>
                </div>
                <span>{{$five_star}}</span>
                <input type="hidden" value="{{$five_star}}">
            </div>
            <div class="result-group">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i>
                <div class="result-bar">
                    <div class="result-bar-inside"></div>
                </div>
                <span>{{$four_star}}</span>
                <input type="hidden" value="{{$four_star}}">
            </div>
            <div class="result-group">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
                <div class="result-bar">
                    <div class="result-bar-inside"></div>
                </div>
                <span>{{$three_star}}</span>
                <input type="hidden" value="{{$three_star}}">
            </div>
            <div class="result-group">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
                <div class="result-bar">
                    <div class="result-bar-inside"></div>
                </div>
                <span>{{$two_star}}</span>
                <input type="hidden" value="{{$two_star}}">
            </div>
            <div class="result-group">
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
                <div class="result-bar">
                    <div class="result-bar-inside"></div>
                </div>
                <span>{{$one_star}}</span>
                <input type="hidden" value="{{$one_star}}">
            </div>
        </div>
        <div class="review-receiver text-center">
            <div class="head">Đánh giá</div>
            <div class="custom-rate">
                    <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="5 sao"></label>
                    <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 sao"></label>
                    <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 sao"></label>
                    <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 sao"></label>
                    <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 sao"></label>
            </div>
        </div>
    </div>
</div>

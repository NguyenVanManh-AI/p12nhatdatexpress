<div class="project-review">
    <div class="head">
        <i class="fas fa-user-circle"></i>
        Đánh giá
    </div>
    <div class="review-result">
        <div class="result-compact">
            {{-- <div class="number">{{round((5*$fiveStar + 4*$fourStar + 3*$threeStar + 2*$twoStar + 1*$oneStar) / ($oneStar+$twoStar+$threeStar+$fourStar+$fiveStar),2)}}</div> --}}
            <div class="number">5</div>
            <div class="star">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <div class="review-count">
                {{$countRating}} đánh giá
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
                <span>{{$fiveStar}}</span>
                <input type="hidden" value="{{$fiveStar}}">
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
                <span>{{$fourStar}}</span>
                <input type="hidden" value="{{$fourStar}}">
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
                <span>{{$threeStar}}</span>
                <input type="hidden" value="{{$threeStar}}">
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
                <span>{{$twoStar}}</span>
                <input type="hidden" value="{{$twoStar}}">
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
                <span>{{$oneStar}}</span>
                <input type="hidden" value="{{$oneStar}}">
            </div>
        </div>
        <div class="review-receiver" >
            <form id="form-rating" method="post" action="{{url('du-an/rating')."/".$project_id}}">
                @csrf 
                <div class="head">Đánh giá</div>
                <div class="receiver">
                    <i class="far fa-star"  onclick="submitFormRating(1)"></i>
                    <i class="far fa-star" onclick="submitFormRating(2)"></i>
                    <i class="far fa-star" onclick="submitFormRating(3)"></i>
                    <i class="far fa-star" onclick="submitFormRating(4)"></i>
                    <i class="far fa-star" onclick="submitFormRating(5)"></i>
                    <input type="hidden" name="onestar" id="onestar" value="0">
                    <input type="hidden" name="twostar" id="twostar" value="0" >
                    <input type="hidden" name="threestar" id="threestar" value="0">
                    <input type="hidden" name="fourstar" id="fourstar" value="0">
                    <input type="hidden" name="fivestar" id="fivestar" value="0">
                    <input type="hidden" name="ip" id="getip" value="">
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $.getJSON('https://api.db-ip.com/v2/free/self', function(data) {
       var getIp =JSON.parse(JSON.stringify(data, null, 2));
       $("#getip").val(getIp.ipAddress);
   });
    function submitFormRating(star){
        if(star == 1){
            $("#onestar").val(1)
        }
        if(star == 2){
            $("#twostar").val(1)
        }
        if(star == 3){
            $("#threestar").val(1)
        }
        if(star == 4){
            $("#fourstar").val(1)
        }
        if(star == 5){
            $("#fivestar").val(1)
        }
        document.getElementById('form-rating').submit();
    }
</script>
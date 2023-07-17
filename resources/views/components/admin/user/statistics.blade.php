<div class="row">
    <div class="col-lg-6 col-sm-6 col-md-6 col-12">
        <div class="row">
            <div class="col-lg-6 col-6">
                <!-- small box -->
                <div class="small-box">
                    <p class="small-box-footer">Tổng số thành viên</p>
                    <div class="inner">
                        <h3 class="large">{{number_format($total_user, 0, '.', '.')}}</h3>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-6 col-6">
                <!-- small box -->
                <div class="small-box">
                    <p class="small-box-footer">Thành viên mới trong tuần</p>
                    <div class="inner">
                        <h3>{{number_format($user_news, 0, '.', '.')}}</h3>
                        @if($user_news>$user_old)
                        <p class="sort-up"><a href="#"><i class="fas fa-caret-up">
                             </i>{{$user_news>$user_old?"Tăng ":"Giảm "}}{{ round($user_news/($user_old!=0?$user_old:1)*100) }}% </a> so với tuần trước
                        </p>
                        @else
                        <p class="sort-down"><a href="#"><i class="fas fa-caret-down">
                         </i>{{$user_news>$user_old?"Tăng ":"Giảm "}}{{ round($user_news/($user_old!=0?$user_old:1)*100) }}% </a> so với tuần trước
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-6 col-sm-6 col-md-6 col-12">
        <div class="row">
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box">
                    <p class="small-box-footer">TK Cá nhân</p>
                    <div class="inner">
                        <h3 class="">{{number_format($personal, 0, '.', '.')}}</h3>
                        @if($personal_news>$personal_old)
                        <p class="sort-up">
                            <a target="_blank" href="{{route('admin.account.list')}}"><i class="fas fa-caret-up"></i>{{$personal_news>=$personal_old?"Tăng ":"Giảm "}}{{ round($personal_news/($personal_old!=0?$personal_old:1)*100) }}%</a>
                            <a target="_blank" href="{{route('admin.account.list')}}" class="view">Xem</a>
                        </p>
                         @else
                            <p class="sort-down">
                                <a target="_blank" href="{{route('admin.account.list')}}"><i class="fas fa-caret-down"></i>{{$personal_news>$personal_old?"Tăng ":"Giảm "}}{{ round($personal_news/($personal_old!=0?$personal_old:1)*100) }}%</a>
                                <a target="_blank" href="{{route('admin.account.list')}}" class="view">Xem</a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box">
                    <p class="small-box-footer">TK Doanh nghiệp</p>
                    <div class="inner">
                        <h3>{{number_format($business, 0, '.', '.')}}</h3>
                        @if($business_news > $business_old)
                        <p class="sort-up">
                            <a target="_blank" href="{{route('admin.business.list')}}"><i class="fas fa-caret-up"></i>{{$business_news>$business_old?"Tăng ":"Giảm "}}{{ round($business_news/($business_old!=0?$business_old:1)*100) }}%</a>
                            <a target="_blank" href="{{route('admin.business.list')}}" class="view">Xem</a>
                        </p>
                            @else
                            <p class="sort-down">
                                <a arget="_blank" href="{{route('admin.business.list')}}"><i class="fas fa-caret-down"></i>{{$business_news>$business_old?"Tăng ":"Giảm "}}{{ round($business_news/($business_old!=0?$business_old:1)*100) }}%</a>
                                <a target="_blank" href="{{route('admin.business.list')}}" class="view">Xem</a>
                            </p>
                            @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box">
                    <p class="small-box-footer">CVTV</p>
                    <div class="inner">
                        <h3>{{number_format($cvtv, 0, '.', '.')}}</h3>
                        @if($cvtv_news > $cvtv_old)
                        <p class="sort-up">
                            <a target="_blank" href="{{route('admin.account.list')}}"><i class="fas fa-caret-up">
                             </i>{{$cvtv_news>$cvtv_old?"Tăng ":"Giảm "}}{{  round($cvtv_news/($cvtv_old!=0?$cvtv_old:1)*100) }}%</a>
                            <a target="_blank" href="{{route('admin.account.list')}}" class="view">Xem</a>
                        </p>
                        @else
                            <p class="sort-down">
                                <a target="_blank" href="{{route('admin.account.list')}}"><i class="fas fa-caret-down"></i>
                                    {{$cvtv_news>$cvtv_old?"Tăng ":"Giảm "}}{{ round($cvtv_news/($cvtv_old!=0?$cvtv_old:1)*100) }}%
                                </a>
                                <a target="_blank" href="{{route('admin.account.list')}}" class="view">Xem</a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

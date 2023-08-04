<div class="row popup-view-map__box">
    <div class="col-md-7-3 mb-sm-0 mb-3">
        <div class="flex-column position-relative">
            <x-common.loading class="inner popup-view-map__load-utilities"/>
      
            {{-- <iframe class="mapparent"
              src="https://maps.google.com/maps?q={{ data_get($classified->location, 'map_latitude') }},{{ data_get($classified->location, 'map_longtitude') }}&hl=vi&z=14&output=embed"
              width="100%" height="465px" style="border:0;" allowfullscreen="" loading="lazy"
            >
            </iframe>
      
            <input name="map-api" value="{!! getGoogleApiKey() !!}" type="hidden">
            <input name="latitude" value="{{ data_get($classified->location, 'map_latitude') }}" type="hidden">
            <input name="longtitude" value="{{ data_get($classified->location, 'map_longtitude') }}" type="hidden">
            <input name="full_address" value="{{ $classified->getFullAddress() }}" type="hidden"> --}}
      
            <x-common.map
              id="view-map__map"
              lat-name="latitude"
              long-name="longtitude"
              lat-value="{{ $getLocation->map_latitude }}"
              long-value="{{ $getLocation->map_longtitude }}"
              hide-hint
            />
            <x-common.map-utilities class="view-map__popup" />
        </div>

        {{-- <iframe class="mapparent w-100 h-450" src="https://maps.google.com/maps?q={{$getLocation->map_latitude}},{{$getLocation->map_longtitude}}&hl=vi&z=14&amp;output=embed" height="70%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        <input name="latitude"  value="{{$getLocation->map_latitude}}" type="hidden">
        <input name="longtitude"  value="{{$getLocation->map_longtitude}}" type="hidden">
        <input name="map-api" value="{!! getGoogleApiKey() !!}" type="hidden">

        <x-common.map-utilities /> --}}
    </div>
    <div class="col-md-3-7">
        <div class="h-500 scrollable">
            <div class="project-vote section">
                <div class="head-center">
                    <h3>Bỏ phiếu khảo sát</h3>
                </div>
                <div class="wrapper">
                    <div class="note">
                        Khảo sát này dựa trên ý kiến của khách hàng. Nhà Đất Express không can thiệp vào quá trình bỏ phiếu
                    </div>
                    <div class="vote-list">
                        <div class="vote-group">
                            <p class="vote-name">
                                Bạn đã mua dự án này chưa?
                            </p>
                            <div class="vote-bar bg-white">
                                <span class="vote-bar-inside" style="width:
                                @if($voteYes1==$voteNo1)
                                50%
                                @else
                                @if($voteYes1 == 0)
                                0%
                                @elseif($voteNo1 == 0)
                                100%
                                @else
                                {{($voteYes1/($voteYes1+$voteNo1))*100}}%;
                                @endif
                                @endif"></span>
                                <div class="yes">
                                    <input type="hidden" name="yes" value="70" >
                                    {{$voteYes1}} phiếu
                                </div>
                                <div class="no">
                                    <input type="hidden" name="no" value="30">
                                    {{$voteNo1}} phiếu
                                </div>
                            </div>
                        </div>
                        <div class="vote-group">
                            <p class="vote-name">
                                Chủ đầu tư có uy tín?
                            </p>
                            <div class="vote-bar">
                                <span class="vote-bar-inside" style="width:
                                @if($voteYes2==$voteNo2)
                                50%
                                @else
                                @if($voteYes2 == 0)
                                0%
                                @elseif($voteNo2 == 0)
                                100%
                                @else
                                {{($voteYes2/($voteYes2+$voteNo2))*100}}%;
                                @endif
                                @endif"></span>
                                <div class="yes">
                                    <input type="hidden" name="yes" value="80">
                                    {{$voteYes2}} phiếu
                                </div>
                                <div class="no">
                                    <input type="hidden" name="no" value="20">
                                    {{$voteNo2}} phiếu
                                </div>
                            </div>
                        </div>
                        <div class="vote-group">
                            <p class="vote-name">
                                Đơn vị xây dựng có uy tín?
                            </p>
                            <div class="vote-bar">
                                <span class="vote-bar-inside" style="width:
                                @if($voteYes3==$voteNo3)
                                50%
                                @else
                                @if($voteYes3 == 0)
                                0%
                                @elseif($voteNo3 == 0)
                                100%
                                @else
                                {{($voteYes3/($voteYes3+$voteNo3))*100}}%;
                                @endif
                                @endif
                                "></span>
                                <div class="yes">
                                    <input type="hidden" name="yes" value="390">
                                    {{$voteYes3}} phiếu
                                </div>
                                <div class="no">
                                    <input type="hidden" name="no" value="130">
                                    {{$voteNo3}} phiếu
                                </div>
                            </div>
                        </div>
                        <div class="vote-group">
                            <p class="vote-name">
                                Giá có hợp lý so với thị trường?
                            </p>
                            <div class="vote-bar">
                                <span class="vote-bar-inside" style="width:
                                @if($voteYes4==$voteNo4)
                                50%
                                @else
                                @if($voteYes4 == 0)
                                0%
                                @elseif($voteNo4 == 0)
                                100%
                                @else
                                {{($voteYes4/($voteYes4+$voteNo4))*100}}%;
                                @endif
                                @endif"></span>
                                <div class="yes">
                                    <input type="hidden" name="yes" value="124">
                                    {{$voteYes4}} phiếu
                                </div>
                                <div class="no">
                                    <input type="hidden" name="no" value="30">
                                    {{$voteNo4}} phiếu
                                </div>
                            </div>
                        </div>
                        <div class="vote-group">
                            <p class="vote-name">
                                Vị trí thuận tiện di chuyển?
                            </p>
                            <div class="vote-bar">
                                <span class="vote-bar-inside" style="width:
                                @if($voteYes5==$voteNo5)
                                50%
                                @else
                                @if($voteYes5 == 0)
                                0%
                                @elseif($voteNo5 == 0)
                                100%
                                @else
                                {{($voteYes5/($voteYes5+$voteNo5))*100}}%;
                                @endif
                                @endif"></span>
                                <div class="yes">
                                    <input type="hidden" name="yes" value="67">
                                    {{$voteYes5}} phiếu
                                </div>
                                <div class="no">
                                    <input type="hidden" name="no" value="12">
                                    {{$voteNo5}} phiếu
                                </div>
                            </div>
                        </div>
                        <div class="vote-group">
                            <p class="vote-name">
                                Thiết kế đẹp?
                            </p>
                            <div class="vote-bar">
                                <span class="vote-bar-inside" style="width:
                                @if($voteYes6==$voteNo6)
                                50%
                                @else
                                @if($voteYes6 == 0)
                                0%
                                @elseif($voteNo6 == 0)
                                100%
                                @else
                                {{($voteYes6/($voteYes6+$voteNo6))*100}}%;
                                @endif
                                @endif"></span>
                                <div class="yes">
                                    <input type="hidden" name="yes" value="68">
                                    {{$voteYes6}} phiếu
                                </div>
                                <div class="no">
                                    <input type="hidden" name="no" value="2">
                                    {{$voteNo6}} phiếu
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="vote-note">
                        <p class="label">Ghi chú</p>
                        <div class="vote-note-items">
                            <span class="yes"></span>
                            <p>Có</p>
                            <span class="no"></span>
                            <p>Không</p>
                        </div>
                    </div>
                    <div class="vote-button">
                        Bỏ phiếu
                    </div>
                </div>
            </div>
            <div class="project-vote-enable section">
                <div class="head-center">
                    <h3>Bỏ phiếu khảo sát</h3>
                </div>
                <div class="wrapper">
                    <div class="note">
                        Khảo sát này dựa trên ý kiến của khách hàng. Nhà Đất Express không can thiệp vào quá trình bỏ phiếu.
                    </div>
                    <form method="post" action="{{url('du-an/vote')."/".$project_id}}" class="vote-list">
                        @csrf
                        <div class="vote-group">
                            <p class="vote-name">
                                Bạn đã mua dự án này chưa?
                            </p>
                            <p class="vote-bar">
                                <label for="buy-yes">
                                    <input type="radio" name="vote1" value="on" checked>Có
                                </label>
                                <label for="buy-no">
                                    <input type="radio" name="vote1" value="off">Không
                                </label>
                            </p>
                        </div>
                        <div class="vote-group">
                            <p class="vote-name">
                                Chủ đầu tư có uy tín?
                            </p>
                            <p class="vote-bar">
                                <label for="investor-yes">
                                    <input type="radio" name="vote2" value="on" checked>Có
                                </label>
                                <label for="investor-no">
                                    <input type="radio" name="vote2" value="off">Không
                                </label>
                            </p>
                        </div>
                        <div class="vote-group">
                            <p class="vote-name">
                                Đơn vị xây dựng có uy tín?
                            </p>
                            <p class="vote-bar">
                                <label for="con-unit-yes">
                                    <input type="radio" name="vote3" value="on" checked>Có
                                </label>
                                <label for="con-unit-no">
                                    <input type="radio" name="vote3" value="off">Không
                                </label>
                            </p>
                        </div>
                        <div class="vote-group">
                            <p class="vote-name">
                                Giá có hợp lý so với thị trường?
                            </p>
                            <p class="vote-bar">
                                <label for="price-yes">
                                    <input type="radio" name="vote4" value="on" checked>Có
                                </label>
                                <label for="price-no">
                                    <input type="radio" name="vote4" value="off">Không
                                </label>
                            </p>
                        </div>
                        <div class="vote-group">
                            <p class="vote-name">
                                Vị trí thuận tiện di chuyển?
                            </p>
                            <p class="vote-bar">
                                <label for="location-yes">
                                    <input type="radio" name="vote5" value="on" checked>Có
                                </label>
                                <label for="location-no">
                                    <input type="radio" name="vote5" value="off">Không
                                </label>
                            </p>
                        </div>
                        <div class="vote-group">
                            <p class="vote-name">
                                Thiết kế đẹp?
                            </p>
                            <p class="vote-bar">
                                <label for="design-yes">
                                    <input type="radio" name="vote6" value="on" checked>Có
                                </label>
                                <label for="design-no">
                                    <input type="radio" name="vote6" alue="off">Không
                                </label>
                            </p>
                        </div>

                        <input type="submit" value="Xác nhận">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .project-vote .vote-bar .vote-bar-inside2 {
    position: absolute;
    content: "";
    height: 100%;
    top: 0;
    left: 0;
    background-color: #00B4FF;
    border-right: 1px solid #ECE4E0;
}
</style>
<div class="col-md-3-7 single-sidebar project-detail-page">
    <div class="project-vote section project-detail__survey">
        <div class="head-center">
            <h3>Bỏ phiếu khảo sát</h3>
        </div>
        <div class="wrapper js-need-toggle-active js-toggle-area">
            <div class="note">
                Khảo sát này dựa trên ý kiến của khách hàng. Nhà Đất Express không can thiệp vào quá trình bỏ phiếu
            </div>
            <div class="vote-list mb-1">
                <div class="vote-group">
                    <p class="vote-name">
                        Bạn đã mua dự án này chưa?
                    </p>
                    <div class="vote-bar bg-white">
                        <span class="vote-bar-inside2" style="width:{{($vote[0][1]??0)/((($vote[0][1]??1)+($vote[0][2]??0)))*100}}%"></span>
                        <div class="yes">
                            <input type="hidden" name="yes" value="70" >
                            {{$vote[0][1]??0}} phiếu
                        </div>
                        <div class="no">
                            <input type="hidden" name="no" value="30">
                            {{$vote[0][2]??0}} phiếu
                        </div>
                    </div>
                </div>
                <div class="vote-group">
                    <p class="vote-name">
                        Chủ đầu tư có uy tín?
                    </p>
                    <div class="vote-bar">
                        <span class="vote-bar-inside2" style="width:{{($vote[1][1]??0)/(($vote[1][1]??1)+($vote[1][2]??0))*100}}%"></span>
                        <div class="yes">
                            <input type="hidden" name="yes" value="80">
                            {{$vote[1][1]??0}} phiếu
                        </div>
                        <div class="no">
                            <input type="hidden" name="no" value="20">
                            {{$vote[1][2]??0}} phiếu
                        </div>
                    </div>
                </div>
                <div class="vote-group">
                    <p class="vote-name">
                        Đơn vị xây dựng có uy tín?
                    </p>
                    <div class="vote-bar">
                        <span class="vote-bar-inside2" style="
                        width:{{($vote[2][1]??0)/(($vote[2][1]??1)+($vote[2][2]??0))*100}}%
                        "></span>
                        <div class="yes">
                            <input type="hidden" name="yes" value="390">
                            {{$vote[2][1]??0}} phiếu
                        </div>
                        <div class="no">
                            <input type="hidden" name="no" value="130">
                            {{$vote[2][2]??0}} phiếu
                        </div>
                    </div>
                </div>
                <div class="vote-group">
                    <p class="vote-name">
                        Giá có hợp lý so với thị trường?
                    </p>
                    <div class="vote-bar">
                        <span class="vote-bar-inside2" style="
                        width:{{($vote[3][1]??0)/(($vote[3][1]??1)+($vote[3][2]??0))*100}}%"></span>
                        <div class="yes">
                            <input type="hidden" name="yes" value="124">
                            {{$vote[3][1]??0}} phiếu
                        </div>
                        <div class="no">
                            <input type="hidden" name="no" value="30">
                            {{$vote[3][2]??0}} phiếu
                        </div>
                    </div>
                </div>
                <div class="vote-group">
                    <p class="vote-name">
                        Vị trí thuận tiện di chuyển?
                    </p>
                    <div class="vote-bar">
                        <span class="vote-bar-inside2" style="
                        width:{{($vote[4][1]??0)/(($vote[4][1]??1)+($vote[4][2]??0))*100}}%"></span>
                        <div class="yes">
                            <input type="hidden" name="yes" value="67">
                            {{$vote[4][1]??0}} phiếu
                        </div>
                        <div class="no">
                            <input type="hidden" name="no" value="12">
                            {{$vote[4][2]??0}} phiếu
                        </div>
                    </div>
                </div>

                <div class="js-toggle-area-slide" style="display: none">
                    <div class="vote-group">
                        <p class="vote-name">
                            Thiết kế đẹp?
                        </p>
                        <div class="vote-bar">
                            <span class="vote-bar-inside2" style="
                            width:{{($vote[5][1]??0)/(($vote[5][1]??1)+($vote[5][2]??0))*100}}%"></span>
                            <div class="yes">
                                <input type="hidden" name="yes" value="68">
                                {{$vote[5][1]??0}} phiếu
                            </div>
                            <div class="no">
                                <input type="hidden" name="no" value="2">
                                {{$vote[5][2]??0}} phiếu
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
                </div>
            </div>

            <div class="text-center js-toggle-area">
                <button class="active-hide btn btn-sm btn-warning js-toggle-active">
                    Xem thêm
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>

            <div class="vote-button">
                Bỏ phiếu
            </div>
        </div>
    </div>
    <div class="project-vote-enable section project-detail__survey">
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
                            <input type="radio" name="survey_1" value="1" checked>Có
                        </label>
                        <label for="buy-no">
                            <input type="radio" name="survey_1" value="0">Không
                        </label>
                    </p>
                </div>
                <div class="vote-group">
                    <p class="vote-name">
                        Chủ đầu tư có uy tín?
                    </p>
                    <p class="vote-bar">
                        <label for="investor-yes">
                            <input type="radio" name="survey_2" value="1" checked>Có
                        </label>
                        <label for="investor-no">
                            <input type="radio" name="survey_2" value="0">Không
                        </label>
                    </p>
                </div>
                <div class="vote-group">
                    <p class="vote-name">
                        Đơn vị xây dựng có uy tín?
                    </p>
                    <p class="vote-bar">
                        <label for="con-unit-yes">
                            <input type="radio" name="survey_3" value="1" checked>Có
                        </label>
                        <label for="con-unit-no">
                            <input type="radio" name="survey_3" value="0">Không
                        </label>
                    </p>
                </div>
                <div class="vote-group">
                    <p class="vote-name">
                        Giá có hợp lý so với thị trường?
                    </p>
                    <p class="vote-bar">
                        <label for="price-yes">
                            <input type="radio" name="survey_4" value="1" checked>Có
                        </label>
                        <label for="price-no">
                            <input type="radio" name="survey_4" value="0">Không
                        </label>
                    </p>
                </div>
                <div class="vote-group">
                    <p class="vote-name">
                        Vị trí thuận tiện di chuyển?
                    </p>
                    <p class="vote-bar">
                        <label for="location-yes">
                            <input type="radio" name="survey_5" value="1" checked>Có
                        </label>
                        <label for="location-no">
                            <input type="radio" name="survey_5" value="0">Không
                        </label>
                    </p>
                </div>
                <div class="vote-group">
                    <p class="vote-name">
                        Thiết kế đẹp?
                    </p>
                    <p class="vote-bar">
                        <label for="design-yes">
                            <input type="radio" name="survey_6" value="1" checked>Có
                        </label>
                        <label for="design-no">
                            <input type="radio" name="survey_6" value="0">Không
                        </label>
                    </p>
                </div>

                <input type="submit" value="Xác nhận">
            </form>
        </div>
    </div>
</div>

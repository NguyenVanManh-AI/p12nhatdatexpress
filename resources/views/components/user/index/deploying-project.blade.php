<div class="social-network-info-account">
    <div class="title-project-woking">
        <h5>Dự án đang triển khai</h5>
        <p>(Chỉ được chọn {{ auth('user')->user()->isEnterprise() ? 5 : 1 }} dự án)</p>
    </div>
    <form action="{{route('user.post-update-deploying-project')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6 question-update">
                <x-common.select2-input
                    max-length="{{ auth('user')->user()->isEnterprise() ? 5 : 1 }}"
                    dropdown-class="select2-purple"
                    name="projects[]"
                    :items="$listProjects"
                    :items-current-value="old('projects', $selectedProjects)"
                    item-text="project_name"
                    placeholder="Chọn dự án"
                    multiple
                />
            </div>
            <div class="col-md-6 question-update">
                <p>Nếu dự án này chưa có vui lòng yêu cầu </p>
                <p><a href="#" data-toggle="modal" data-target="#project_request">Cập nhật tại đây</a></p>
            </div>
        </div>
        <div class="click-update"><input type="submit" class="btn update-info-btn" value="Lưu"></div>
    </form>
</div>
@include('user.popup.project_request')

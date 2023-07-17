<div class="modal fade" id="project_request" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered bd-example-modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header flex-center bg-sub-main">
                <h4 class="modal-title text-white fs-18">Nhập thông tin dự án</h4>
                <button type="button" class="close custom-close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{route('user.post-project-request')}}" class="form form-chat form-popup" method="post">
                    @csrf
                    <div class="row group-load-address">
                        <div class="col">
                            <x-common.text-input
                                label="Tên dự án"
                                name="project_name"
                                value="{{ old('project_name') }}"
                                placeholder="Tên dự án"
                                required
                            />

                            <x-common.text-input
                                label="Chủ dự án"
                                name="investor"
                                value="{{ old('investor') }}"
                                placeholder="Chủ dự án"
                                required
                            />

                            <x-common.text-input
                                label="Địa chỉ"
                                name="address"
                                value="{{ old('address') }}"
                                placeholder="Địa chỉ"
                                required
                            />
                              
                            <x-common.select2-input
                                label="Tỉnh/thành phố"
                                input-class="province-load-district"
                                name="pr_province"
                                placeholder="-- Tỉnh/Thành Phố  --"
                                :items="$provinces"
                                item-text="province_name"
                                items-select-name="pr_province"
                                :items-current-value="old('pr_province')"
                                select2-parent="#project_request"
                            />
                              
                            <x-common.select2-input
                                label="Quận huyện"
                                input-class="district-province district-load-ward"
                                name="pr_district"
                                placeholder="-- Quận/Huyện --"
                                :data-selected="old('pr_district')"
                                select2-parent="#project_request"
                            />
                              
                            <x-common.select2-input
                                label="Xã/phường"
                                input-class="ward-district"
                                name="pr_ward"
                                placeholder="-- Xã/Phường --"
                                :data-selected="old('pr_ward')"
                                select2-parent="#project_request"
                            />
                        </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                        <input type="submit" class="btn btn-success" value="Gửi yêu cầu">
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

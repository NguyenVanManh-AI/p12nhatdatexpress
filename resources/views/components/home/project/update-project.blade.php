@push('style')
    <style>
        #update-project-content{
            width: 460px;
            padding: 15px 20px;
            background-color: #fff;
            border-radius: 8px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            z-index: 1049;
            display: none;
        }
        #update-project-content .wrapper > i{
            position: absolute;
            color: #8A8A8A;
            right: 0;
            top: 7px;
            cursor: pointer;
            font-size: 18px;
        }
        #update-project-content .title{
            font-size: 22px;
            color: #0096DF;
            text-transform: uppercase;
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-bottom: 1px solid #E8E8E8;
        }
        #update-project-content input[type="submit"]{
            flex: 1;
            border: unset;
            background-color: #00BFF3;
            color: #fff;
        }
        #update-project-content .foot label{
            margin: 0 5px 0 0;
        }
        #update-project-content .foot{
            display: flex;
            align-items: center;
            padding-top: 5px;
        }
        #update-project-content .radio-list{
            margin-bottom: 15px;
        }
        #update-project-content .radio-list .group{
            display: flex;
            align-items: center;
        }
        #update-project-content .radio-list input{
            margin-right: 10px;
            width: 15px;
            height: 15px;
        }
        #update-project-content .radio-list label{
            margin: 0;
            font-weight: 400;
        }
    </style>
@endpush
<div id="update-project-content" class="popup update-project-popup">
    <div class="wrapper">
        <div class="title">
            Cập nhật dự án
        </div>
        <form action="" method="post" id="formUpdate">
            @csrf
            <div class="radio-list">

                <div class="group">
                    <input type="radio" name="update" id="update_price" value="1" {{ old('update') == 1 ? 'checked' : '' }}>
                    <label for="update_price">Cập nhật giá bán</label>
                </div>

                <div class="group">
                    <input type="radio" name="update" id="update_rent" value="2" {{ old('update') == 2 ? 'checked' : '' }}>
                    <label for="update_rent">Cập nhật giá thuê</label>
                </div>

                <div class="group">
                    <input type="radio" name="update" id="update_progress" value="3" {{ old('update') == 3 ? 'checked' : '' }}>
                    <label for="update_progress">Cập nhật tình trạng</label>
                </div>
            </div>

            <div class="update-project-popup__input d-none">
                <x-common.text-input
                    name="update_content"
                    placeholder="Nhập giá trị cập nhật"
                    value="{{ old('update_content') }}"
                />
            </div>

            <div class="update-project-popup__select d-none">
                <x-common.select2-input
                    name="update_content_progress"
                    id="select_update"
                    placeholder="Nhập tình trạng"
                    :items="$progress"
                    item-text="progress_name"
                    items-select-name="update_content_progress"
                    :items-current-value="old('update_content_progress')"
                />
            </div>

            <div class="mb-3">
                <x-common.captcha />
            </div>

            <div class="text-center">
                <button class="btn btn-light-cyan px-4">
                    Gửi
                </button>
            </div>
        </form>
        <i class="fas fa-times close-popup"></i>
    </div>
</div>

@push('scripts_children')
<script>

    const progress = <?php echo $progress ?>;

    function handleShowContent(default_select = null){
        let selected = undefined;
        if(default_select == null)
             selected = $("#update-project-content input[name='update']:checked").val();
        else
        {
            selected = default_select
            $('#update-project-content input:radio[name="update"]').filter(`[value="${selected}"]`).prop('checked', true);
        }

        switch (parseInt(selected)){
            case 1:
            case 2:
                $('.update-project-popup__select').addClass('d-none')
                $('.update-project-popup__input').removeClass('d-none')
                break;

            case 3:
                $('.update-project-popup__input').addClass('d-none')
                $('.update-project-popup__select').removeClass('d-none')
                break;
        }
    }

    handleShowContent()

    $("#update-project-content input[name='update']").change(function () {
        handleShowContent();
    })

    $("body").on("click", ".update-project", function (event) {
        event.preventDefault();

        @if(auth('user')->check())

        $("#update-project-content").show();
        $("#layout").show();

        $("#select_update").empty();
        const selectBox = document.getElementById('select_update');
        const selected_progress = $(this).data('selected')
        const data_selected = $(this).data('action')
        const url_submit = $(this).data('href');
        const groupId = $(this).data('group-id')

        $('#formUpdate').attr('action', url_submit)
        handleShowContent(data_selected);

        progress.forEach((item) => {
            if(item.group_id == groupId)
            {
                const newOption = new Option(item.progress_name, item.id);
                selectBox.appendChild(newOption);
            }
        })
        $('#select_update').val(selected_progress)
        $("#select_update").trigger('change');
        $('.update-project-popup__input input').val('')
        @else
        toastr.error("Vui lòng đăng nhập")
        @endif
    });

    $("body").on("click", ".popup .close-popup", function (event) {
        event.preventDefault();
        let popup = $(this).parents(".popup");
        popup.hide();
        $("#layout").hide();
    });

    $("body").on("click", "#layout", function (event) {
        event.preventDefault();
        $("#update-project-content").hide();
        $("#layout").hide();
    });
</script>
@endpush

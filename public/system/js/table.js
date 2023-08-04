// Chinh: function Select all or UnSelect all trong table
// 28/12/2021
// Select all | UnSelect all
$(function () {

    const inputSelectAll = $("input.select-all")
    const inputSelectItem = $("input.select-item")

    //button select all or cancel
    $("#select-all").click(function () {
        var all = inputSelectAll[0];
        all.checked = !all.checked
        var checked = all.checked;
        inputSelectItem.each(function (index, item) {
            item.checked = checked;
        });
    });

    //button select invert
    $("#select-invert").click(function () {
        inputSelectItem.each(function (index, item) {
            item.checked = !item.checked;
        });
        checkSelected();
    });

    //column checkbox select all or cancel
    inputSelectAll.click(function () {
        var checked = this.checked;
        inputSelectItem.each(function (index, item) {
            item.checked = checked;
        });
    });

    //check selected items
    inputSelectItem.click(function () {
        var checked = this.checked;
        checkSelected();
    });

    //check is all selected
    function checkSelected() {
        var all = $("input.select-all")[0];
        var total = $("input.select-item").length;
        var len = $("input.select-item:checked:checked").length;
        all.checked = len === total;
    }
});
//get selected info
function getSelected() {
    var items = [];
    $("input.select-item:checked:checked").each(function (index, item) {
        // items[index] = item.value;
        items[index] = item;
    });
    if (items.length < 1) {
        // alert("Không có mục nào được chọn !!!");
        toastr.error('Không có mục nào được chọn !!!');
    } else {
        var values = items.join(',');
        var html = $("<div></div>");
        html.html("selected:" + values);
        html.appendTo("body");
        return items;
    }
}

jQuery(document).ready(function ($) {
    $(function () {
        $("input.select-item").not('.select-all').change(function () {
            $(this).is(':checked') ? $(this).parent().parent().addClass('active') : $(this).parent().parent().removeClass('active')
        })
        $("input.select-all.checkbox").change(function () {
            $(this).is(':checked') ? $(this).parent().parent().parent().siblings().find('tr').map((k, v) => $(v).addClass('active'))
                : $(this).parent().parent().parent().siblings().find('tr').map((k, v) => $(v).removeClass('active'))
        })
        $("table input:text").change(function () {
            $(this).parent().siblings().find('input.select-item').prop('checked', true)
            $(this).parent().parent().addClass('active')
        })
        // handle checkbox in table toggle
        $("table input:checkbox.checkbox-select").change(function () {
            $(this).parents('tr').toggleClass('active')
            $(this).parents('tr').find('input.select-item').prop('checked', !$(this).parents('tr').find('input.select-item').is(':checked'))
        })
        $("table input:checkbox.checkboxItem").change(function () {
            const tr = $(this).parent().parent().parent().parent();
            tr.find('input.select-item').prop('checked', true)
            tr.addClass('active')
        })

        function restoreItem() {
            if (!getSelected()) return
            Swal.fire({
                title: 'Xác nhận khôi phục!',
                text: `Sau khi khôi phục sẽ được chuyển sang danh sách`,
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitMultipleActionForm('restore')
                }
            })
        }

        function deleteItem() {
            if (!getSelected()) return
            Swal.fire({
                title: 'Xác nhận xóa',
                text: "Sau khi xóa sẽ chuyển vào thùng rác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed)
                    submitMultipleActionForm('delete')
            })
        }

        function forceDeleteItem() {
            if (!getSelected()) return
            Swal.fire({
                title: 'Xác nhận xóa hẳn',
                text: "Sau khi xóa sẽ không thể khôi phục",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed)
                    submitMultipleActionForm('duplicate')
            })
        }

        function duplicateItem() {
            if (!getSelected()) return
            Swal.fire({
                title: 'Xác nhận nhân bản!',
                text: "Sau khi nhân bản sẽ có thêm 1 bản ghi tương tự!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed)
                    submitMultipleActionForm('duplicate')
            })
        }

        function updateItem() {
            if (!getSelected()) return
            Swal.fire({
                title: 'Xác nhận cập nhật',
                text: `Cập nhật thông tin`,
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed)
                    submitMultipleActionForm('duplicate')
            })
        }

        const submitMultipleActionForm = (action, updateActions) => {
            let $table = $('.js-table__has-multiple-action'),
                $form = $('.js-table__action-form'),
                url = $form.attr(`data-${action}-url`);

            if (!url || !$form || !$form.length) return

            const selectedArray = getSelected();
            let ids = [];

            selectedArray.forEach(element => {
                const selectedId = $(element).val()

                if (selectedId) {
                    ids.push(selectedId)
                    if (updateActions && updateActions.length && typeof updateActions == 'object') {
                        updateActions.forEach(updateAction => {
                            switch (updateAction) {
                                case 'order':
                                    const orderIndex = $table.find(`[name="show_order[${selectedId}]"]`).val()
                                    if (orderIndex != null)
                                        $form.append(`
                                            <input type="hidden" name="show_order[${selectedId}]" value="${orderIndex}" />
                                        `)
                                    break;
                                default:
                                    break;
                            }
                        });
                    }
                }
            })

            $form.attr('action', url)
            $form.find('input[name="ids"]').val(ids)
            $form.trigger('submit')
        }

        $(document).ready(function () {
            $('.js-delete-multiple').on('click', function (e) {
                e.preventDefault()
                deleteItem()
            })

            $('.js-force-delete-multiple').on('click', function (e) {
                e.preventDefault()
                forceDeleteItem()
            })

            $('.js-restore-multiple').on('click', function (e) {
                e.preventDefault()
                restoreItem()
            })

            $('.js-duplicate-multiple').on('click', function (e) {
                e.preventDefault()
                duplicateItem()
            })

            $('.js-update-show-order').on('click', function (e) {
                e.preventDefault()
                updateItem(['order'])
            })
        })
    })
})

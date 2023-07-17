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
        // console.log(checked);
        checkSelected();
    });

    //check is all selected
    function checkSelected() {
        var all = $("input.select-all")[0];
        var total = $("input.select-item").length;
        var len = $("input.select-item:checked:checked").length;
        // console.log("total:"+total);
        // console.log("len:"+len);
        all.checked = len===total;
    }
});
//get selected info
function getSelected(){
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
        // console.log(values);
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
            $(this).is(':checked') ? $(this).parent().parent().parent().siblings().find('tr').map((k,v) => $(v).addClass('active'))
                : $(this).parent().parent().parent().siblings().find('tr').map((k,v) => $(v).removeClass('active'))
        })
        $("table input:text").change(function () {
            $(this).parent().siblings().find('input.select-item').prop('checked',true)
            $(this).parent().parent().addClass('active')
        })
        // handle checkbox in table toggle
        $("table input:checkbox.checkbox-select").change(function () {
            $(this).parents('tr').toggleClass('active')
            $(this).parents('tr').find('input.select-item').prop('checked', !$(this).parents('tr').find('input.select-item').is(':checked'))
        })
        $("table input:checkbox.checkboxItem").change(function () {
            const tr = $(this).parent().parent().parent().parent();
            tr.find('input.select-item').prop('checked',true)
            tr.addClass('active')
        })
    })
})

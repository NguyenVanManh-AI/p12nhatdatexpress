$(function () {
    $("#date-send").change(function () {
        $("#check-birthday").attr("checked", false);
    })
    $("#start-time-hour-input").change(function () {
        $("#check-birthday").attr("checked", false);
    })
    $("#start-time-min-input").change(function () {
        $("#check-birthday").attr("checked", false);
    })

    $('#date-send').change(function () {
        if ($('#date-send').val() != "") {
            $("#check-birthday").attr("disabled", true);
        } else {
            $("#check-birthday").attr("disabled", false);
        }
    })
    $('#check-birthday').change(function () {
        if ($('#check-birthday').is(":checked")) {
            $('.disble-input-send-date').show();
        } else {
            $('.disble-input-send-date').hide();
        }
    })
});

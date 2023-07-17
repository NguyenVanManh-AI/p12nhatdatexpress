$(function () {

    $('.access-options').click(function (evt) {
        evt.preventDefault();
        let url = $(this).attr('href')
        $.ajax({
            url,
            success: function (result) {
                $('.access-choose').html(result.title)
                $('#access-value').html(result.data)
                $('#access-direction').html(result.percent > 0 ? 'Tăng' : 'Giảm')
                $('#access-percent').html(Math.abs(result.percent) + '%')
            }
        })
    })

})

jQuery(document).ready(function($) {
    $('input[name="daterange"]').daterangepicker();

    $('.tutorial-content .show-more-tutorial a').on('click', function(e) {
        e.preventDefault();

        $(this).fadeOut();
        $('.tutorial-item-content').css("height", "auto");
        $('.show-more-tutorial').css("border-top", "none");


    });

    $('.see').click(function() {
        $('#myModal').modal('show');
        var test = $('.upload-cover.upload-img').attr('src');
        $('.modal-content').attr('src', test);
    });
});

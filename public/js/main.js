$(function () {
    $('.wrapper').dotdotdot();

    $(window).resize(function () {
        $('.wrapper').trigger('update');
    });
});
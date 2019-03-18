$(function () {
    $(window).resize(function () {
        $('.wrapper').trigger('update');
    });
});
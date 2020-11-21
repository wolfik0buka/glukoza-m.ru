$( function() {
    $('.do_toggle_city').click(function () {
        var value = $(this).text();
        $('.do_set_city_value').text(value);
        $.cookie('city', value, { expires: 7, path: '/' });
    });

});
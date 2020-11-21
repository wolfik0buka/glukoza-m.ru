$( function() {
    if( slider_settings){
        var price_slider =  $("#price-slider");
        var price_from = $("#price_from");
        var price_to = $("#price_to");
        price_slider.slider({
            animate: "slow",
            range: true,
            min: slider_settings.min,
            max: slider_settings.max,
            values: [slider_settings.cur_min, slider_settings.cur_max],
            slide: function (event, ui) {
                price_from.val(ui.values[0]);
                price_to.val(ui.values[1]);
                $('#product_filter').submit();
            }
        });
        price_from.val(price_slider.slider("values", 0));
        price_to.val(price_slider.slider("values", 1));
    }

    $('#product_filter').change(function () {
        $(this).submit();
    })
    $('#form_reset').click(function () {
        window.location.href = window.location.href.split('?')[0];
    })

    $('.filter-box__show-all').click(function () {
        $(this).siblings('.filter-box__closed-options').show();
        $(this).remove();
    });

    $('.do_expand_filter_box').click(function () {
        $(this).siblings('.filter_body').toggle(200);
        $(this).toggleClass('closed');
    });

});
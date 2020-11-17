$ ->
    $('.expand_button').mouseenter(
        (ev) -> $('.expand_menu').fadeIn(200)
    ).mouseleave(
        (ev) -> $('.expand_menu').fadeOut(100)
    )

    lvl1 = $('.cat_parent')
    lvl1.hover(
        (ev) ->
            cat_id =  $(this).data 'id'
            lvl1.removeClass('active')
            $(this).addClass('active')
            $('.child_cat').hide()
            $('#child_cats_'+cat_id).show()
    )
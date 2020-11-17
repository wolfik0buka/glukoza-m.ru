function add_post_id(order_id) {
    $.ajax({
        type: 'post',
        url: '/add_post_id',
        data: {
            order_id: order_id,
            post_id: $('#post_id').val()
        },
        success: function (json) {
            alert('Номер почтового идентификатора записан и отправлен получателю');
        }
    });
}


function to_dostavka() {
    $.ajax({
        type: 'POST',
        url: '/admin_new/order/to_dostavka.php',
        data: {
            id: $('#current_order').val()
        },
        dataType: 'xml',
        success: function(xml) {
            alert($('head', xml).text());
            $('span#cour_id').html($('head', xml).attr('cour_id'));
        }
    });
}


$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
function show_archive_orders(){
    if ($("tr.blednim").is(":hidden")) {
        $("tr.blednim").show();
        $("div.show_archive_orders").html('<i class="fa fa-eye-slash"></i> Скрыть завершенные заказы');
    }
    else{
        $("tr.blednim").hide();
        $("div.show_archive_orders").html('<i class="fa fa-eye"></i> Показать завершенные заказы');
    }
}
function search_in_order() {
    txt = '<tr><td colspan="7"><img style="display: block; margin: 50px auto;" src="/img/loading.gif"></td></tr>';
    $('table.panelTable tbody').html(txt);
    search = $('#search').val();
    $.post('/admin_new/arc/db.php', 'doIt=get_order_list_arc&search='+search, request_in_div, 'xml');
}
function add_post_id(order_id) {
    $.ajax({
        type: 'POST',
        url: '/admin_new/arc/db.php',
        data: {
            doIt: 'add_post_id',
            order_id: order_id,
            post_id: $('#post_id').val()
        },
        dataType: "xml",
        success: function (xml) {
            send_post_id(order_id);
        },
        error: function (json) {
            console.log(json);
        }
    });
}
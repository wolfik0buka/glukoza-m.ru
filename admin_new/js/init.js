$(document).ready(function () {
    window.name = 'basic_window';
    switch ($('input#currentPage').val()) {
        case('order_list'):
            document.onkeyup = function (e) {
                e = e || window.event;
                if (e.keyCode === 13) {
                    search_in_order();
                }
                return false;
            }
            search_param = '';
            client_param = -1;
            if ($('input#search').val() != '') search_param = '&search=' + $('input#search').val();
            if ($('input#client_id').val() != '') client_param = '&client_id=' + $('input#client_id').val();
            $.post('/admin_new/order/db.php', 'doIt=get_order_list_new' + search_param + client_param, request_in_div, 'xml');
            break;
        case('arc'):
            document.onkeyup = function (e) {
                e = e || window.event;
                if (e.keyCode === 13) {
                    search_in_order();
                }
                return false;
            }
            search_param = '';
            client_param = -1;
            if ($('input#search').val() != '') search_param = '&search=' + $('input#search').val();
            if ($('input#client_id').val() != '') client_param = '&client_id=' + $('input#client_id').val();
            $.post('/admin_new/arc/db.php', 'doIt=get_order_list_arc' + search_param + client_param, request_in_div, 'xml');
            break;
        case('order_single'):
            //$('#date_of_delivery').datepicker();
            break;
        case('users_list'):
            user_list('desc');
            $('.fa-sort-amount-desc').click(function () {
                user_list('desc');
            });
            $('.fa-sort-amount-asc').click(function () {
                user_list('asc');
            });
            break;
    }
});

function user_list(sort) {
    search_param = '';
    sort_param = '&sort=' + sort;
    if ($('input#search').val() != '') search_param = '&search=' + $('input#search').val();
    $.post('/admin_new/users/db.php', 'doIt=get_users_list' + search_param + sort_param, request_in_table, 'xml');
}
function request_in_table(xml) {
    var txt = $('head', xml).text();
    $('table.panelTable tbody').html(txt);
    if ($('head', xml).attr('sort') != '') {  // Делаем кнопку сортировка "текущей"
        $('.fa').removeClass('current');
        $('.fa-sort-amount-' + $('head', xml).attr('sort')).addClass('current');
    }
}
function request_in_div(xml) {
    var page_content = $('head', xml).text();
    var tableBody = $('div#order_table_body');
    $(tableBody).hide();
    $(tableBody).html(page_content);
    show_archive_orders(true);
    $(tableBody).show();
    var client_width = $('table.panelTable tr td:nth-child(3)').innerWidth();
    $('table#order_head tr td:nth-child(3)').innerWidth(client_width);

    if ($('head', xml).attr('id') != -1) {
        $('.show_archive_orders').hide();
        $('.search_client').hide();
        if ($('head', xml).attr('val') != -1) $('h2').append(' клиента ' + $('head', xml).attr('val'));
        $("tr.blednim").show();
        sum_all = $('head', xml).attr('sum_all');
        sum_pay = $('head', xml).attr('sum_pay');
        bonus = $('head', xml).attr('bonus');
        $('#content').append('<div class="clear20"></div><div>Всего заказов на сумму: ' + sum_all + ' руб</div><div class="clear10"></div><div>Оплачено заказов на сумму: ' + sum_pay + ' руб</div><div class="clear10"></div><div>Накоплено бонусов: ' + bonus + '</div><div class="clear20"></div><a class="uni-button" href="/admin_new/index.php?page=users">« Вернуться к списку пользователей</a>');
    }
    if ($('input#str').length) {
        if (typeof $('input#str' + $('#str').val()).offset() !== "undefined") {
            var scrollTop = $('input#str' + $('#str').val()).offset().top - 85;
            $(document).scrollTop(scrollTop);
        }
    }
    $('[data-toggle="tooltip"]').tooltip()
}
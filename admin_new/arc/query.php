<?php namespace App;

class query
{
    function adm_order_list_arc($search, $client_id, $end_date)
    {
        $filter = '';
        if ($search != '') $filter = ' && (order_shop.fio like "%' . $search . '%") ';
        if ($client_id != -1) $filter .= ' && (users.id = ' . $client_id . ') ';
        if ($end_date != 0) $filter .= ' && (order_shop.date_order < "' . $end_date . '") ';
        return 'select order_shop.id,
                       order_shop.date_order,
                       order_shop.fio,
                       order_shop.phone,
                       order_shop.status_pay as pay,
                       status.name as status,
                       order_shop.status_agreed,
                       order_shop.status_pay,
                       order_shop.send_invoice,
                       order_shop.status as status_id,
                       order_shop.adr,
                       order_shop.dop_fld,
                       order_shop.comment_my,
                       delivery.name as delivery_name,
                       delivery.id as delivery_id,
                       users.name as client_name,
                       users.bonus,
                       order_shop.bonus as use_bonus,
                       order_shop.number,
                       sum(body_order.price * body_order.amount) as order_sum
                from order_shop
                inner join status on (status.id = order_shop.status)
                inner join body_order on (body_order.parent = order_shop.id)
                inner join delivery on (delivery.id = order_shop.delivery)
                left join users on (users.id = order_shop.user_id)
                where (order_shop.del = 0)' . $filter . '
                group by order_shop.id,
                         order_shop.date_order,
                         order_shop.fio,
                         order_shop.phone,
                         status.name,
                         order_shop.status_agreed,
                         order_shop.status_pay,
                         order_shop.status,
                         order_shop.adr,
                         order_shop.dop_fld,
                         order_shop.comment_my,
                         delivery.name,
                         delivery.id,
                         users.name,
                         users.bonus,
                         order_shop.bonus,
                         order_shop.number
                order by order_shop.date_order desc';
    }

    function adm_order_single($id)
    {
        return 'select order_shop.id,
                       order_shop.date_order,
                       order_shop.fio,
                       order_shop.phone,
                       order_shop.email,
                       order_shop.comment,
                       order_shop.comment_my,
                       order_shop.adr,
                       order_shop.date_of_delivery,
                       order_shop.date_of_cur,
                       order_shop.time_intervals,
                       order_shop.status,
                       order_shop.status_pay as pay,
                       order_shop.status_agreed as agreed,
                       order_shop.dop_fld,
                       order_shop.send_invoice,
                       order_shop.post_id,
                       order_shop.bonus,
                       order_shop.number,
                       body_order.id as order_item,
                       body_order.id_tovar,
                       body_order.price,
                       body_order.amount,
                       tovar.name as tovar,
                       tovar.art,
                       delivery.name as delivery_name,
                       delivery.id as delivery_id
                  from order_shop
                  left join body_order on (order_shop.id = body_order.parent)
                  left join tovar on (body_order.id_tovar = tovar.id)
                  inner join delivery on (delivery.id = order_shop.delivery)
                  where (order_shop.del = 0) and
                        (order_shop.id = ' . $id . ')
                  order by tovar.usluga,
                           tovar.name';
    }

    function adm_status_list()
    {
        return 'select * from status';
    }

    function adm_tovar_list($word)
    {
        return 'select tovar.id,
                       tovar.name,
                       tovar.price
                from tovar
                where (tovar.del = 0) &&
                      ((tovar.name like "%' . $word . '%") || (tovar.id = ' . (int)$word . '))';
    }
}

?>

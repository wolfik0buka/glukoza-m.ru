<?php namespace App;
class query {
    public function adm_users_list($search, $sort) { // Список зарегистрированных пользователей
        $filter = '';
        if ($search != '') $filter = ' && ((users.name like "%'.$search.'%")
                                        || (users.email like "%' . $search . '%")
                                        || (users.phone like "%' . $search . '%"))';
        return 'select users.id,
                       users.name,
                       users.email,
                       users.phone,
                       users.bonus,
                       sum(body_order.price * body_order.amount) as oborot
                from users
                left join order_shop on (order_shop.user_id = users.id) &&
                                        (order_shop.status_pay != 0)
                left join body_order on (body_order.parent = order_shop.id)
                where (users.del = 0)'.$filter.'
                group by users.id,
                         users.name,
                         users.email,
                         users.phone,
                         users.bonus
                order by oborot '.$sort.',
                         users.name';
    }
    public function adm_orders_list($id) {       // Список заказов пользователя
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
                       sum(body_order.price * body_order.amount) as order_sum
                from order_shop
                inner join status on (status.id = order_shop.status)
                inner join body_order on (body_order.parent = order_shop.id)
                inner join delivery on (delivery.id = order_shop.delivery)
                inner join users on (users.id = order_shop.user_id)
                where (order_shop.del = 0) && (users.id = '.$id.')
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
                         delivery.name
                order by order_shop.date_order desc';
    }
}
?>

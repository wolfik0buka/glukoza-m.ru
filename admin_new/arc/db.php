<?php namespace App;
header('Content-Type: text/xml; charset=utf-8');
require_once '../../php/mysqlconnect_new.php';
require_once 'query.php';
require_once 'function.php';
$txt = 'Ошибка';
$go_url = '';
$id = '';
$block = '';
$val = -1;
$sum_all = 0;
$sum_pay = 0;
$bonus = 0;
$date_delivery = strftime('%d.%m.%Y', (time() + 60 * 60 * 24));
switch ($_POST['doIt']) {
    case ('save_new_price'):
        $stmt = $mysqli->prepare('update body_order set price = ? where id = ?');
        $stmt->bind_param('di', $_POST['price'], $_POST['id']);
        if ($stmt->execute()) {
            $id = $_POST['id'];
            $val = $_POST['price'];
        }
        break;

    case ('save_date_of_delivery'):
        $date = strftime('%Y-%m-%d', strtotime($_POST['date_of_delivery']));
        if ($mysqli->query('update order_shop set date_of_delivery = "' . $date . '" where id = ' . $_POST['id'])) {
            $txt = 'ok';
        } else {
            $txt = $stmt->error;
        }
        break;
    case ('save_fld'):
        $stmt = $mysqli->prepare('update order_shop set ' . $_POST['fld'] . ' = ? where id = ?');
        $val = $_POST['val'];
        if ($_POST['fld'] == 'date_of_delivery') {
            $val = strftime('%Y-%m-%d', strtotime($_POST['val']));
        }
        $stmt->bind_param('si', $val, $_POST['id']);
        if ($stmt->execute()) {
            $txt = $_POST['val'];
            $id = $_POST['fld'];
        }
        break;

    case ('pereschet'):
        $dicQ = new query();
        $res = $mysqli->query($dicQ->adm_order_single($_POST['order']));
        $sum_all = 0;
        $sum_item = 0;
        $comment = '';
        $dost = '';
        $t_header = '';
        $cnt = 1;
        while ($row = $res->fetch_object()) {
            $bonus = $row->bonus;
            $sum_item = $row->amount * $row->price;
            $sum_all = $sum_all + $sum_item;
            $art = '';
            if ($row->id_tovar != 71) {
                $art = str_repeat('0', 5 - strlen($row->id_tovar)) . $row->id_tovar;
                $disabled = 'disabled: false';
            } else {
                $disabled = 'disabled: true';
            }
            $t_header .= '<tr>
	                          <td>' . $cnt . '</td>
	                          <td>' . $art . '</td>
	                          <td>' . $row->tovar . '</td>
	                          <td><input type="text" class="spin" id="amount_' . $row->order_item . '" value="' . $row->amount . '"></td>
	                          <td class="td_price" style="text-align: right;">
	                              <span>' . number_format($row->price, 2, ',', ' ') . '</span>&nbsp;&nbsp;
	                              <i class="fa fa-pencil" order_item="' . $row->order_item . '"></i>
	                          </td>
	                          <td style="text-align: right;">' . number_format($sum_item, 2, ',', ' ') . '</td>
	                          <td><i onClick="del_order_item(' . $_POST['order'] . ', ' . $row->order_item . ')" class="red fa fa-trash-o"></i></td>
	                      </tr>
	                      <script>
	                          $("input#amount_' . $row->order_item . '").spinner({
                                  min: 1,
                                  stop: function(event, ui) {
                                      change_amount(' . $row->order_item . ',this.value, ' . $_POST['order'] . ');
                                  },
                                  ' . $disabled . '
                              });
                          </script>';
            $cnt++;
        }
        $t_header .= '<tr>
                              <td colspan="2"></td>
                              <td>
                                  <a class="uni-button" onClick="open_add_to_order(' . $_POST['order'] . ')">
                                      <i class="fa fa-plus-circle"></i>&nbsp Добавить товар
                                  </a>
                              </td>
                              <td colspan="2" style="text-align: right"><b>Итого:</b></td>
                              <td style="text-align: right;"><b>' . number_format($sum_all, 2, ',', ' ') . '</b></td>
                              <td></td>
                          </tr>
                          <tr>
                              <td colspan="5" style="text-align: right"><b>Использовано бонусов:</b></td>
                              <td style="text-align: right;"><b>' . number_format($bonus, 2, ',', ' ') . '</b></td>
                              <td></td>
                          </tr>
                          <tr>
                              <td colspan="5" style="text-align: right"><b>К оплате:</b></td>
                              <td style="text-align: right;"><b>' . number_format(($sum_all - $bonus), 2, ',', ' ') . '</b></td>
                              <td></td>
                          </tr>';

        $txt = $t_header;
        break;

    case ('change_amount'):
        $dicQ = new query();
        $res = $mysqli->query('update body_order set amount=' . $_POST['amount'] . ' where id=' . $_POST['item']);
        $res = $mysqli->query($dicQ->adm_order_single($_POST['order']));
        $sum_item = 0;
        $comment = '';
        $dost = '';
        $t_header = '';
        $cnt = 1;
        while ($row = $res->fetch_object()) {
            $bonus = $row->bonus;
            $sum_item = $row->amount * $row->price;
            $sum_all = $sum_all + $sum_item;
            $art = '';
            if ($row->id_tovar != 71) {
                $art = str_repeat('0', 5 - strlen($row->id_tovar)) . $row->id_tovar;
                $disabled = 'disabled: false';
            } else {
                $disabled = 'disabled: true';
            }
            $t_header .= '<tr>
	                          <td>' . $cnt . '</td>
	                          <td>' . $art . '</td>
	                          <td>' . $row->tovar . '</td>
	                          <td><input type="text" class="spin" id="amount_' . $row->order_item . '" value="' . $row->amount . '"></td>
	                          <td class="td_price" style="text-align: right;">
	                              <span>' . number_format($row->price, 2, ',', ' ') . '</span>&nbsp;&nbsp;
	                              <i class="fa fa-pencil" order_item="' . $row->order_item . '"></i>
	                          </td>
	                          <td style="text-align: right;">' . number_format($sum_item, 2, ',', ' ') . '</td>
	                          <td><i onClick="del_order_item(' . $_POST['order'] . ', ' . $row->order_item . ')" class="red fa fa-trash-o"></i></td>
	                      </tr>
	                      <script>
	                          $("input#amount_' . $row->order_item . '").spinner({
                                  min: 1,
                                  stop: function(event, ui) {
                                      change_amount(' . $row->order_item . ',this.value, ' . $_POST['order'] . ');
                                  },
                                  ' . $disabled . '
                              });
                          </script>';
            $cnt++;
        }
        $t_header .= '<tr>
                              <td colspan="2"></td>
                              <td>
                                  <a class="uni-button" onClick="open_add_to_order(' . $_POST['order'] . ')">
                                      <i class="fa fa-plus-circle"></i>&nbsp Добавить товар
                                  </a>
                              </td>
                              <td colspan="2" style="text-align: right"><b>Итого:</b></td>
                              <td style="text-align: right;"><b>' . number_format($sum_all, 2, ',', ' ') . '</b></td>
                              <td></td>
                          </tr>
                          <tr>
                              <td colspan="5" style="text-align: right"><b>Использовано бонусов:</b></td>
                              <td style="text-align: right;"><b>' . number_format($bonus, 2, ',', ' ') . '</b></td>
                              <td></td>
                          </tr>
                          <tr>
                              <td colspan="5" style="text-align: right"><b>К оплате:</b></td>
                              <td style="text-align: right;"><b>' . number_format(($sum_all - $bonus), 2, ',', ' ') . '</b></td>
                              <td></td>
                          </tr>';
        $txt = $t_header;
        break;

    case ('get_order_list_arc'):
        $search = '';
        $client_id = -1;
        if (isset($_POST['client_id'])) $client_id = $_POST['client_id'];
        if ((isset($_POST['search'])) && ($_POST['search'] != '')) $search = $_POST['search'];
        $dicQ = new query();
        $res = $mysqli->query($dicQ->adm_order_list_arc($search, $client_id, '2017-01-01'));
        $txt = '<table class="panelTable order_list">';
        $cnt = 1;
        $client = '';
        while ($row = $res->fetch_object()) {
            $class_pay = '';
            $id = $client_id;
            $bonus = $row->bonus;
            if (($client_id != -1) && ($cnt == 1)) {
                $val = $row->client_name;
                $client = '&client_id=' . $client_id;
            }
            $date_order = strftime('%d.%m.%Y<br>(%H:%M)', strtotime($row->date_order));
            $sum_all = $sum_all + $row->order_sum;
            switch ($row->pay) {
                case (1):
                    $class_pay = 'pay';
                    $name_pay = 'Оплачен';
                    $sum_pay = $sum_pay + $row->order_sum;
                    break;
                case (0):
                    $class_pay = 'no_pay';
                    $name_pay = 'Не оплачен';
                    if ($row->send_invoice == 1) $class_pay = 'send_invoice';
                    break;
            }
            switch ($row->status_agreed) {
                case (1):
                    $agreed = 'green';
                    $agreed_alt = 'Согласовано';
                    break;
                case (0):
                    $agreed = 'red';
                    $agreed_alt = 'Не согласовано';
                    break;
            }
            switch ($row->status_id) {
                case (1):
                    $delivery = 'red';
                    break;
                case (2):
                    $delivery = 'yellow';
                    break;
                case (3):
                    $delivery = 'green';
                    break;
                case (4):
                    $delivery = 'black';
                    $agreed = 'black';
                    break;
            }
            $dost = get_delivery($row);
            $number = str_repeat('0', 5 - strlen($row->number)) . $row->number . '\\' . strftime('%y', strtotime($row->date_order));
            $txt .= '<tr id="str' . $row->id . '">
                             <td>' . $number . '</td>
                             <td>' . $date_order . '</td>
                             <td>' . $row->fio . '</td>
                             <td>' . $row->delivery_name . '</td>
                             <td><i class="' . $agreed . ' icon fa fa-thumbs-o-up" title="' . $agreed_alt . '"></i></td>
                             <td><i class="' . $delivery . ' icon fa fa-truck" title="' . $row->status . '"></i></td>
                             <td>' . number_format(($row->order_sum - $row->use_bonus), 2, ',', ' ') . '</td>
                             <td>' . $name_pay . '</td>
                             <td>
                                <a href="index.php?page=order&id=' . $row->id . $client . '"><i class="blue button fa fa-pencil"></i></a>
                                <!-- <i onClick="delOrder(' . $row->id . ')" class="button red fa fa-trash-o"></i> -->
                             </td>
                             <td>' . $row->comment_my . '</td>
                         </tr>';
            $cnt++;
        }
        if ($cnt == 1) $txt .= '<tr><td colspan="10" style="text-align: center">Заказов нет</td></tr>';
        $txt .= '</table>';
        $sum_all = number_format($sum_all, 2, ',', ' ');
        $sum_pay = number_format($sum_pay, 2, ',', ' ');
        break;

    case ('get_tovar_list'):
        $dicQ = new query();
        $res = $mysqli->query($dicQ->adm_tovar_list($_POST['word']));
        $txt = '<table>';
        $cnt = 1;
        while ($row = $res->fetch_object()) {
            $txt .= '<tr id="str' . $row->id . '">
                             <td style="text-align: right;">' . str_repeat('0', 5 - strlen($row->id)) . $row->id . '</td>
                             <td style="text-align: left;">' . $row->name . '</td>
                             <td style="text-align: right;width:60px;">' . number_format($row->price, 2, ',', ' ') . '</td>
                             <td>
                                 <a class="uni-button" onClick="add_to_order(' . $_POST['order'] . ', ' . $row->id . ')">+</a>
                             </td>
                         </tr>';
            $cnt++;
        }
        $txt .= '</table>';
        break;

    case ('del_order'):
        $query = 'delete from order_shop where (id = ' . $_POST['id'] . ')';
        if ($mysqli->query($query)) {
            $txt = 'Удалено';
            $id = $_POST['id'];
        }
        break;

    case ('del_order_item'):
        $query = 'delete from body_order where (id = ' . $_POST['id'] . ')';
        if ($mysqli->query($query)) {
            $txt = 'Удалено';
            $id = $_POST['order'];
        }
        break;

    case ('change_status'):
        $time = 0;
        $val = 0;
        if ($_POST['status'] == 2) {
            $time = time();
            $val = strftime('%d.%m.%Y', $time);
        }
        $query = 'update order_shop
                      set status=' . $_POST['status'] . ',
                          date_of_cur=' . $time . '
                      where (id = ' . $_POST['order'] . ')';
        if ($mysqli->query($query)) $txt = 'Обновлено';
        break;

    case ('change_delivery'):
        $query = 'update order_shop
                      set delivery = ' . $_POST['delivery'] . '
                      where (id = ' . $_POST['order'] . ')';
        if ($mysqli->query($query)) {
            $res = $mysqli->query('select adr from order_shop where id = ' . $_POST['order']);
            while ($row = $res->fetch_object()) $txt = $row->adr;
            $id = $_POST['delivery'];
        }
        break;

    case ('add_post_id'):
        $query = 'update order_shop
                      set status = 3,
                          post_id = "' . $_POST['post_id'] . '",
                          status_agreed = 1
                      where (id = ' . $_POST['order_id'] . ')';
        if ($mysqli->query($query)) {
            $txt = 'ok';
        }

        break;

    case ('change_interval'):
        $query = 'update order_shop set time_intervals="' . $_POST['status'] . '" where (id = ' . $_POST['order'] . ')';
        if ($mysqli->query($query)) $txt = 'Обновлено';
        break;

    case ('add_to_order'):
        $stmt = $mysqli->prepare('insert into body_order (parent, id_tovar, price, amount) values (?,?,?,?)');
        $res = $mysqli->query('select tovar.price from tovar where (tovar.id = ' . $_POST['id'] . ')');
        $amount = 1;
        while ($row = $res->fetch_object()) $stmt->bind_param('iidi', $_POST['order'], $_POST['id'], $row->price, $amount);
        if ($stmt->execute()) {
            $txt = 'ok';
            $go_url = '/admin_new/index.php?page=order&id=' . $_POST['order'];
        }
        break;

    case ('save_comment_my'):
        $stmt = $mysqli->prepare('update order_shop set comment_my=? where (id = ?)');
        $stmt->bind_param('si', $_POST['comment_my'], $_POST['order']);
        if ($stmt->execute()) $txt = 'Сохранено';
        break;

    case ('change_flag'):
        $stmt = $mysqli->prepare('update order_shop set status_' . $_POST['fld'] . '=? where (id = ?)');
        switch ($_POST['c_val']) {
            case (0):
                $c_val = 1;
                break;
            case (1):
                $c_val = 0;
                break;
        }
        $stmt->bind_param('ii', $c_val, $_POST['order']);
        if ($stmt->execute()) {
            $txt = 'Сохранено';
            $val = $c_val;
            $id = $_POST['fld'];
            $res = $mysqli->query('select user_id from order_shop where (id = ' . $_POST['order'] . ')');
            while ($row = $res->fetch_object()) {
                counting_bonuses($row->user_id);
                $id = $row->user_id;
            }
        }
        break;

}
echo '<?xml version="1.0" encoding="utf-8"?>
          <box>
              <head date_delivery="' . $date_delivery . '" id="' . $id . '" val="' . $val . '" sum_all="' . $sum_all . '" sum_pay="' . $sum_pay . '" bonus="' . $bonus . '"><![CDATA[' . $txt . ']]></head>
              <go_url><![CDATA[' . $go_url . ']]></go_url>
          </box>';
?>
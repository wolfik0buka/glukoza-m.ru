<?php namespace App;
function delivery_list($id_order, $id_delivery) {
    global $mysqli;
    $dicQ = new query();
    $res = $mysqli->query('select id, name from delivery');
    $html = '<select id="delivery_id" name="delivery_id" onChange="change_delivery('.$id_order.', this.value)">';
    while($row = $res->fetch_object()) {
        $selected = '';
        if ($id_delivery == $row->id) {$selected = ' selected';}
        $html .= '<option value="'.$row->id.'"'.$selected.'>'.$row->name.'</option>';
    }
    $html .= '</select>';
    return $html;
}

function get_delivery($row) {
    $dost = '<tr id="head_delivery">
                 <td colspan="3">Способ получения: '.delivery_list($row->id, $row->delivery_id).'</td>
             </tr>';
    if (($row->delivery_id == '2') || ($row->delivery_id == '3') || ($row->delivery_id == '4')) {
        if ($row->delivery_id == '3') $dost .= '<tr id="date_delivery">
                                                    <td>Дата доставки:</td>
                                                    <td id="date_of_delivery">'.strftime('%d.%m.%Y', strtotime($row->date_of_delivery)).'</td>
                                                    <td id="date_of_delivery_but"><i class="fa fa-pencil" onClick="edit_fld(\'date_of_delivery\')"></i></td>
                                                </tr>';
        $dost .= '<tr id="adr_delivery">
                      <td>Адрес доставки:</td>
                      <td id="adr">'.$row->adr.'</td>
                      <td id="adr_but"><i class="fa fa-pencil" onClick="edit_fld(\'adr\')"></i></td>
                  </tr>';
    }
    return $dost;
}

function counting_bonuses($id) {
    $ch = curl_init();
    $post_arr = [];
    $post_arr['user_id'] = $id;
    curl_setopt($ch, CURLOPT_URL, 'http://'.$_SERVER['SERVER_NAME'].'/php/counting-bonuses.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_arr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch);
    curl_close($ch);
}
?>
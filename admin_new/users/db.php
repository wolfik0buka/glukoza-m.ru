<?php namespace App;
header('Content-Type: text/xml; charset=utf-8');
include '../../php/mysqlconnect_new.php';
include 'query.php';
$txt = 'Ошибка';
$go_url = '';
$id = '';
$block = '';
$val = -1;
$sort = 'desc';
switch ($_POST['doIt']) {
    case ('get_users_list'):
        $search = '';
        if ((isset($_POST['search'])) && ($_POST['search'] != '')) {
            $search = $_POST['search'];
            $search_param = '&search=' . $search;
        }
        $sort = $_POST['sort'];
        $dicQ = new query();
        $res = $mysqli->query($dicQ->adm_users_list($search, $sort));
        $txt = '';
        $cnt = 1;
        while ($row = $res->fetch_object()) {
            $txt .= '<tr id="str_' . $row->id . '">
                             <td>' . $cnt . '</td>
                             <td>' . $row->name . '</td>
                             <td>' . number_format($row->oborot, 2, ',', ' ') . '</td>
                             <td>' . $row->bonus . '</td>
                             <td>' . $row->phone . '</td>
                             <td>' . $row->email . '</td>
                             <td>
                                 <a href="/admin_new/index.php?page=order&client_id=' . $row->id . '">
                                     <i title="Заказы" class="green button fa fa-money"></i>
                                 </a>
                             </td>
                         </tr>';
            $cnt++;
        }
        break;
    case ('get_orders'):
        $dicQ = new query();
        $res = $mysqli->query($dicQ->adm_orders_list($_POST['id']));
        $txt = '<table class="panelTable">
                       <tr>
                           <th>';
        $cnt = 1;
        while ($row = $res->fetch_object()) {
            $txt .= '<tr id="str_' . $row->id . '">
                             <td>' . $cnt . '</td>
                             <td>' . $row->name . '</td>
                             <td>' . $row->phone . '</td>
                             <td>' . $row->email . '</td>
                             <td><i onClick="get_orders(' . $row->id . ')" title="Заказы" class="green button fa fa-money"></i></td>
                         </tr>';
            $cnt++;
        }
        break;


}
echo '<?xml version="1.0" encoding="utf-8"?>
          <box>
              <head id="' . $id . '" val="' . $val . '" sort="' . $sort . '"><![CDATA[' . $txt . ']]></head>
              <go_url><![CDATA[' . $go_url . ']]></go_url>
          </box>';
?>
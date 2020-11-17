<?php namespace App;
header('Content-Type: application/json');
include '../../php/mysqlconnect_new.php';
include 'query.php';
global $mysqli;
$txt = 'Ошибка';
$go_url = '';
$id = '';
$block = '';
$val = -1;
switch ($_GET['doIt']) {
    case ('get_tovar_list_json'):
        $dicQ = new query();
        $res = $mysqli->query($dicQ->adm_nom_list());
        $json = '{';
        $cnt = 1;
        while ($row = $res->fetch_object()) {
            $tovar_list[] = [
                "id" => $row->id,
                "art" => str_repeat('0', 5 - strlen($row->id)) . $row->id,
                "name" => $row->name
            ];

//            $saleChecked = '';
//            $hitChecked = '';
//            $podzakazChecked = '';
//            if ($row->sale == 1) $saleChecked = ' checked';
//            if ($row->hit == 1) $hitChecked = ' checked';
//            if ($row->podzakaz == 1) $podzakazChecked = ' checked';
//
//            $txt .= '<tr id="str' . $row->id . '">
//                             <td>' . $cnt . '</td>
//                             <td>' . str_repeat('0', 5 - strlen($row->id)) . $row->id . '</td>
//                             <td>' . $row->name . '</td>
//                             <td>' . $row->price . '</td>
//                             <td><input value="' . $row->sale . '" type="checkbox" id="sale' . $row->id . '" onChange="saleSwitch(' . $row->id . ',this.value)"' . $saleChecked . '></td>
//                             <td><input value="' . $row->hit . '" type="checkbox" id="hit' . $row->id . '" onChange="hitSwitch(' . $row->id . ',this.value)"' . $hitChecked . '></td>
//                             <td><input value="' . $row->podzakaz . '" type="checkbox" id="podzakaz' . $row->id . '" onChange="podzakazSwitch(' . $row->id . ',this.value)"' . $podzakazChecked . '></td>
//                             <td>
//                                 <a class="uni-button" href="/admin_new/index.php?page=nom&id=' . $row->id . $search_param . '">Свойства</a>
//                                 <a class="uni-button" onClick="delTovar(' . $row->id . ')">Удалить</a>
//                             </td>
//                         </tr>';
            $cnt++;
        }
            $json = [
                "iTotalRecords" => $res->num_rows,
                "iTotalDisplayRecords" => 50,
                "sEcho" => 50,
                "aaData" => $tovar_list
            ];
            $json = json_encode($json);
        break;
}
echo $json;
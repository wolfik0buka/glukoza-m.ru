<?php namespace App;
header('Content-Type: text/xml; charset=utf-8');
include '../../php/mysqlconnect_new.php';
include 'query.php';
global $mysqli;
$txt = 'Ошибка';
$go_url = '';
$id = '';
$block = '';
$val = -1;
switch ($_POST['doIt']) {
    case ("sale"):
        switch ($_POST["sale"]) {
            case(1):
                $val = 0;
                break;
            case(0):
                $val = 1;
                break;
        }
        $query = "update tovar set tovar.sale = " . $val . " where tovar.id = " . $_POST["idTovar"];
        if ($mysqli->query($query)) {
            $txt = "Данные отредактированы";
            $reload = 0;
            $id = $_POST["idTovar"];
        }
        break;
    case ("hit"):
        switch ($_POST["hit"]) {
            case(1):
                $val = 0;
                break;
            case(0):
                $val = 1;
                break;
        }
        $query = "update tovar set tovar.hit = " . $val . " where tovar.id = " . $_POST["idTovar"];
        if ($mysqli->query($query)) {
            $txt = "Данные отредактированы";
            $reload = 0;
            $id = $_POST["idTovar"];
        }
        break;
    case ("podzakaz"):
        switch ($_POST["podzakaz"]) {
            case(1):
                $val = 0;
                break;
            case(0):
                $val = 1;
                break;
        }
        $query = "update tovar set tovar.podzakaz = " . $val . " where tovar.id = " . $_POST["idTovar"];
        if ($mysqli->query($query)) {
            $txt = "Данные отредактированы";
            $reload = 0;
            $id = $_POST["idTovar"];
        }
        break;
    case ('get_tovar_list'):
        $search = '';
        if ((isset($_POST['search'])) && ($_POST['search'] != '')) {
            $search = $_POST['search'];
            $search_param = '&search=' . $search;
        }
        $dicQ = new query();
        $res = $mysqli->query($dicQ->adm_nom_list($search));
        $txt = '';
        $cnt = 1;
        while ($row = $res->fetch_object()) {
            $saleChecked = '';
            $hitChecked = '';
            $podzakazChecked = '';
            if ($row->sale == 1) $saleChecked = ' checked';
            if ($row->hit == 1) $hitChecked = ' checked';
            if ($row->podzakaz == 1) $podzakazChecked = ' checked';
            $txt .= '<tr id="str' . $row->id . '">
                             <td>' . $cnt . '</td>
                             <td>' . str_repeat('0', 5 - strlen($row->id)) . $row->id . '</td>
                             <td>' . $row->name . '</td>
                             <td><input value="' . $row->sale . '" type="checkbox" id="sale' . $row->id . '" onChange="saleSwitch(' . $row->id . ',this.value)"' . $saleChecked . '></td>
                             <td><input value="' . $row->hit . '" type="checkbox" id="hit' . $row->id . '" onChange="hitSwitch(' . $row->id . ',this.value)"' . $hitChecked . '></td>
                             <td><input value="' . $row->podzakaz . '" type="checkbox" id="podzakaz' . $row->id . '" onChange="podzakazSwitch(' . $row->id . ',this.value)"' . $podzakazChecked . '></td>
                             <td>
                                 <a class="uni-button" href="/admin_new/index.php?page=nom&id=' . $row->id . $search_param . '">Свойства</a>
                                 <a class="uni-button" onClick="delTovar(' . $row->id . ')">Удалить</a>
                             </td>
                         </tr>';
            $cnt++;
        }
        break;
    case ('get_tovar_list_json'):
        $dicQ = new query();
        $res = $mysqli->query($dicQ->adm_nom_list());
        $txt = '';
        $cnt = 1;
        while ($row = $res->fetch_object()) {
            $saleChecked = '';
            $hitChecked = '';
            $podzakazChecked = '';
            if ($row->sale == 1) $saleChecked = ' checked';
            if ($row->hit == 1) $hitChecked = ' checked';
            if ($row->podzakaz == 1) $podzakazChecked = ' checked';
            $txt .= '<tr id="str' . $row->id . '">
                             <td>' . $cnt . '</td>
                             <td>' . str_repeat('0', 5 - strlen($row->id)) . $row->id . '</td>
                             <td>' . $row->name . '</td>
                             <td><input value="' . $row->sale . '" type="checkbox" id="sale' . $row->id . '" onChange="saleSwitch(' . $row->id . ',this.value)"' . $saleChecked . '></td>
                             <td><input value="' . $row->hit . '" type="checkbox" id="hit' . $row->id . '" onChange="hitSwitch(' . $row->id . ',this.value)"' . $hitChecked . '></td>
                             <td><input value="' . $row->podzakaz . '" type="checkbox" id="podzakaz' . $row->id . '" onChange="podzakazSwitch(' . $row->id . ',this.value)"' . $podzakazChecked . '></td>
                             <td>
                                 <a class="uni-button" href="/admin_new/index.php?page=nom&id=' . $row->id . $search_param . '">Свойства</a>
                                 <a class="uni-button" onClick="delTovar(' . $row->id . ')">Удалить</a>
                             </td>
                         </tr>';
            $cnt++;
        }
        break;
    case ('saveTovar'):
        if (!isset($_POST['price'])) {
            $query = 'update tovar
                      set tovar.name = ?,
                          tovar.desc_full = ?,
                          tovar.price_old = ?,
                          tovar.description = ?
                      where tovar.id = ?';
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('sssi', $_POST['name'],
                $_POST['desc_full'],
                $_POST['price_old'],
                $_POST['description'],
                $_POST['id']
            );
        } else {
            $query = 'update tovar
                      set tovar.name = ?,
                          tovar.desc_full = ?,
                          tovar.description = ?,
                          tovar.price = ?,
                          tovar.price_old = ?
                      where tovar.id = ?';
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('sssdi', $_POST['name'],
                $_POST['desc_full'],
                $_POST['description'],
                $_POST['price'],
                $_POST['price_old'],
                $_POST['id']
            );
        }
        if ($stmt->execute()) $txt = 'Сохранено';
        break;
    case ('addTovar'):
        $stmt = $mysqli->prepare('insert into tovar (tovar.name, tovar.desc_full, tovar.description)
                                      values (?,?,?)');
        $stmt->bind_param('sss', $_POST['name'],
            $_POST['desc_full'],
            $_POST['description']);
        if ($stmt->execute()) {
            $res_id = $mysqli->query('show table status like "tovar"');
            while ($row_id = $res_id->fetch_object()) $new_id = $row_id->Auto_increment - 1;
            $txt = 'Сохранено';
            $go_url = '/admin_new/index.php?page=nom&id=' . $new_id;
        }

        break;
    case ('delTovar'):
        $query = 'update tovar set del = 1 where (id = ' . $_POST['id'] . ')';
        if ($mysqli->query($query)) {
            $txt = 'Удалено';
            $id = $_POST['id'];
        }
        break;
    case ('insert_tovar'):
        $query = 'update tovar set ' . $_POST['block'] . ' = ' . $_POST['id'] . ' where (id = ' . $_POST['tovar'] . ')';
        $txt = $query;
        if ($mysqli->query($query)) $go_url = '/admin/index.php?page=nom&dop=' . $_POST['tovar'];
        break;
    case ('nom_to_div'):
        $dicQ = new query();
        $txt = '<div class="close" onclick="close_popup()">Закрыть</div>
                    <div style="padding: 0 20px; height: 500px; overflow: auto; display: block; text-align: left">';
        $res = $mysqli->query($dicQ->adm_nom_list_pic(0));
        while ($row = $res->fetch_object()) {
            $txt .= '<a class="dop_link" onClick="insert_tovar(\'' . $_POST['block'] . '\', ' . $_POST['id'] . ', ' . $row->id . ')">' . $row->art . ' - ' . $row->name_ru . '</a><br>';
        }
        $txt .= '</div>';
        $block = $_POST['block'];
        break;
}
echo '<?xml version="1.0" encoding="utf-8"?>
          <box>
              <head id="' . $id . '" val="' . $val . '"><![CDATA[' . $txt . ']]></head>
              <go_url><![CDATA[' . $go_url . ']]></go_url>
          </box>';
?>
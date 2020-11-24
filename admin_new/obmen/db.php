<?php namespace App;
header('Content-Type: text/xml; charset=utf-8');
include '../../php/mysqlconnect_new.php';
include '../../php/query.php';
global $mysqli;
$txt = 'Ошибка';
if (mysqli_connect_errno()) {
    $txt .= var_export(mysqli_connect_error(), true);
}else{
    $txt .= 'УС';
}

switch ($_POST['doIt']) {
    case('priv'):
        $stmt = $mysqli->prepare('update 1c_tovar set id_tovar = ? where id = ? ;');
        
        $stmt->bind_param('is', $_POST["id"], $_POST["cur_1c"]);
        $temp = $stmt->execute();       
        if ($temp) {$txt = 'Привязано';}
        else{
            $txt.=var_export($stmt->error_list, true);
        }
        break;
    case('unmount'):
        $stmt = $mysqli->prepare('update 1c_tovar set id_tovar = ? where id = ?');
        $a = null;
        $stmt->bind_param('is', $a, $_POST["id"]);
        if ($stmt->execute()) $txt = 'Отвязано';
        break;
    case('to_arc'):
        $stmt = $mysqli->prepare('update 1c_tovar set arc = ? where id = ?');
        $flag = 1;
        $stmt->bind_param('is', $flag, $_POST["id"]);
        if ($stmt->execute()) $txt = 'Перемещено в архив';
        break;
}
echo '<?xml version="1.0" encoding="utf-8"?>
          <box>
              <head><![CDATA[' . $txt . ']]></head>
          </box>';
?>
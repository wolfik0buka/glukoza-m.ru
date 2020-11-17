<?php namespace App;
header('Content-Type: text/xml; charset=utf-8');
include '../../php/mysqlconnect_new.php';
include 'query.php';
global $mysqli;
$txt = 'Ошибка';
switch ($_POST['doIt']) {
    case('priv'):
        $stmt = $mysqli->prepare('update 1c_tovar set id_tovar = ? where id = ?');
        $stmt->bind_param('is', $_POST["id"], $_POST["cur_1c"]);
        if ($stmt->execute()) $txt = 'Привязано';
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
<?php namespace App;
header('Content-Type: text/xml; charset=utf-8');
include '../../php/mysqlconnect_new.php';
$txt = "Ошибка";
switch ($_POST['doIt']) {
    case ('edit'):
        $stmt = $mysqli->prepare('update static_pages_new
                                      set static_pages_new.title = ?,
                                          static_pages_new.content = ?
                                      where static_pages_new.id = ?');
        $stmt->bind_param('ssi', $_POST['statName'], $_POST['stat'], $_POST['statId']);
        if ($stmt->execute()) $txt = 'Сохранено';
        break;
//        case ("add"):
//            $date = strftime("%Y-%m-%d", strtotime($_POST["date_event"]));
//            $query = ibase_prepare($dbh, "insert into events (event_title, event_date, event_content, event_type)
//                                          values (?,?,?,?)");
//            if (ibase_execute($query, $_POST["title"], $date, $_POST["content"], $_POST["type"])) $txt = "Сохранено";
//        break;
//        case ("del"):
//            $query = "update events
//                      set events.del = 1
//                      where events.id = ".$_POST["id"];
//            $mess = "Удалено";
//        break;
}
//if (mysql_query($query)) $txt = $mess; else $txt = $query;
echo '<?xml version="1.0" encoding="utf-8"?>
          <head><![CDATA[' . $txt . ']]></head>';
?>
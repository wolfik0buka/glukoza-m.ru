<?php
    header('Content-Type: text/xml; charset=utf-8');
    include '../../php/mysqlconnect.php';
    $txt = 'Ошибка';
    switch ($_POST['doIt']) {
    	case '1':
            $func = 'color_linker('.$_POST['tovar'].','.$_POST['color'].',1)';
            $query = 'insert into color_link (color_id, tovar_id) values ('.$_POST['color'].', '.$_POST['tovar'].')';
            if ($mysqli->query($query)) $func = 'color_linker('.$_POST['tovar'].','.$_POST['color'].',0)';
    	break;
    	case '0':
            $func = 'color_linker('.$_POST['tovar'].','.$_POST['color'].',0)';
            $query = 'delete from color_link where (color_link.tovar_id = '.$_POST['tovar'].') and (color_link.color_id = '.$_POST['color'].')';
            if ($mysqli->query($query)) $func = 'color_linker('.$_POST['tovar'].','.$_POST['color'].',1)';
    	break;
    }
    echo '<?xml version="1.0" encoding="utf-8"?>
          <head cat="'.$_POST['color'].'" func="'.$func.'"><![CDATA[]]></head>';
?>
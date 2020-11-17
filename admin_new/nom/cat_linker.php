<?php
    header('Content-Type: text/xml; charset=utf-8');
    include '../../php/mysqlconnect_new.php';
    $txt = 'Ошибка';
    switch ($_POST['doIt']) {
    	case '1':
            $func = 'cat_linker('.$_POST['tovar'].','.$_POST['cat'].',1)';
            $query = 'insert into link_tovar (id_cat, id_tovar) values ('.$_POST['cat'].', '.$_POST['tovar'].')';
            if ($mysqli->query($query)) $func = 'cat_linker('.$_POST['tovar'].','.$_POST['cat'].',0)';
    	break;
    	case '0':
            $func = 'cat_linker('.$_POST['tovar'].','.$_POST['cat'].',0)';
            $query = 'delete from link_tovar where (link_tovar.id_tovar = '.$_POST['tovar'].') and (link_tovar.id_cat = '.$_POST['cat'].')';
            if ($mysqli->query($query)) $func = 'cat_linker('.$_POST['tovar'].','.$_POST['cat'].',1)';
    	break;
    }
    echo '<?xml version="1.0" encoding="utf-8"?>
          <head cat="'.$_POST['cat'].'" func="'.$func.'"><![CDATA[]]></head>';    
?>
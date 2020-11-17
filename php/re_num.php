<?php
require_once 'mysqlconnect_new.php';
$query = 'select * from order_shop order by date_order';
$res = $mysqli->query($query);
$year_cnt = 1;
$year = -1;
while ($row = $res->fetch_object()) {
    if (strftime('%y', strtotime($row->date_order)) != $year) {
        $year = strftime('%y', strtotime($row->date_order));
        $year_cnt = 1;
    }
    $mysqli->query('update order_shop set number = '.$year_cnt.' where (id = '.$row->id.')');
    $year_cnt++;
}
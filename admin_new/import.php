<?php
include '../php/mysqlconnect_new.php';
$xml = simplexml_load_file('../share_1c/sklad.xml');

$arr_sklad = [];
$arr_amount = [];

$res_sklad = $mysqli->query('select * from 1c_tovar');
while ($row_sklad = $res_sklad->fetch_object()) $arr_sklad[$row_sklad->id] = $row_sklad->price;

$res_amt = $mysqli->query('select * from 1c_amount');
while ($row_amt = $res_amt->fetch_object()) $arr_amount[$row_amt->id_tovar][$row_amt->id_sklad] = $row_amt->amount;

//var_dump($arr_amount);

$stmt_ins = $mysqli->prepare('insert into 1c_tovar (id, name, price)
                              values (?,?,?)');

$stmt_upd = $mysqli->prepare('update 1c_tovar set name=?, price=? where id=?');

$stmt_amt_ins = $mysqli->prepare('insert into 1c_amount (id_tovar, id_sklad, amount)
                                  values (?,?,?)');

$stmt_amt_upd = $mysqli->prepare('update 1c_amount set amount=?, updated_at=? where (id_tovar=?) && (id_sklad=?)');

$time_upd = strftime("%Y-%m-%d %H:%M:%S", time());

foreach ($xml->tovars->tovar as $tovar) {
    foreach ($tovar->prices->price as $price) {
        if ($price['type'] == '000000001') {
            if (array_key_exists(strval($tovar['id']), $arr_sklad)) {
                $cena = str_replace(',', '.', $price);
//                if ($arr_sklad[strval($tovar['id'])] != $cena) {
                    $stmt_upd->bind_param('sds', $tovar->name, $cena, $tovar['id']);
                    if ($stmt_upd->execute()) echo $tovar['id'] . ' - up<br>';
//                }
            } else {
                $stmt_ins->bind_param('ssd', $tovar['id'], $tovar->name, $cena);
                if ($stmt_ins->execute()) echo 'ins<br>';
            }
        }
    }
    foreach ($tovar->amounts->amount as $amount) {
        if (isset($arr_amount[strval($tovar['id'])])) {
            if (isset($arr_amount[strval($tovar['id'])][strval($amount['sklad'])])) {
                $stmt_amt_upd->bind_param('isss', $amount, $time_upd, $tovar['id'], $amount['sklad']);
                if ($stmt_amt_upd->execute()) {
                    echo 'upd - amount' . $tovar['id'] . '<br>';
                }
            } else {
                $stmt_amt_ins->bind_param('ssi', $tovar['id'], $amount['sklad'], $amount);
                if ($stmt_amt_ins->execute()) {
                    echo 'ins - amount - sklad<br>';
                }
            }
        } else {
            $stmt_amt_ins->bind_param('ssi', $tovar['id'], $amount['sklad'], $amount);
            if ($stmt_amt_ins->execute()) echo 'ins - amount - tovar<br>';
        }
    }
}
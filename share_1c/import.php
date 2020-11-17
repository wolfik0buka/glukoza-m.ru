<?php header('Access-Control-Allow-Origin: *');
include '../php/mysqlconnect_new.php';
$xml = simplexml_load_file('https://143824.selcdn.ru/1c.glukoza-med.ru/sklad.xml');

$reset = $mysqli->query('update 1c_tovar set pres = 0');

$arr_sklad = [];
$arr_amount = [];

$res_sklad = $mysqli->query('select * from 1c_tovar');
while ($row_sklad = $res_sklad->fetch_object()) {
    $arr_sklad[$row_sklad->id] = $row_sklad->price;
}

$res_amt = $mysqli->query('select * from 1c_amount');
while ($row_amt = $res_amt->fetch_object()) {
    $arr_amount[$row_amt->id_tovar][$row_amt->id_sklad] = $row_amt->amount;
}

$stmt_ins = $mysqli->prepare("insert into 1c_tovar (`id`, `name`, `price`, `pres`) values (?,?,?,?)");

$stmt_upd = $mysqli->prepare('update 1c_tovar set name=?, price=?, pres=? where id=?');

$stmt_amt_ins = $mysqli->prepare('insert into 1c_amount (id_tovar, id_sklad, amount) values (?,?,?)');

$stmt_amt_upd = $mysqli->prepare('update 1c_amount set amount=?, updated_at=? where (id_tovar=?) && (id_sklad=?)');

$time_upd = strftime("%Y-%m-%d %H:%M:%S", time());

foreach ($xml->tovars->tovar as $tovar) {
    foreach ($tovar->prices->price as $price) {
        if ( (string) $price['type'] === '000000001') {
            echo '<br/><br/>'.$tovar['id'].' взят в работу';
            $cena = str_replace(',', '.', $price);

            if (array_key_exists((string) $tovar['id'], $arr_sklad)) {
                echo ', найден в 1c_tovar';

                /*
                 *  Проверяем наличие только в Питере
                 */
                $pres_on = 0;
                foreach ($tovar->amounts->amount as $amount) {
                    if (
                           ((string) $amount['sklad'] === '000000001' && ((int) $amount > 0))
                        || ((string) $amount['sklad'] === '000000002' && ((int) $amount > 0))
                        || ((string) $amount['sklad'] === 'УТ0000003' && ((int) $amount > 0))
                        || ((string) $amount['sklad'] === 'УТ0000004' && ((int) $amount > 0))
                    ) {
                        $pres_on = 1;
                    }

                }

                $stmt_upd->bind_param('sdis', $tovar->name, $cena, $pres_on, $tovar['id']);
                if ($stmt_upd->execute()) echo ', товар обновлен';
            } else {
                echo ', отсутствует в 1c_tovar';
                $product_name = trim($tovar->name);
                $stmt_ins->bind_param('ssdi', $tovar['id'], $product_name, $cena, $pres_on);

                if ($stmt_ins->execute()) {
                    echo ', товар создан в 1c_tovar';
                }
            }
        }
    }
    foreach ($tovar->amounts->amount as $amount) {
        if (isset($arr_amount[(string) $tovar['id']])) {
            if (isset($arr_amount[ (string)$tovar['id'] ][ (string)$amount['sklad'] ])) {
                $stmt_amt_upd->bind_param('isss', $amount, $time_upd, $tovar['id'], $amount['sklad']);
                if ($stmt_amt_upd->execute()) {
                    echo ', обновлен на складе';
                }
            } else {
                $stmt_amt_ins->bind_param('ssi', $tovar['id'], $amount['sklad'], $amount);
                if ($stmt_amt_ins->execute()) {
                    echo ', добавлен на склад';
                }
            }
        } else {
            $stmt_amt_ins->bind_param('ssi', $tovar['id'], $amount['sklad'], $amount);
            if ($stmt_amt_ins->execute()) echo 'ins - amount - tovar<br>';
        }
    }
}

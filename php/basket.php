<?php
session_start();
header('Content-Type: text/xml; charset=utf-8');
require_once 'mysqlconnect_new.php';
require_once 'attache_sender.php';
require_once 'propis.php';
$tst = '';
switch ($_POST['do']) {
    case('delDostavka'):
        if (isset($_POST['where'])) $_SESSION['dostavka'] = $_POST['where'];
        $query = 'select tovar.id
                  from tovar
                  where (tovar.usluga = 1) and
                        (tovar.del = 0)';
        $res = $mysqli->query($query);
        while ($row = $res->fetch_object()) {
            foreach ($_SESSION["basket"] as $key => $value) {
                if ($value["idTov"] == $row->id) unset($_SESSION["basket"][$key]);
            }
        }
        $idTov = '';
        if (isset($_POST['idTov'])) $idTov = $_POST['idTov'];
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <head idTov="' . $idTov . '"><![CDATA[]]></head>';
        break;
    case('addToBasketDostavka'):
        if (isset($_POST['where'])) $_SESSION['dostavka'] = $_POST['where'];

        // Удаление текущей доставки - начало
        $query = 'select tovar.id
                  from tovar
                  where (tovar.usluga = 1) and
                        (tovar.del = 0)';
        $res = $mysqli->query($query);
        while ($row = $res->fetch_object()) {
            foreach ($_SESSION["basket"] as $key => $value) {
                if ($value["idTov"] == $row->id) unset($_SESSION["basket"][$key]);
            }
        }
        // Удаление текущей доставки - конец

        $query = 'select tovar.name,
                         tovar.price
                  from tovar
                  where (tovar.del = 0) and
                        (tovar.id = ' . $_POST['idTov'] . ')';

        // Если не почтой то $row->price
        // Если почта, то то что посчитали

        $res = $mysqli->query($query);
        while ($row = $res->fetch_object()) {
            $_SESSION["basket"][] = array("idTov" => $_POST['idTov'],
                "name" => $row->name,
                "price" => $row->price,
                "amount" => 1,
                "usluga" => 1
            );
        }
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <head idTov="' . $_POST['idTov'] . '"><![CDATA[(' . count($_SESSION['basket']) . ')]]></head>';
        break;

    case("addToBasket"):
        if (!isset($_SESSION["dostavka"])) $_SESSION["dostavka"] = 1;
        if (!isset($_SESSION['bonus'])) $_SESSION['bonus'] = 0;
        if (isset($_SESSION['basket'])) {
            $flag = 0;
            foreach ($_SESSION['basket'] as $key => $value) {
                if ($_POST['idTov'] == $value['idTov']) {
                    $_SESSION['basket'][$key]['amount']++;
                    $flag = 1;
                }
            }
            if ($flag == 0) $_SESSION["basket"][] = array("idTov" => $_POST["idTov"],
                "name" => $_POST["name"],
                "price" => $_POST["price"],
                "amount" => $_POST["amount"],
                "usluga" => 0);
        } else {
            $_SESSION["basket"][] = array("idTov" => $_POST["idTov"],
                "name" => $_POST["name"],
                "price" => $_POST["price"],
                "amount" => $_POST["amount"],
                "usluga" => 0);
        }
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <head tst="' . $tst . '"><![CDATA[(' . count($_SESSION['basket']) . ')]]></head>';
        break;
    case("clearBasket"):
        unset($_SESSION["basket"]);
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                    <head><![CDATA[(" . count($_SESSION["basket"]) . ")]]></head>";
        break;
    case('delBasketItem'):
        unset($_SESSION['basket'][$_POST['id']]);
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                    <head><![CDATA[Удалено]]></head>';
        break;

    case('reCheckout'):
        if (isset($_SESSION["basket"])) {
            $html = '<form>
                         <table class="basketTable">
                         <tr>
                             <th style="width: 410px; text-align: left;">Наименование</th>
                             <th style="width: 100px;">Кол-во, шт</th>
                             <th style="width: 100px; text-align: right;">Цена, руб</th>
                             <th style="width: 100px; text-align: right;">Сумма, руб</th>
                             <th style="width: 110px;">&nbsp;</th>
                         </tr>';
            $test = '';
            foreach ($_POST as $key => $value) {
                $key_num = explode('_', $key);
                if ($key_num[0] == 'amount') $_SESSION['basket'][$key_num[1]]['amount'] = $value;
                if ($key_num[0] == 'currf') {
                    foreach ($_SESSION['basket'] as $k => $v) {
                        if ($v['idTov'] == 982) unset ($_SESSION['basket'][$k]);
                    }
                    $_SESSION['basket'][] = array('amount' => 1, 'price' => $value, 'name' => 'Доставка курьером по РФ', 'idTov' => 982);
                }
                $test .= '_' . $value . '-' . $key_num[1];
            }
            $sumAll = 0;
            $_SESSION['sumAllDeliveryFree'] = 0;
            $test .= ' *** ';
            foreach ($_SESSION["basket"] as $key => $value) {
                $sumItem = $value["price"] * $value["amount"];
                $cnt = 1;
                $art = '';
                $test .= '_' . $value['amount'];
                $html .= '<tr id="tr' . $key . '">
                              <td>' . $value['name'] . $art . '</td>
                              <td style="text-align: center"><input class="amount' . $value['usluga'] . '" id="amount_' . $key . '" name="amount_' . $key . '" value="' . $value['amount'] . '"></td>
                              <td style="text-align: right">' . number_format($value['price'], 2, '-', ' ') . '</td>
                              <td style="text-align: right">' . number_format($sumItem, 2, '-', ' ') . '</td>
                              <td style="vertical-align: top"><input class="delItem" type="button" value="Удалить" onClick="delBasketItem(' . $key . ')" /></td>
                          </tr>';
                $cnt++;
                $sumAll = $sumAll + $sumItem;
            }
            $html .= '<tr id="podval">
                          <td colspan="3" style="text-align: right">Итого: </td>
                          <td style="text-align: right">' . number_format($sumAll, 2, '-', ' ') . '</td>
                          <td>&nbsp;</td>
                      </tr>';
            if (isset($_SESSION['user'])) {
                $html .= '<tr id="podval">
                          <td colspan="3" style="text-align: right">Будет списано бонусов: </td>
                          <td style="text-align: right">' . number_format($_SESSION['bonus'], 2, '-', ' ') . '</td>
                          <td>&nbsp;</td>
                      </tr>';
            }
            $html .= '<tr id="podval">
                          <td colspan="3" style="text-align: right; font-weight: bold;">К оплате: </td>
                          <td style="text-align: right">' . number_format($sumAll - $_SESSION['bonus'], 2, '-', ' ') . '</td>
                          <td>&nbsp;</td>
                      </tr>';
            $html .= '</table>';
            if (isset($_SESSION['user'])) {
                $query = 'select * from users where (id = ' . $_SESSION['user']['id'] . ')';
                $res = $mysqli->query($query);
                while ($row = $res->fetch_object()) $bonus = $row->bonus;
                if ($_SESSION['bonus'] == 0) {
                    $html .= '<div>
                                  Накоплено ' . $bonus . ' бонусов, использовать <input type="text" name="bonus" id="bonus" value="' . $bonus . '">
                                  <a class="uni-button" onClick="addBonus(' . $bonus . ')">Применить</a>
                              </div>';
                } else {
                    $html .= '<div>
                                  Накоплено ' . $bonus . ' бонусов, использовать ' . $_SESSION['bonus'] . ' <a class="uni-button" onClick="delBonus()">Отмена</a>
                              </div>';
                }
            }
            $html .= '</form>';
        } else {
            $html = '<div style="text-align: center; color: #666">Ваша корзина пуста</div>';
        }

        $xml = '<?xml version="1.0" encoding="utf-8"?>
                    <asd>
                    <head test="' . $test . '"><![CDATA[' . $html . ']]></head>
                    </asd>';
        break;

    case('getBasket'):
        $def_date = '';
        $def_date_js = '';
        if (!isset($_SESSION["basket"])) {
            $html = '<form><table class="basketTable">
                         <tr>
                             <th style="width:410px;text-align:left;">Наименование</th>
                             <th style="width:100px">Кол-во, шт</th>
                             <th style="width:100px;text-align:right;">Цена, руб</th>
                             <th style="width:100px;text-align:right;">Сумма, руб</th>
                             <th style="width:110px;">&nbsp;</th>
                         </tr>';
            $sumAll = 0;
            $_SESSION['sumAllDeliveryFree'] = 0;
            foreach ($_SESSION["basket"] as $key => $value) {
                $sumItem = $value["price"] * $value["amount"];
                $cnt = 1;
                $art = '';
                $html .= '<tr id="tr' . $key . '">
                              <td>' . $value['name'] . $art . '</td>
                              <td style="text-align: center"><input class="amount' . $value['usluga'] . '" id="amount_' . $key . '" name="amount_' . $key . '" value="' . $value['amount'] . '"></td>
                              <td style="text-align: right">' . number_format($value['price'], 2, '-', ' ') . '</td>
                              <td style="text-align: right">' . number_format($sumItem, 2, '-', ' ') . '</td>
                              <td style="vertical-align: top"><input class="delItem" type="button" value="Удалить" onClick="delBasketItem(' . $key . ')" /></td>
                          </tr>';
                $cnt++;
                $sumAll = $sumAll + $sumItem;
            }
            $html .= '<tr id="podval">
                          <td colspan="3" style="text-align: right">Итого: </td>
                          <td style="text-align: right">' . number_format($sumAll, 2, '-', ' ') . '</td>
                          <td>&nbsp;</td>
                      </tr>';
            if (isset($_SESSION['user'])) {
                $html .= '<tr id="podval">
                          <td colspan="3" style="text-align: right">Будет списано бонусов: </td>
                          <td style="text-align: right">' . number_format($_SESSION['bonus'], 2, '-', ' ') . '</td>
                          <td>&nbsp;</td>
                      </tr>';
            }
            $html .= '<tr id="podval">
                          <td colspan="3" style="text-align: right; font-weight: bold;">К оплате: </td>
                          <td style="text-align: right">' . number_format($sumAll - $_SESSION['bonus'], 2, '-', ' ') . '</td>
                          <td>&nbsp;</td>
                      </tr>';
            $html .= '</table>';
            if (isset($_SESSION['user'])) {
                $query = 'select * from users where (id = ' . $_SESSION['user']['id'] . ')';
                $res = $mysqli->query($query);
                while ($row = $res->fetch_object()) $bonus = $row->bonus;
                if ($_SESSION['bonus'] == 0) {
                    $html .= '<div>
                                  Накоплено ' . $bonus . ' бонусов, использовать <input type="text" name="bonus" id="bonus" value="' . $bonus . '">
                                  <a class="uni-button" onClick="addBonus(' . $bonus . ')">Применить</a>
                              </div>';
                } else {
                    $html .= '<div>
                                  Накоплено ' . $bonus . ' бонусов, использовать ' . $_SESSION['bonus'] . ' <a class="uni-button" onClick="delBonus()">Отмена</a>
                              </div>';
                }
            }
            $html .= '</form>';
            if ((!isset($_SESSION["promo"])) and (isset($_SESSION["user_id"]))) $html .= "Промо код (если есть) <input type=\"text\" id=\"promo\"> <button onClick=\"getSale()\" id=\"getSale\">Пересчитать</button>";
        } else {
            $html = '<div style="text-align: center; color: #666">Ваша! корзина пуста</div>';
        }
        switch ($_SESSION['dostavka']) {
            case(1):
                $dopFld = '<p><strong>Адрес магазина:</strong></p>
                           <p>Санкт-Петербург, Большой Сампсониевский пр., д. 62, оф. 202</p>';
                break;
            case(2):
                $dopFld = '<p><strong>Введите город доставки:</strong></p>
                           <div class="ui-widget city_list"><input type="text" name="city" id="city" style="width: 380px"></div>
                           <p><strong>Введите адрес доставки:</strong></p>
                           <textarea id="adr"></textarea>';
                break;
            case(3):
                $current_hour = strftime('%H', time());
                $current_day = strftime('%u', time());
                if ($current_day < 6) {
                    if ($current_hour < 16) {
                        $delta = 1;
                        if ($current_day == 5) $delta = 3;
                    } else {
                        $delta = 2;
                        if (($current_day == 4) || ($current_day == 5)) $delta = 4;
                    }
                }
                if ($current_day == 6) $delta = 3;
                if ($current_day == 7) $delta = 2;
                $def_timestamp = time() + 86400 * $delta;
                $def_date = strftime('%d.%m.%Y', $def_timestamp);
                $def_date_js = strftime('%m/%d/%Y', $def_timestamp);
                $dopFld = '<p><strong>Введите адрес доставки:</strong></p>
                           <textarea id="adr"></textarea>
                           <div class="clear"></div>
                           <p>Когда вы желаете получить заказ от курьера:</p>
                           <input type="text" readonly class="datepicker-here" name="date_of_delivery" id="date_of_delivery">
                           <select name="time_intervals" id="time_intervals">
                               <option value="Доставка до 16:00">Доставка до 16:00</option>
                               <option value="Доставка после 16:00">Доставка после 16:00</option>
                           </select>';
                break;
            case(4):
                $dopFld = '<p><strong>Введите почтовый индекс:</strong></p>
                           <div><input type="text" name="post_index" id="post_index" style="width: 380px"></div>
                           <p><strong>Введите адрес доставки:</strong></p>
                           <textarea id="adr"></textarea>';
                break;
            case(5):
                $dopFld = '<p><strong>Адрес магазина:</strong></p>
                           <p>Санкт-Петербург, ул. Сикейроса, д.10, корп.4, лит."А", ТК "Бульвар", помещение 4/2</p>';
                break;
        }
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <asd>
                    <head def_date_js="' . $def_date_js . '" def_date="' . $def_date . '" getTovar="' . $_SESSION['dostavka'] . '"><![CDATA[' . $html . ']]></head>
                    <dopFld><![CDATA[' . $dopFld . ']]></dopFld>
                </asd>';
        break;

    case("basketToOrder"):
        mb_internal_encoding("UTF-8");
        $use_bonus = 0;
        if ((isset($_SESSION['bonus'])) && (isset($_SESSION['user']))) $use_bonus = $_SESSION['bonus'];
        $date = strftime("%Y-%m-%d %H:%M:%S", time());
        $dateMy = strftime("%d-%m-%Y", time());
        $fio = $_POST["fam"] . " " . $_POST["name"];
        $tel = "+7 (" . $_POST["pref"] . ") " . $_POST["phone"];
        $tel_sms = '7' . $_POST["pref"] . $_POST["phone"];
        $date_of_delivery = null;
        $time_intervals = null;
        $city_id = -1;
        if ($_POST['city_id'] != '') $city_id = $_POST['city_id'];
        $myMail = array('alex.prokofiev@mail.ru', 'opmyukka@gmail.com');
        switch ($_SESSION["dostavka"]) {
            case(1):
                $dopFld = 'Самовывоз со склада (г. Санкт-Петербург, Большой Сампсониевский пр., д. 62, оф. 202)';
                $hat = 'г. Санкт-Петербург, Большой Сампсониевский пр., д. 62, оф. 202';
                break;
            case(2):
                $dopFld = 'Доставка курьером по РФ (срок доставки в ваш город +1 день)';
                $hat = 'г. Санкт-Петербург, Большой Сампсониевский пр., д. 62, оф. 202';
                break;
            case(3):
                $dopFld = 'Доставка: (' . $_POST['adr'] . '). Дата и время доставки будут согласовываться с оператором согласно <a href="https://glukoza-med.ru/index.php?page=stat&alias=delivery">условиям доставки</a>.';
                $hat = 'г. Санкт-Петербург, Большой Сампсониевский пр., д. 62, оф. 202';
                break;
            case(4):
                $dopFld = 'Доставка почтой по РФ';
                $hat = 'г. Санкт-Петербург, Большой Сампсониевский пр., д. 62, оф. 202';
                break;
            case(5):
                $dopFld = 'Самовывоз из магазина (г. Санкт-Петербург, ул. Сикейроса, д.10, корп.4, лит."А", ТК "Бульвар", помещение 4/2)';
                $hat = 'г. Санкт-Петербург, ул. Сикейроса, д.10, корп.4, лит."А", ТК "Бульвар", пом. 4/2';
                break;
        }

        if (isset($_POST['date_of_delivery'])) $date_of_delivery = strftime("%Y-%m-%d", strtotime($_POST['date_of_delivery']));
        if (isset($_POST['time_intervals'])) $time_intervals = $_POST['time_intervals'];
        $adr = $_POST["adr"];

        $res_n = $mysqli->query('select number from order_shop where id in (select max(id) from order_shop)');
        while ($row_n = $res_n->fetch_object()) {
            $number = $row_n->number + 1;
            $number_prn = str_repeat('0', 5 - strlen($number)) . $number . '\\' . strftime('%y', time());   // Формат номера для вывода на печать
        }
        // Подготовка запроса для записи заголовка заказа
        $stmt_order = $mysqli->prepare('insert into order_shop (date_order, fio, phone, email, comment, adr, date_of_delivery, time_intervals, dop_fld, delivery, user_id, bonus, `number`, city_id, post_index)
                                        values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');

        // Привязка параметров для записи заголовка заказа
        $user_id = -1;
        if (isset($_SESSION['user'])) $user_id = $_SESSION['user']['id'];
        $stmt_order->bind_param('sssssssssiiiiis', $date, $fio, $tel, $_POST["email"], $_POST["comment"], $adr, $date_of_delivery, $time_intervals, $dopFld, $_SESSION['dostavka'], $user_id, $use_bonus, $number, $city_id, $_POST['post_index']);
        if ($stmt_order->execute()) {

            // Отнимаем бонусы у пользователя, если он авторизован
            if (isset($_SESSION['user'])) {
                $res_bonus = $mysqli->query('update users set bonus = (bonus - ' . $use_bonus . ') where (id = ' . $_SESSION['user']['id'] . ')');
            }

            // Записываем в базу тело заказа
            $res_num = $mysqli->query('show table status like "order_shop"');
            while ($row_num = $res_num->fetch_object()) $num_order = $row_num->Auto_increment - 1;
            $stmt_body = $mysqli->prepare('insert into body_order (parent, id_tovar, price, amount)
                                           values (?,?,?,?)');
            foreach ($_SESSION["basket"] as $key => $value) {
                $stmt_body->bind_param('iidi', $num_order, $value["idTov"], $value["price"], $value["amount"]);
                $stmt_body->execute();
            }
        } else {
            echo 'Ошибка записи заказа!';
            printf("Ошибка: %d.\n", $stmt_order->errno);
        }
        $teloZakaza = '<table style="width: 100%; color: #666; border-collapse: collapse">
                           <tr>
                               <th>Наименование</th>
                               <th>Кол-во, шт</th>
                               <th>Цена, руб</th>
                               <th>Сумма, руб</th>
                           </tr>';
        $sumAll = 0;
        $sumDel = 0;
        $sumBig = 0;
        $startLine = 12;
        $nameDel = '';

        require_once '../phpexcel/Classes/PHPExcel.php';
        require_once '../phpexcel/Classes/PHPExcel/Writer/Excel5.php';

        $xls = new PHPExcel();
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle('Товарный чек');
        $borderedHead = new PHPExcel_Style();
        $borderedHead->applyFromArray(
            array(
                'alignment' => array(
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                    'wrap' => true,
                ),
                'borders' => array(
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                )
            )
        );
        $chek_day = strftime('%d.%m.%Y', (time() + 60 * 60 * 24));
        $sheet->mergeCells('A1:I1')->setCellValue('A1', 'ООО "Сингер-Мед"');
        $sheet->getStyle('A1')->getFont()->setSize(14);

        $sheet->mergeCells('A3:I3')->setCellValue('A3', 'Телефон/факс : +7 (812) 244-4192, +7 (812) 244-4102, +7 (812) 244-3474');
        $sheet->mergeCells('A4:I4')->setCellValue('A4', 'www.glukoza-med.ru');
        $sheet->getStyle('A2:A4')->getFont()->setSize(9)->setBold(true);
        $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('D6:E6')// Номер документа (заголовок)
        ->mergeCells('F6:G6')// Дата составления (заголовок)
        ->mergeCells('D7:E7')// Номер документа
        ->mergeCells('F7:G7')// Дата составления
        ->mergeCells('B7:C7')// Товарный чек
        ->mergeCells('A8:B8');    // ИНН
        $sheet->setCellValue('D6', 'Номер документа')
            ->setCellValue('F6', 'Дата составления')
            ->setCellValue('A8', 'ИНН 7814557450')
            ->setCellValue('B7', 'ТОВАРНЫЙ ЧЕК ')
            ->setCellValue('F7', $chek_day)
            ->setCellValue('D7', $number_prn);
        $sheet->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('D7:F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B7:F7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->setSharedStyle($borderedHead, 'D6:G7');
        $sheet->getStyle('D6:F6')->getFont()->setSize(8);
        $sheet->getStyle('B7:F7')->getFont()->setSize(9);
        $sheet->getStyle('A8')->getFont()->setSize(9);
        $sheet->getStyle('D6:F6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

// Шапка таблицы
        $sheet->mergeCells('A10:A11')
            ->mergeCells('C10:C11')
            ->mergeCells('D10:E10')
            ->mergeCells('F10:F11')
            ->mergeCells('G10:H10')
            ->mergeCells('I10:I11')
            ->setCellValue('A10', 'Номер по порядку')
            ->setCellValue('B10', 'Товар, тара')
            ->setCellValue('B11', 'Наименование, характеристика')
            ->setCellValue('C10', 'Артикул товара')
            ->setCellValue('D10', 'Единица измерения')
            ->setCellValue('D11', 'Наиме-нование')
            ->setCellValue('E11', 'Код по ОКЕИ')
            ->setCellValue('F10', 'Цена')
            ->setCellValue('G10', 'Отпущено')
            ->setCellValue('E11', 'Код по ОКЕИ')
            ->setCellValue('G11', 'Количест-во (масса)')
            ->setCellValue('H11', 'Сумма, руб. коп')
            ->setCellValue('I10', 'Продано на сумму, руб. коп');
        $sheet->getColumnDimension('A')->setWidth(7);
        $sheet->getColumnDimension('B')->setWidth(27);
        $sheet->getColumnDimension('C')->setWidth(7);
        $sheet->getColumnDimension('D')->setWidth(7);
        $sheet->getColumnDimension('E')->setWidth(8);
        $sheet->getColumnDimension('F')->setWidth(7);
        $sheet->getColumnDimension('G')->setWidth(9);
        $sheet->getColumnDimension('H')->setWidth(7);
        $sheet->getColumnDimension('I')->setWidth(10);
        $sheet->getRowDimension(14)->setRowHeight(25);
        $sheet->setSharedStyle($borderedHead, 'A10:I11');
        $sheet->getStyle('A10:I13')->getAlignment()->setWrapText(true)
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A10:I11')->getFont()->setSize(8);

        $cnt = $startLine + 1;

        foreach ($_SESSION["basket"] as $key => $value) {                 // Пихаем список товаров в товарный чек
            $sumItem = $value["price"] * $value["amount"];
            if (($value["idTov"] != 71) and ($value["idTov"] != 72)) {    // Если это не доставка
                $sheet->setCellValue('A' . $cnt, $cnt - $startLine);
                $sheet->setCellValue('B' . $cnt, $value['name']);
                $sheet->setCellValue('D' . $cnt, 'Упак');

                $sheet->setCellValue('F' . $cnt, $value['price']);
                //$sheet->getStyle('F'.$cnt)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

                $sheet->setCellValue('G' . $cnt, $value['amount']);

                $sheet->setCellValue('H' . $cnt, '=F' . $cnt . '*G' . $cnt);
                $sheet->setCellValue('I' . $cnt, '=F' . $cnt . '*G' . $cnt);
                $sumAll = $sumAll + $sumItem;
                $cnt++;
            } else {
                $sumDel = $sumDel + $sumItem;
                $nameDel = $value['name'];
            }
            $teloZakaza .= '<tr id="tr' . $key . '">
                                <td style="border: 1px solid #666">' . $value["name"] . '</td>
                                <td style="text-align: center; border: 1px solid #666">' . $value["amount"] . '</td>
                                <td style="text-align: right; border: 1px solid #666">' . number_format($value["price"], 2, '-', ' ') . '</td>
                                <td style="text-align: right; border: 1px solid #666">' . number_format($sumItem, 2, '-', ' ') . '</td>
                            </tr>';
        }
        $sheet->setSharedStyle($borderedHead, 'A' . ($startLine + 1) . ':I' . ($cnt - 1));
        $sheet->setCellValue('H' . $cnt, 'Итого: ');

        $sheet->setCellValue('I' . $cnt, '=SUM(I' . ($startLine + 1) . ':I' . ($cnt - 1) . ')');
        $sheet->mergeCells('A' . ($cnt + 2) . ':I' . ($cnt + 2));
        $sheet->setCellValue('A' . ($cnt + 2), 'Всего: ' . num_propis($sumAll) . ' руб. 00 коп.');
        $sheet->mergeCells('A' . ($cnt + 4) . ':I' . ($cnt + 4));
        $sheet->setCellValue('A' . ($cnt + 4), 'Покупатель ___________________ /_____________/');
        $sheet->mergeCells('A' . ($cnt + 6) . ':I' . ($cnt + 6));
        $sheet->setCellValue('A' . ($cnt + 6), 'Кассир ___________________ /_____________/');
        $sheet->mergeCells('A' . ($cnt + 8) . ':I' . ($cnt + 8));
        $sheet->setCellValue('A' . ($cnt + 8), 'МП');
        $sheet->mergeCells('A' . ($cnt + 10) . ':I' . ($cnt + 10));

        $sheet->setCellValue('A' . ($cnt + 10), 'Деятельность организации осуществляется при использовании ЕНВД без применения ККМ на основании пункта 2.1 ст.2. Федерального закона 54-ФЗ «О применении контрольно-кассовой техники при осуществлении наличных денежных расчетов и (или) расчетов с использованием платежных карт»');
        $sheet->getStyle('A' . ($cnt + 10))->getFont()->setSize(8);
        $sheet->setCellValue('A' . ($cnt + 11), '*****************************************************************************************************');
        $date_of_delivery = '';
        if (isset($_POST['date_of_delivery'])) $date_of_delivery = $_POST['date_of_delivery'];
        $sheet->setCellValue('A' . ($cnt + 13), 'Заказ № ' . $number_prn)
            ->setCellValue('A' . ($cnt + 14), 'Заказчик: ' . $fio)
            ->setCellValue('A' . ($cnt + 15), 'Телефон: ' . $tel)
            ->setCellValue('A' . ($cnt + 16), 'E-mail: ' . $_POST['email'])
            ->setCellValue('A' . ($cnt + 18), 'Требуемая дата доставки заказа: ' . $date_of_delivery)
            ->setCellValue('A' . ($cnt + 19), 'Временной период доставки заказа: ' . $time_intervals)
            ->setCellValue('A' . ($cnt + 21), 'Итого: ' . ($sumDel + $sumAll) . ' руб');
        if ($sumDel != 0) $sheet->setCellValue('A' . ($cnt + 20), $nameDel . ' - ' . $sumDel . ' руб');
        // $sheet->setSharedStyle($borderedHead, 'A'.($cnt + 18).':I'.($cnt+18));
        $sheet->getStyle('A' . ($cnt + 13))->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A' . ($cnt + 21))->getFont()->setBold(true)->setSize(14);


        $sheet->getRowDimension($cnt + 10)->setRowHeight(40);
        $sheet->getStyle('B' . $startLine . ':B' . $cnt)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A' . ($cnt + 2) . ':A' . ($cnt + 10))->getAlignment()->setWrapText(true);
        $sheet->getStyle('A' . $startLine . ':I' . ($cnt + 3))->getFont()->setSize(8);
        $sheet->getStyle('a1:i200')->getFont()->setName('Arial');
        $sheet->getStyle('A' . ($cnt + 30) . ':I' . ($cnt + 30))->getFont()->setSize(8);
        $sheet->getStyle('H' . $cnt . ':I' . $cnt)->getFont()->setBold(true)->setSize(9);

        $teloZakaza .= '<tr id="podval">
                            <td colspan="3" style="text-align: right">Итого: </td>
                            <td style="text-align: right; border: 1px solid #666">' . number_format(($sumAll + $sumDel), 2, '-', ' ') . '</td>
                        </tr>';
        if (isset($_SESSION['user'])) {
            $teloZakaza .= '<tr id="podval">
                                <td colspan="3" style="text-align: right">Использовано бонусов: </td>
                                <td style="text-align: right; border: 1px solid #666">' . number_format($use_bonus, 2, '-', ' ') . '</td>
                            </tr>';
        }
        $teloZakaza .= '<tr id="podval">
                            <td colspan="3" style="text-align: right">К оплате: </td>
                            <td style="text-align: right; border: 1px solid #666">' . number_format(($sumAll + $sumDel - $use_bonus), 2, '-', ' ') . '</td>
                        </tr>
                        </table>';


//        $myMail = 'WebMaster <isin.ltd@ya.ru>';


        $sheet->mergeCells('A2:I2')->setCellValue('A2', $hat);
        $sheet->getStyle('A2')->getFont()->setSize(9)->setBold(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A' . ($cnt + 17) . ':I' . ($cnt + 17));
        $sheet->setCellValue('A' . ($cnt + 17), $dopFld);
        $sheet->getStyle('A' . ($cnt + 17))->getAlignment()->setWrapText(true);  // Перенос доставки

        $tovarToAgent = strftime("%d.%m.%Y", time());
        $objWriterCheck = new PHPExcel_Writer_Excel5($xls);
        $objWriterCheck->save('../xlsx_2014/check_' . $num_order . '.xls');
        $delivery = '';
        if ($date_of_delivery != null) $delivery = '<p style="color: #666">Дата получения заказа: ' . $_POST['date_of_delivery'] . ' ' . $time_intervals . '</p>';


        $sMess = new smtp_mailer();
        $sMess->subject = 'Заказ №' . $number_prn . ' от ' . $dateMy;
        $sMess->text = '<html>
                        <head>
                             <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        </head>
                        <body>
                        <p style="color: #666">Здравствуйте ' . $fio . '!</p>
                           <p style="color: #666">Ваш заказ №' . $number_prn . ' от ' . $dateMy . ' принят.</p>
                           ' . $teloZakaza . $delivery . '
                           <p style="color: #666">' . $dopFld . '</p>
                           <p style="color: #666">Комментарий: ' . $_POST['comment'] . '</p>
                           <p style="color: #666">Наш менеджер свяжется с вами в рабочее время по телефону: ' . $tel . '<br/><br/>
                           Спасибо.<br/><br/><b>ВНИМАНИЕ!</b> При самовывозе со склада или из магазина Ваш заказ действителен в течение 3-х рабочих дней.
                                                 Если Вы (или Ваш представитель) не забрали заказ своевременно - он аннулируется.</p>
                                                 <p style="font-weight: bold; font-size: 16px;">Товары, купленные в нашем магазине, обмену и возврату не подлежат. Гарантия распространяется ТОЛЬКО на технические неисправности!!!</p>
                        </body>
                        </html>';
        $sMess->mail_rc = $_POST['email'];
        $sMess->link = -1;
        $sMess->send();


        $myMess = new smtp_mailer();
        $myMess->subject = 'Вам поступил заказ №' . $number_prn;
        $myMess->text = '<html>
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        </head>
                        <body><p style="color: #666">Здравствуйте владельцы магазина ГЛЮКОЗА!</p>
                            <p style="color: #666">Вам поступил заказ №' . $number_prn . ' от ' . $dateMy . '.</p>
                            ' . $teloZakaza . $delivery . '
                            <p style="color: #666">' . $dopFld . '</p>
                            <p style="color: #666">Комментарий: ' . $_POST['comment'] . '</p>
                            <p style="color: #666">Заказ оставил(а): ' . $fio . ', телефон: ' . $tel . ', e-mail: ' . $_POST['email'] . '<br/>
                            Во вложении бланк заказа в формате XLS.<br/><br/>
                            Спасибо.</p>
                        </body>
                        </html>';
        $myMess->link = '../xlsx_2014/check_' . $num_order . '.xls';
        foreach ($myMail as $key => $value) {
            $myMess->mail_rc = $value;
            $myMess->send();
        }


        /* СМС клиенту - начало */
//        $txt = urlencode('Заказ №' . $number_prn . ' от ' . strftime("%d.%m.%Y", time()) . ', сумма ' . ($sumAll + $sumDel) . 'руб поступил в обработку. Тел +78122444192');
        $txt = urlencode('Спасибо за заказ. Сумма: ' . ($sumAll + $sumDel - $use_bonus) . ' руб. Ждите звонка или смс');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://smsc.ru/sys/send.php?login=isin_glukoza&psw=SubarU96&phones=' . $tel_sms . '&mes=' . $txt . '&charset=utf-8');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        curl_close($ch);
        /* СМС клиенту - конец */


        unset($_SESSION['basket']);
        if (isset($_SESSION['bonus'])) unset($_SESSION['bonus']);
        unset($_SESSION['dostavka']);
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <head><![CDATA[Спасибо, ваш заказ оформлен. На e-mail, который вы указали отправлена информация о заказе.]]></head>';
        break;

    case ('addBonus'):
        $_SESSION['bonus'] = $_POST['bonus'];
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <head><![CDATA[]]></head>';
        break;

    case ('delBonus'):
        $_SESSION['bonus'] = 0;
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <head><![CDATA[]]></head>';
        break;

}
echo $xml;
?>

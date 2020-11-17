<?php namespace App;

use mPDF;

require_once '../../vendor/autoload.php';
require_once '../../php/mysqlconnect_new.php';
require_once '../../php/propis.php';

class cash_memo
{
    var $pdf_body;         // Html код для загрузки в mpdf
    var $order;            // Номер документа
    var $date;             // Дата документа
    var $body;             // Тело документа
    var $sum_all;          // Общая сумма документа
    var $sum_all_delivery; // Общая сумма документа с доставкой
    var $fio;              // Заказчик
    var $phone;            // Телефон
    var $email;            // E-mail
    var $poluchenie;       // Способ получения товара
    var $delivery;         // Строчка с суммой доставки

    function __construct()
    {
    }

    function get_body()
    {
        $this->pdf_body = '<!DOCTYPE html>
                           <html>
                               <head lang="en">
                                   <meta charset="UTF-8">
                                   <title></title>
                               </head>
                               <body>
                                   <div class="head" style="font-size: 14px">ООО "Сингер-Мед"</div>
                                   <div class="head">Телефон/факс : +7 (812) 244-4192</div>
                                   <div class="head">www.glukoza-med.ru</div>
                                   <table style="width: 300px; margin: 20px auto; border-collapse: collapse; text-align: center;">
                                       <tr>
                                           <td></td>
                                           <td style="border: 1px solid #000">Номер документа</td>
                                           <td style="border: 1px solid #000">Дата составления</td>
                                       </tr>
                                       <tr>
                                           <td>ТОВАРНЫЙ ЧЕК</td>
                                           <td style="border: 1px solid #000">' . $this->order . '</td>
                                           <td style="border: 1px solid #000">' . $this->date . '</td>
                                       </tr>
                                   </table>
                                   <div>ИНН 7814557450</div>
                                   <table class="main_table">
                                       <thead>
                                           <tr>
                                               <td rowspan="2">Номер по<br>порядку</td>
                                               <td>Товар, тара</td>
                                               <td rowspan="2">Артикул<br>товара</td>
                                               <td colspan="2">Единица измерения</td>
                                               <td rowspan="2">Цена</td>
                                               <td colspan="2">Отпущено</td>
                                               <td rowspan="2">Продано на<br>сумму, руб. коп</td>
                                           </tr>
                                           <tr>
                                               <td>Наименование, характеристика</td>
                                               <td>Наиме-<br>нование</td>
                                               <td>Код по<br>ОКЕИ</td>
                                               <td>Количество<br>(масса)</td>
                                               <td>Сумма,<br>руб. коп</td>
                                           </tr>
                                       <thead>
                                       <tbody>' . $this->body . '</tbody>
                                   </table>
                                   <div>Всего: ' . num_propis($this->sum_all) . ' руб. 00 коп.</div>
                                   <div class="storona">Покупатель _______________________________________/________________________/</div>
                                   <div class="storona">Кассир _______________________________________/________________________/</div>
                                   <div class="storona">МП</div>
                                   <div class="zakon">Деятельность организации осуществляется при использовании ЕНВД без применения ККМ на основании пункта 2.1 ст.2. Федерального закона 54-ФЗ «О применении контрольно-кассовой техники при осуществлении наличных денежных расчетов и (или) расчетов с использованием платежных карт»</div>
                                   <div>*****************************************************************************************************************************************</div>
                                   <div class="part_two_title">Заказ №' . $this->order . '</div>
                                   <div class="part_two_storona">Заказчик: ' . $this->fio . '</div>
                                   <div class="part_two_storona">Телефон: ' . $this->phone . '</div>
                                   <div class="part_two_storona">E-mail: ' . $this->email . '</div>
                                   ' . $this->poluchenie . $this->delivery . '
                               </body>
                           </html>';
    }
}
global $mysqli;
$html = new cash_memo();
$res = $mysqli->query('select order_shop.date_order,
                              body_order.price,
                              body_order.id_tovar,
                              body_order.amount,
                              order_shop.fio,
                              order_shop.phone,
                              order_shop.email,
                              order_shop.adr,
                              order_shop.date_of_delivery,
                              order_shop.time_intervals,
                              order_shop.number,
                              tovar.name,
                              order_shop.bonus,
                              body_order.price * body_order.amount as sum_item
                       from order_shop
                       inner join body_order on (body_order.parent = order_shop.id)
                       inner join tovar on (tovar.id = body_order.id_tovar)
                       where (order_shop.id = ' . $_GET['order'] . ')');
$cnt = 1;
$sum_all = 0;
$sum_delivery = 0;
while ($row = $res->fetch_object()) {
    $number_prn = str_repeat('0', 5 - strlen($row->number)) . $row->number . '\\' . strftime('%y', strtotime($row->date_order));
    $bonus = $row->bonus;
    $html->date = strftime('%d.%m.%Y', strtotime($row->date_order));
    if (($row->id_tovar != 71) && ($row->id_tovar != 966)) {
        $html->body .= '<tr>
                            <td class="right">' . $cnt . '</td>
                            <td class="left">' . $row->name . '</td>
                            <td></td>
                            <td class="center">Упак</td>
                            <td></td>
                            <td class="right">' . number_format($row->price, 2, ',', ' ') . '</td>
                            <td class="right">' . $row->amount . '</td>
                            <td class="right">' . number_format($row->sum_item, 2, ',', ' ') . '</td>
                            <td class="right">' . number_format($row->sum_item, 2, ',', ' ') . '</td>
                        </tr>';
        $cnt++;
        $sum_all = $sum_all + $row->sum_item;
    } else {
        $html->delivery = '<div class="part_two_storona">' . $row->name . ' - ' . number_format($row->price, 2, ',', ' ') . ' руб</div>';
        $sum_delivery = $row->price;
    }
    $html->fio = $row->fio;
    $html->phone = $row->phone;
    $html->email = $row->email;
    if ($row->adr != 'undefined') {
        $date_of_delivery = strftime('%d.%m.%Y', strtotime($row->date_of_delivery));
        $html->poluchenie = '<div class="part_two_storona">Доставка по адресу: ' . $row->adr . '</div>
                             <div class="part_two_storona">Требуемая дата доставки заказа: ' . $date_of_delivery . '</div>';
        if ($row->time_intervals != '') $html->poluchenie .= '<div class="part_two_storona">Временной период доставки заказа: ' . $row->time_intervals . '</div>';
    }
}
$html->body .= '<tr>
                    <td class="podval right" colspan="8">Итого:</td>
                    <td class="podval right">' . number_format($sum_all, 2, ',', ' ') . '</td>
                </tr>';
if ($bonus != 0) {
    $html->body .= '<tr>
                        <td class="podval right" colspan="8">Скидка по бонусной программе:</td>
                        <td class="podval right">' . number_format($bonus, 2, ',', ' ') . '</td>
                    </tr>';
}
$html->body .= '<tr>
                    <td class="podval right" colspan="8">К оплате:</td>
                    <td class="podval right">' . number_format(($sum_all - $bonus), 2, ',', ' ') . '</td>
                </tr>';
$html->sum_all = $sum_all - $bonus;
$html->order = $number_prn;
$html->delivery .= '<div class="part_two_title">Итого: ' . number_format(($html->sum_all + $sum_delivery), 2, ',', ' ') . ' руб</div>';


$html->get_body();
$mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
$stylesheet = file_get_contents('style_pdf.css'); /*подключаем css*/
$mpdf->WriteHTML($stylesheet, 1);
// $mpdf->useAdobeCJK = true;
$mpdf->SetAutoFont(AUTOFONT_ALL);
$mpdf->writeHTML($html->pdf_body, 2);
$mpdf->Output();
?>
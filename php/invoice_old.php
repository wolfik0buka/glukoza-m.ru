<?php namespace App;

use mPDF;

require_once '../vendor/autoload.php';
require_once 'mysqlconnect_new.php';
require_once 'propis.php';

class cash_memo {
    var $pdf_body;   // Html код для загрузки в mpdf
    var $order;      // Номер документа
    var $date;       // Дата документа
    var $body;       // Тело документа
    var $sum_all;    // Общая сумма документа
    var $fio;        // Заказчик
    var $phone;      // Телефон
    var $email;      // E-mail
    var $poluchenie; // Способ получения товара
    var $podval;     // То, что после таблицы с товарами
    var $cnt;        // Колиество наименований
    function cash_memo() {}
    function get_body() {
        $this->pdf_body = '<!DOCTYPE html>
                           <html>
                               <head lang="en">
                                   <meta charset="UTF-8">
                                   <title></title>
                               </head>
                               <body>
                                   <div class="head">
                                       Внимание! Оплата данного счета означает согласие с условиями поставки товара. Уведомление об оплате<br>
                                       обязательно, в противном случае не гарантируется наличие товара на складе. Товар отпускается по факту<br>
                                       прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и паспорта.
                                   </div>
                                   <table class="invoice_head">
                                       <tr>
                                           <td style="width: 55%" rowspan="2" colspan="2">СЕВЕРО-ЗАПАДНЫЙ БАНК СБЕРБАНКА РФ Г. САНКТ-ПЕТЕРБУРГ<br>Банк получателя</td>
                                           <td style="width: 10%">БИК</td>
                                           <td style="width: 35%">044030653</td>
                                       </tr>
                                       <tr>
                                           <td>Сч. №</td>
                                           <td>30101810500000000653</td>
                                       </tr>
                                       <tr>
                                           <td>ИНН 7814557450</td>
                                           <td>КПП 781401001</td>
                                           <td rowspan="2" style="vertical-align: top">Сч. №</td>
                                           <td rowspan="2" style="vertical-align: top">40702810855070001781</td>
                                       </tr>
                                       <tr>
                                           <td colspan="2">ООО "Сингер-Мед"<br><br>Получатель</td>
                                       </tr>
                                   </table>
                                   <div class="invoice_name">Счет на оплату № '.$this->order.' от '.$this->date.'.</div>
                                   <div style="width: 100%; border-bottom: 2px solid #000;"></div>
                                   <table>
                                       <tr>
                                           <td style="padding-right: 10px; vertical-align: top; font: 12px normal;">Поставщик:</td>
                                           <td class="invoice_stor">ООО "Сингер-Мед", ИНН 7814557450, КПП 781401001, 197372, Санкт-Петербург г, Камышовая, дом № 38, корпус 1, кв.54, тел.: 448-67-08, факс: 448-67-08</td>
                                       </tr>
                                       <tr>
                                           <td style="padding-right: 10px; vertical-align: top; font: 12px normal;">Покупатель:</td>
                                           <td class="invoice_stor">'.$this->fio.', '.$this->phone.', '.$this->email.'</td>
                                       </tr>
                                   </table>
                                   <table class="invoice_table">
                                       <thead>
                                           <tr>
                                               <td>№</td>
                                               <td>Артикул</td>
                                               <td>Товары (работы, услуги)</td>
                                               <td>Кол-во</td>
                                               <td>Ед.</td>
                                               <td>Цена</td>
                                               <td>Сумма</td>
                                           </tr>
                                       <thead>
                                       <tbody>'.$this->body.'</tbody>
                                   </table>
                                   '.$this->podval.'
                                   <div>Всего наименований '.$this->cnt.', на сумму: '.number_format($this->sum_all, 2, ',', ' ').' руб.</div>
                                   <div class="invoice_stor">'.num_propis($this->sum_all).' руб. 00 коп.</div>
                                   <div style="width: 100%; border-bottom: 2px solid #000;"></div>
                                   <table style="width: 100%; margin: 20px 0;">
                                       <tr>
                                           <td style="width: 90px;">Руководитель</td>
                                           <td style="border-bottom: 1px solid #000; text-align: right;">Герасименко С.В.</td>
                                           <td style="width: 110px; padding-left: 40px;">Бухгалтер</td>
                                           <td style="border-bottom: 1px solid #000; text-align: right;">Сингаевская А.И.</td>
                                       </tr>
                                   </table>
                                   '.$this->poluchenie.'
                                   <div style="font: 14px bold; margin-top: 40px;">ВНИМАНИЕ! Данный счет действителен в течение 5 (пяти) рабочих дней. В случае оплаты после истечения указанного срока Поставщик не гарантирует цену и наличие товара на складе.</div>
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
                              tovar.name,
                              body_order.price * body_order.amount as sum_item
                       from order_shop
                       inner join body_order on (body_order.parent = order_shop.id)
                       inner join tovar on (tovar.id = body_order.id_tovar)
                       where (order_shop.id = '.$_GET['order'].') and
                             (body_order.id_tovar != 71)');
$cnt = 1;
$sum_all = 0;
while($row = $res->fetch_object()) {
    $html->date = strftime('%d.%m.%Y', strtotime($row->date_order));
    $html->body .= '<tr>
                        <td class="right">'.$cnt.'</td>
                        <td>'.str_repeat('0', 5-strlen($row->id_tovar)).$row->id_tovar.'</td>
                        <td class="left">'.$row->name.'</td>
                        <td class="right">'.$row->amount.'</td>
                        <td class="center">Упак</td>
                        <td class="right">'.number_format($row->price, 2, ',', ' ').'</td>
                        <td class="right">'.number_format($row->sum_item, 2, ',', ' ').'</td>
                    </tr>';
    $cnt++;
    $sum_all = $sum_all + $row->sum_item;
    $html->fio = $row->fio;
    $html->phone = $row->phone;
    $html->email = $row->email;
}
$html->cnt = $cnt - 1;
$html->podval = '<table style="float: right; width: 100%; font: 12px bold; margin: 10px 0;">
                     <tr><td style="text-align: right;">Итого: </td><td style="width: 100px; text-align: right;">'.number_format($sum_all, 2, ',', ' ').'</td></tr>
                     <tr><td style="text-align: right;">Без налога (НДС) </td><td style="width: 100px; text-align: right;">-</td></tr>
                     <tr><td style="text-align: right;">Всего к оплате: </td><td style="width: 100px; text-align: right;">'.number_format($sum_all, 2, ',', ' ').'</td></tr>
                 </table>';
$html->sum_all = $sum_all;
$html->order = $_GET['order'];

$html->get_body();

$mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
$stylesheet = file_get_contents('style_pdf.css'); /*подключаем css*/
$mpdf->WriteHTML($stylesheet, 1);
// $mpdf->useAdobeCJK = true;
// $mpdf->SetAutoFont(AUTOFONT_ALL);
$mpdf->writeHTML($html->pdf_body, 2);
$mpdf->Output();
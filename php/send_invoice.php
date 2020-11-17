<?php
header('Content-Type: text/xml; charset=utf-8');
include 'attache_sender_inv.php';
include 'mysqlconnect_new.php';
$txt = 'error';
$query = 'select * from order_shop where (id = ' . $_POST['id'] . ')';
$res = $mysqli->query($query);
while ($row = $res->fetch_object()) {
    $email = $row->email;
    $number_prn = str_repeat('0', 5 - strlen($row->number)) . $row->number . '\\' . strftime('%y', strtotime($row->date_order));
}
$myMess = new smtp_mailer();
$myMess->subject = 'Счет №' . $number_prn;
$myMess->text = '<html>
                         <head>
                             <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                         </head>
                         <body>
                             <p>Добрый день!</p>
                             <p>Во вложении счет на оплату Вашего заказа.</p>
                             <p>С уважением,<br>Интернет-магазин ГЛЮКОЗА</p>
                         </body>
                     </html>';
$myMess->link = 'https://glukoza-med.ru/php/invoice.php?order=' . $_POST['id'];
$myMess->mail_rc = $email;
$myMess->send();
if ($mysqli->query('update order_shop set send_invoice=1 where id=' . $_POST['id'])) $txt = 'ok';
echo '<?xml version="1.0" encoding="utf-8"?>
          <head><![CDATA[' . $txt . ']]></head>';
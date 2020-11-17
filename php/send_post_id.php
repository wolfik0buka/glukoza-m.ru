<?php
    header('Content-Type: text/xml; charset=utf-8');
    include 'attache_sender.php';
    include 'mysqlconnect_new.php';
    $txt = 'error';
    $query = 'select * from order_shop where (id = '.$_POST['id'].')';
    $res = $mysqli->query($query);
    while ($row = $res->fetch_object()) {
        $email = $row->email;
        $post_id = $row->post_id;
        $bad_simbol = array(' ', '+', '(', ')', '-');
        $phone = str_replace($bad_simbol, '', $row->phone);
    }
    $myMess = new smtp_mailer();
    $myMess->subject = 'Заказ №'.$_POST['id'];
    $myMess->text = '<html>
                         <head>
                             <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                         </head>
                         <body>
                             <p>Добрый день!</p>
                             <p>Магазин Глюкоза отправил Ваш заказ №'.$_POST['id'].' почтой России.</p>
                             <p>Нахождение груза вы можете отследить самостоятельно на сайте Почты России <a href="http://www.russianpost.ru/tracking20/'.$_POST['post_id'].'">http://www.russianpost.ru/tracking20/'.$post_id.'</a>.</p>
                             <p>Ваш почтовый идентификатор: '.$post_id.'</p>
                             <p>Благодарим, что воспользовались услугами нашего магазина</p>
                         </body>
                     </html>';
    $myMess->link = -1;
    $myMess->mail_rc = $email;
    $myMess->send();

    /* СМС клиенту - начало */
    $txt = urlencode('Заказ №'.$_POST['id'].' отправлен. Ваш почтовый идентификатор: '.$post_id);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://smsc.ru/sys/send.php?login=isin_glukoza&psw=SubarU96&phones='.$phone.'&mes='.$txt.'&charset=utf-8');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch);
    curl_close($ch);
    /* СМС клиенту - конец */

    echo '<?xml version="1.0" encoding="utf-8"?>
          <head><![CDATA['.$phone.']]></head>';
?>
<?php
    header('Content-Type: text/xml; charset=utf-8');
    include 'ibaseconnect.php';
    include 'mailer.php';    
    global $dbh;    
    switch ($_POST['do']){
        case('addComment'):
            $date = strftime("%Y-%m-%d", strtotime($_POST["date_event"]));
            $query = ibase_prepare ($dbh, 'insert into comments (parent, comment, comment_name, date_comment)
                                           values (?, ?, ?, CURRENT_TIMESTAMP)');
            if (ibase_execute($query, $_POST['idTov'], $_POST['comment'], $_POST['commentName'])) {
                $txt = "Спасибо! Ваш отзыв ожидает проверки модератора и скоро будет опубликован!";
                $Mess = new Mailer();
                $Mess->charset = 'utf-8';
                $Mess->from    = 'Glukoza <shop@glukoza-med.ru>';
                $Mess->to      = 'Glukoza <glukoza@glukoza-med.ru>, Admin <alex.prokofiev@mail.ru>';
                $Mess->subject = 'Comments';
                $Mess->html = '<p style="color: #666">Всем привет, вам оставили комментарий!</p>';
                $Mess->Send();
            }
        break;
    }
    echo '<?xml version="1.0" encoding="utf-8"?>
          <head><![CDATA['.$txt.']]></head>';
?>

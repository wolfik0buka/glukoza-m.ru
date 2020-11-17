<?php
session_start();
header('Content-Type: text/xml; charset=utf-8');
require_once 'mysqlconnect_new.php';
require_once 'attache_sender.php';
$txt = 'Ошибка';
$id ='';
$html = '';
$go_url = '-1';
$error = -1;
function generate_password($num) {
    $masiv = array('a','b','c','d','e','f','g','h','i','j',
        'k','l','m','n','o','p','r','s','t','u',
        'v','x','y','z','A','B','C','D','E','F',
        'G','H','I','J','K','L','M','N','O','P',
        'R','S','T','U','V','X','Y','Z',
        '1','2','3','4','5','6','7','8','9','0');

    $pass = "";
    for($i = 0; $i < $num; $i++) {
        $podbor = rand(0, count($masiv) - 1);
        $pass .= $masiv[$podbor];
    }
    return $pass;
}
switch($_POST['doIt']) {
    case ('reg'):
        $flag = 0;
        $query = 'select * from users where (email = "'.$_POST['email'].'")';
        $res = $mysqli->query($query);
        while ($row = $res->fetch_object()) $flag = 1;
        if ($flag == 1) {
            $error = 'er_email';
        } else {
            $stmt = $mysqli->prepare('insert into users (name, pwd, email, phone, first_name, last_name, adr, city, city_id)
                                      values (?, ?, ?, ?, ?, ?, ?, ? ,?)');
            if ($_POST['pwd1'] == $_POST['pwd2']) {
                $name = $_POST['fam'].' '.$_POST['name'];
                $phone = "+7 (" . $_POST["pref"] . ") " . $_POST["phone"];
                $stmt->bind_param('ssssssssi',
                                   $name,
                                   md5($_POST['pwd1']),
                                   $_POST['email'],
                                   $phone,
                                   $_POST['first_name'],
                                   $_POST['last_name'],
                                   $_POST['adr'],
                                   $_POST['city'],
                                   $_POST['city_id']);
                if ($stmt->execute()) {
                    $query = 'select * from users
                              where (email="'.$_POST['email'].'")';
                    $res = $mysqli->query($query);
                    while ($row = $res->fetch_object()) {
                        foreach ($row as $key => $value) $_SESSION['user'][$key] = $value;
                    }
                    $txt = '<div>Поздравляем, теперь вы зарегистрированный пользователь!</div>';

                    $sMess = new smtp_mailer();
                    $sMess->subject = 'Регистрация на сайте glukoza-med.ru';
                    $sMess->text = '<html>
                        <head>
                             <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        </head>
                        <body>
                        <p style="color: #666">Здравствуйте ' . $name . '!</p>
                        <p style="color: #666">Поздравляем, теперь вы зарегистрированный пользователь интернет-магазина <a href="https://glukoza-med.ru/">glukoza-med.ru</a>!</p>
                        <p style="color: #666">Зарегистрированные пользователи не вводят свои контактные данные при каждом заказе, могут просмотреть информацию о своих заказах, получают информацию о новинках и акциях, накапливают скидки.</p>
                        <p style="color: #666">
                            Ваши персональные данные для входа на сайт<br>
                            E-mail: '.$_POST['email'].'<br>
                            Пароль: '.$_POST['pwd1'].'
                        </p>
                        <p style="color: #666">Спасибо за интерес, проявленный к нашему магазину.</p>
                        </body>
                        </html>';
                    $sMess->mail_rc = $_POST['email'];
                    $sMess->link = -1;
                    $sMess->send();

                }
            } else {
                $error = 'er_no_swp';
            }
        }
    break;
    case('recovery'):
        $query = 'select * from users where (email = "'.$_POST['email'].'")';
        $res = $mysqli->query($query);
        $txt = 'Такой E-mail не зарегистрирован!';
        $error = 'er_no_mail';
        while ($row = $res->fetch_object()) {
            $new_pwd = generate_password(intval(6));
            if ($mysqli->query('update users set pwd="'.md5($new_pwd).'" where (id = '.$row->id.')')) {
                $sMess = new smtp_mailer();
                $sMess->subject = 'Восстановлене пароля от сайта glukoza-med.ru';
                $sMess->text = '<html>
                        <head>
                             <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        </head>
                        <body>
                        <p style="color: #666">Здравствуйте '.$row->name.'!</p>
                        <p style="color: #666">Ваш новый пароль от интернет-магазина <a href="https://glukoza-med.ru/">glukoza-med.ru</a> '.$new_pwd.'</p>
                        <p style="color: #666">Спасибо за интерес, проявленный к нашему магазину.</p>
                        </body>
                        </html>';
                $sMess->mail_rc = $row->email;
                $sMess->link = -1;
                $sMess->send();
                $txt = 'Новый пароль выслан на указанный E-mail!';
                $error = -1;
            }
        }
    break;
    case('save_my_info'):
        $stmt = $mysqli->prepare('update users set name = ?, phone = ?, adr = ? where (id = ?)');
        $name = $_POST['fam'].' '.$_POST['name'];
        $phone = "+7 (" . $_POST["pref"] . ") " . $_POST["phone"];
        $stmt->bind_param('sssi', $name, $phone, $_POST['adr'], $_SESSION['user']['id']);
        if ($stmt->execute()) {
            $txt = 'Изменения сохранены';
        } else {
            $txt = 'Ошибка';
        }
    break;
}
echo '<?xml version="1.0" encoding="utf-8"?>
      <box>
          <head id="'.$id.'"><![CDATA['.$txt.']]></head>
          <go_url>'.$go_url.'</go_url>
          <error>'.$error.'</error>
      </box>';
?>
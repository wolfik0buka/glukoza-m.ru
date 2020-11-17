<?php
class smtp_mailer {
    var $link;    // Ссылка на приглашение
    var $text;    // Текст письма
    var $mail_rc; // E-mail получателя
    var $subject; // Тема письма
function get_data($smtp_conn)
{
    $data="";
    while($str = fgets($smtp_conn,515))
    {
        $data .= $str;
        if(substr($str,3,1) == " ") { break; }
    }
    return $data;
}

function send() {

$header="Date: ".date("D, j M Y G:i:s")." +0700\r\n";
$header.="From: =?utf-8?Q?".str_replace("+","_",str_replace("%","=",urlencode('Glukoza')))."?= <shop@glukoza-med.ru>\r\n";
$header.="X-Mailer: The Bat! (v3.99.3) Professional\r\n";
$header.="Reply-To: =?utf-8?Q?".str_replace("+","_",str_replace("%","=",urlencode('Glukoza')))."?= <shop@glukoza-med.ru>\r\n";
$header.="X-Priority: 3 (Normal)\r\n";
$header.="Message-ID: <172562218.".date("YmjHis")."@mail.ru>\r\n";
$header.="To: =?utf-8?Q?".str_replace("+","_",str_replace("%","=",urlencode('Glukoza')))."?= <".$this->mail_rc.">\r\n";
$header.="Subject: =?utf-8?Q?".str_replace("+","_",str_replace("%","=",urlencode($this->subject)))."?=\r\n";
$header.="MIME-Version: 1.0\r\n";
$header.="Content-Type: multipart/mixed; boundary=\"----------A4D921C2D10D7DB\"\r\n";


    $code_file2 = chunk_split(base64_encode(file_get_contents($this->link)));

    $text = "------------A4D921C2D10D7DB
Content-Type: text/html; charset=utf-8
Content-Transfer-Encoding: 8bit

" . $this->text . "

------------A4D921C2D10D7DB
Content-Type: application/pdf; name=\"invoice.pdf\"
Content-transfer-encoding: base64
Content-Disposition: attachment; filename=\"invoice.pdf\"

" . $code_file2 . "
------------A4D921C2D10D7DB";

$smtp_conn = fsockopen("ssl://smtp.yandex.ru", 465,$errno, $errstr, 10);
if(!$smtp_conn) {print "соединение с серверов не прошло"; fclose($smtp_conn); exit;}
$data = $this->get_data($smtp_conn);
fputs($smtp_conn,"EHLO vasya\r\n");
$code = substr($this->get_data($smtp_conn),0,3);
if($code != 250) {print "ошибка приветсвия EHLO"; fclose($smtp_conn); exit;}
fputs($smtp_conn,"AUTH LOGIN\r\n");
$code = substr($this->get_data($smtp_conn),0,3);
if($code != 334) {print "сервер не разрешил начать авторизацию"; fclose($smtp_conn); exit;}

fputs($smtp_conn,base64_encode("shop@glukoza-med.ru")."\r\n");
$code = substr($this->get_data($smtp_conn),0,3);
if($code != 334) {print "ошибка доступа к такому юзеру"; fclose($smtp_conn); exit;}


fputs($smtp_conn,base64_encode("KurtCobain666")."\r\n");
$code = substr($this->get_data($smtp_conn),0,3);
if($code != 235) {print "не правильный пароль"; fclose($smtp_conn); exit;}

$size_msg=strlen($header."\r\n".$text);

fputs($smtp_conn,"MAIL FROM:<shop@glukoza-med.ru> SIZE=".$size_msg."\r\n");

$code = substr($this->get_data($smtp_conn),0,3);
if($code != 250) {print "сервер отказал в команде MAIL FROM"; fclose($smtp_conn); exit;}

fputs($smtp_conn,"RCPT TO:<".$this->mail_rc.">\r\n");
$code = substr($this->get_data($smtp_conn),0,3);
if($code != 250 AND $code != 251) {print "Сервер не принял команду RCPT TO"; fclose($smtp_conn); exit;}

fputs($smtp_conn,"DATA\r\n");
$code = substr($this->get_data($smtp_conn),0,3);
if($code != 354) {print "сервер не принял DATA"; fclose($smtp_conn); exit;}

fputs($smtp_conn,$header."\r\n".$text."\r\n.\r\n");
$code = substr($this->get_data($smtp_conn),0,3);
if($code != 250) {print "ошибка отправки письма"; fclose($smtp_conn); exit;}

fputs($smtp_conn,"QUIT\r\n");
fclose($smtp_conn);
}


}

?>
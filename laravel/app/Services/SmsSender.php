<?php namespace App\Services;

class SmsSender
{
    protected $message = false;
    protected $phone = false;


    public function send()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://smsc.ru/sys/send.php?login=isin_glukoza&psw=SubarU96&phones='.$this->phone.'&mes='.$this->message.'&charset=utf-8');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        curl_close($ch);
    }


    public function setPhone($phone)
    {
        $this->phone = str_replace(['+', '-', '‒', '(', ')', ' '], "", $phone);
        return $this;
    }


    public function setMessage($message)
    {
        $this->message = urlencode($message);
        return $this;
    }


}
<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://lk.pro-cour.ru/calculator.php?charset=utf-8&type=find&query=' . $_GET['term']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$json = json_decode(curl_exec($ch));
$a = array();
foreach ($json as $key => $value) {
    if ($value->id != 137) {
        $a[$key]['id'] = $value->id;
        $a[$key]['label'] = $value->name;
        $a[$key]['value'] = $value->name;
    }
}
echo json_encode($a);
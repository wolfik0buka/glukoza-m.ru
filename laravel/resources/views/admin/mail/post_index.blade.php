<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>
<body>
<p>Добрый день!</p>
<p>Магазин Глюкоза отправил Ваш заказ №{{ str_repeat('0', 5 - strlen($order->number)) . $order->number . '\\' . strftime('%y', time()) }} почтой России.</p>
<p>Нахождение груза вы можете отследить самостоятельно на сайте Почты России
    <a href="https://www.pochta.ru/tracking#{{ $order->post_id }}">https://www.pochta.ru/tracking#{{ $order->post_id }}</a>.
</p>
<p>Ваш почтовый идентификатор: {{ $order->post_id }}</p>
<p>Благодарим, что воспользовались услугами нашего магазина</p>
</body>
</html>
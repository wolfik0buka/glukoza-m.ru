<!DOCTYPE html>
<html>
<head lang="ru">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <p>Здравствуйте {{ $order->fio }}!</p>
    <p>Ваш заказ
        № {{ str_repeat('0', 5 - strlen($order->number)) . $order->number . '\\' . strftime('%y', time()) }}
        от {{ strftime('%d.%m.%Y', strtotime($order->date_order)) }} принят.</p>
    <p>Наш менеджер свяжется с вами в рабочее время по телефону: {{ $order->phone }}</p>
<table style="width: 100%; color: #666; border-collapse: collapse">
    <thead>
    <tr>
        <th>Наименование</th>
        <th>Цена, руб</th>
        <th>Кол-во, шт</th>
        <th>Стоимость, руб</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->body_att as $item)
        <tr>
            <td style="border: 1px solid #666;">{{ $item->tovar_att->name }}</td>
            <td style="border: 1px solid #666; text-align: right;">{{ number_format($item->price, '2', ',', ' ') }}</td>
            <td style="border: 1px solid #666; text-align: center;">{{  $item->amount }}</td>
            <td style="border: 1px solid #666; text-align: right;">{{ number_format($item->amount * $item->price, '2', ',', ' ') }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    @if($order->bonus > 0)
        <tr>
            <th colspan="3" style="text-align: right;">Использованные бонусы:</th>
            <th style="text-align: right">{{ $order->bonus }}</th>
        </tr>
    @endif
    <tr>
        <th colspan="3" style="text-align: right;">Итого:</th>
        <th style="text-align: right">
            {{ number_format($sum_full, 2, ',', ' ') }}
        </th>
    </tr>
    </tfoot>
</table>


    @if(mb_strlen($order->comment, 'utf8') > 2)
        <p>Комментарий к заказу: {{ $order->comment }}</p>
    @endif


    @if($order->payment_type_id===2)
        <div>
            <p><b>Способ оплаты:</b> картой на сайте.</p>
            <p>Ссылка на оплату будет отправлена вам после подтверждения заказа.</p>
        </div>
    @endif


    {{--Пункт выдачи--}}
    @if($order->delivery === 11 && $order->deliveryPickupPoint instanceof Illuminate\Database\Eloquent\Model)
        <b>Пункт выдачи:</b>
        <p style="font-size:14px;">
            {{ $order->deliveryPickupPoint->point_name }} <br/>
            {{ $order->deliveryPickupPoint->point_address }} <br/>
            {{ $order->deliveryPickupPoint->point_work }} <br/>
            {{ $order->deliveryPickupPoint->point_way }} <br/>
            {{ (mb_strlen($order->deliveryPickupPoint->point_phone) > 2) ? ('тел: '.$order->deliveryPickupPoint->point_phone) : '' }}
        </p>
    @endif


    {{--Самовывоз из магазина--}}
    @if(($order->delivery === 1 || $order->delivery === 5 || $order->delivery === 13) && $order->deliveryModel instanceof Illuminate\Database\Eloquent\Model)
        <p><strong>Самовывоз из магазина</strong></p>
        <div style="font-size:14px;">{!! $order->deliveryModel->dop_fld !!}</div>
    @endif


    <p><strong>ВНИМАНИЕ!</strong></p>
    @if($order->delivery === 11 || $order->delivery === 2 || $order->delivery === 3)
        <p style="font-size:14px;">
            Обязательно проверьте комплектность заказа сразу при получении в транспортной компании, в противном случае обмен и возврат товара невозможен.
        </p>
    @endif
    <p style="font-size:14px;">
        При самовывозе со склада или из магазина Ваш заказ действителен в течение 3-х рабочих дней.
        Если Вы (или Ваш представитель) не забрали заказ своевременно - он аннулируется.
    </p>
    <p style="font-size:14px;">
        Товары, купленные в нашем магазине, обмену и возврату не подлежат.
        Гарантия распространяется ТОЛЬКО на технические неисправности!!!
    </p>

</body>
</html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<style>
    *{
        font-size: 13px;
        line-height: 13px;
        font-family: 'DejaVu Sans';
    }
    .main_table {
        width: 100%;
        margin: 10px 0;
        border-collapse: collapse;
        text-align: center;
    }
    .main_table thead tr td {
        vertical-align: middle;
    }
    .main_table tbody tr td {
        vertical-align: top;
    }
    .main_table tr td {
        border: 1px solid #000;
        padding: 3px 5px;
        font-size: 12px;
    }
    .main_table tr td.podval {
        border: none;
        font-weight: bold;
    }
    .main_table thead tr th{
        font-weight: normal;
        border: 1px solid #000;
        padding: 2px 5px;
        font-size: 12px;
    }
    .right {
        text-align: right;
    }
    .left {
        text-align: left;
    }
    .center {
        text-align: center;
    }
    .zakon {
        font-size: 12px;
    }
    .zakon_line{
        padding-top: 15px;
        font-size: 10px;
    }
    .storona {
        font-size: 12px;
        margin: 15px 0;
    }
    .head {
        text-align: center;
    }
    .part_two_title {
        font-weight: bold;
        font-size: 14px;
    }
    .part_two_storona {
        font-size: 14px;
        line-height: 19px;
    }
    .invoice_head {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .invoice_head tr td {
        border: 1px solid #000;
    }

    .invoice_name {
        font: 16px bold;
        margin: 5px 0;
    }

    .invoice_stor {
        font: 12px bold;
    }

    .invoice_table {
        width: 100%;
        border: 2px solid #000;
        border-collapse: collapse;
        margin: 10px 0;
    }

    .invoice_table tr td {
        border: 1px solid #000;
    }

    .invoice_table thead {
        font: 16px bold;
        text-align: center;
    }

    .invoice_table tbody {
        font: 14px normal;
    }

    .invoice_table tr td {
        padding: 2px 5px;
    }

    .cour_id {
        margin: 20px 0;
        font-size: 72px;
        text-align: center;
    }
</style>

<div class="head" style="font-size: 14px">ООО "Сингер-Мед"</div>
<div class="head">Телефон/факс : +7 (812) 244-4192</div>
<div class="head">
    @if($order->onmedi_order_id > 0)
        onmedi.ru
        @else
        glukoza-med.ru
    @endif
</div>

<table style="width: 300px; margin: 20px auto; border-collapse: collapse; text-align: center;">
    <tr>
        <td colspan="2">
            ТОВАРНЫЙ ЧЕК
            <div style="height:20px;"></div>
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid #000">Номер документа</td>
        <td style="border: 1px solid #000">Дата составления</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000">
            {{ $order->glukozaNumberFormatted() }}
        </td>
        <td style="border: 1px solid #000">
            {{ strftime('%d.%m.%Y', strtotime($order->date_order)) }}
        </td>
    </tr>
</table>

<div>ИНН 7814557450</div>

<table class="main_table">
    <thead>
        <tr>
            <th width="20px" rowspan="2">№<br>п/п</th>
            <th rowspan="2">Наименование товара</th>
            <th width="30px" rowspan="2">Артикул<br>товара</th>
            <th width="40px" rowspan="2">Единица <br>измерения</th>
            <th rowspan="2" width="70px">Цена, <br>руб.</th>
            <th colspan="2">Отпущено</th>
            <th width="80px" rowspan="2">Продано<br> на сумму,<br>руб.</th>
        </tr>
        <tr>
            <th width="45px">Кол-во</th>
            <th width="75px">Сумма, руб.</th>
        </tr>
    <thead>
    <tbody>
        @foreach($order->productLinks as $index => $product_link)
            @if($product_link->product->usluga === 0)
                <tr>
                    <td class="right">{{ $index+1}}</td>
                    <td class="left">{{ $product_link->product->name }}</td>
                    <td>{{ $product_link->product->id }}</td>
                    <td class="center">Упак</td>
                    <td class="right">{{ number_format($product_link->price, 2, ',', "&nbsp;") }}</td>
                    <td class="right">{{ $product_link->amount }}</td>
                    <td class="right">{{  number_format($product_link->price*$product_link->amount, 2, ',', "&nbsp;") }}</td>
                    <td class="right">{{  number_format($product_link->price*$product_link->amount, 2, ',', "&nbsp;") }}</td>
                </tr>
            @endif
        @endforeach
        <tr>
            <td class="podval right" colspan="7">Итого:</td>
            <td class="podval right">{{ number_format($order->getProductsSum(), 2, ',', "&nbsp;") }}</td>
        </tr>
        @if($order->bonus > 0)
            <tr>
                <td class="podval right" colspan="7">Скидка по бонусной программе:</td>
                <td class="podval right">{{ number_format($order->bonus, 2, ',', "&nbsp;") }}</td>
            </tr>
        @endif
        <tr>
            <td class="podval right" colspan="7">К оплате:</td>
            <td class="podval right">{{ number_format(($order->getProductsSum() - $order->bonus) , 2, ',', "&nbsp;") }}</td>
        </tr>
    </tbody>
</table>
@if(count(explode('.', $order->getPaymentSum())) > 1)
<div>Всего: {{ priceWordString(explode('.', $order->getPaymentSum())[0]) }} руб. {{ priceWordString(explode('.', $order->getPaymentSum())[1]) }} коп.</div>
@else
<div>Всего: {{ priceWordString($order->getPaymentSum()) }} руб. 00 коп.</div>
@endif
<div class="storona">Покупатель _______________________________________/________________________/</div>
<div class="storona">Кассир _______________________________________/________________________/</div>
<div class="storona">МП</div>
<div class="zakon">
    Деятельность организации осуществляется при использовании ЕНВД без применения ККМ на основании пункта 2.1 ст.2. Федерального
    закона 54-ФЗ «О применении контрольно-кассовой техники при осуществлении наличных денежных расчетов и (или) расчетов с
    использованием платежных карт»
    <br>
</div>
<div class="zakon_line">
    <br>
    *****************************************************************************************************************************************<br><br>
    <br>
</div>
<div class="part_two_title">Заказ №{{ $order->glukozaNumberFormatted() }}</div>
<div class="part_two_storona"><br>Заказчик: {{ $order->fio }}</div>
<div class="part_two_storona">Телефон: {{ $order->phone }}</div>
<div class="part_two_storona">E-mail: {{ $order->email }}</div>

@if($order->adr)
    <div class="part_two_storona">Доставка по адресу: {{ $order->adr }}</div>
    <div class="part_two_storona">Требуемая дата доставки
        заказа: {{ strftime('%d.%m.%Y', strtotime($order->date_of_delivery)) }}</div>
@endif


@if($order->delivery === 11)
    @if($order->deliveryPickupPoint)
        <div class="part_two_storona">
            Доставка в ПВЗ<br>
            Название ПВЗ: {{ $order->deliveryPickupPoint->point_name }}<br>
            Город: {{ $order->deliveryPickupPoint->city_name }}<br>
            Адрес: {{ $order->deliveryPickupPoint->point_address }}
        </div>
    @endif
@endif

@foreach(collect($order->productLinks)->filter(function($link){ return $link->product->usluga === 1; }) as $deliveryProductLink)
    <div class="part_two_storona">
        {{ $deliveryProductLink->product->name }} - {{ number_format($deliveryProductLink->price, 2, ',', ' ') }} руб
    </div>
@endforeach

<div class="part_two_title">
    <br>
    Итого: {{ number_format($order->getPaymentSum(), 2, ',', ' ') }} руб
</div>

@if($order->cour_id)
    <div class="cour_id">{{ $order->cour_id }}</div>
@endif

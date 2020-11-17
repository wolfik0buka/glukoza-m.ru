<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<style>
    * {
        font-size: 13px;
        line-height: 13px;
        font-family: 'DejaVu Sans';
    }

    .head {
        text-align: left;
        font-size: 11px;
        line-height: 12px;
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
        font-size: 10px;
        padding: 2px 5px;
    }
    .right{
        text-align: right;
    }
    .center{
        text-align: right;
    }
    .invoice_name {
        font-size: 15px;
        margin: 5px 0;
        font-weight: 400;
        padding-bottom: 5px;
    }

    .invoice_stor {
        font-size: 12px;
        font-weight: bold;
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
        text-align: center;
    }

    .invoice_table tbody {
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

<div class="head">
    Внимание!<br>
    Оплата данного счета означает согласие с условиями поставки товара.<br>
    Уведомление об оплате обязательно, в противном случае не гарантируется наличие товара на складе.<br>
    Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и паспорта.
</div>

<table class="invoice_head">
    <tr>
        <td style="width: 55%;" rowspan="2" colspan="2">СЕВЕРО-ЗАПАДНЫЙ БАНК СБЕРБАНКА РФ Г. САНКТ-ПЕТЕРБУРГ<br>Банк получателя</td>
        <td style="width: 10%">БИК</td>
        <td style="width: 35%">044030653</td>
    </tr>
    <tr>
        <td>Сч. №</td>
        <td>30101810500000000653</td>
    </tr>
    <tr>
        <td>ИНН 7814557450</td>
        <td>КПП 781401001</td>
        <td rowspan="2" style="vertical-align: top">Сч. №</td>
        <td rowspan="2" style="vertical-align: top">40702810855070001781</td>
    </tr>
    <tr>
        <td colspan="2">ООО "Сингер-Мед"<br><br>Получатель</td>
    </tr>
</table>

<div class="invoice_name">
    Счет на оплату №{{ $order->glukozaNumberFormatted() }} от {{ strftime('%d.%m.%Y', strtotime($order->date_order)) }}.
</div>

<div style="width: 100%; border-bottom: 2px solid #000;padding-top: 15px;"></div>

<table style="margin-top: 15px;">
    <tr>
        <td style="padding-right: 10px; vertical-align: top;">Поставщик:</td>
        <td class="invoice_stor">ООО "Сингер-Мед", ИНН 7814557450, КПП 781401001, 197372, Санкт-Петербург г, Камышовая, дом № 38, корпус 1,
            кв.54, тел.: +7 (812) 244-4192
        </td>
    </tr>
    <tr>
        <td style="padding-right: 10px; vertical-align: top;">Покупатель:</td>
        <td class="invoice_stor">
            {{ trim($order->fio) }}, {{ $order->phone }}, {{ $order->email }}
        </td>
    </tr>
</table>
<table class="invoice_table">
    <thead>
        <tr>
            <td>№</td>
            <td>Артикул</td>
            <td>Товары (работы, услуги)</td>
            <td>Кол-во</td>
            <td>Ед.</td>
            <td>Цена</td>
            <td>Сумма</td>
        </tr>
    <thead>
    <tbody>
        @foreach($order->productLinks as $index => $productLink)
            <tr>
                <td class="right">{{ $index + 1 }}</td>
                <td class="right">{{ $productLink->product->articul }}</td>
                <td class="left">{{ $productLink->product->name }}</td>
                <td class="right">{{ $productLink->amount }}</td>
                <td class="center">Упак</td>
                <td class="right">{{ number_format($productLink->price, 2, ',', ' ') }}</td>
                <td class="right">{{  number_format($productLink->price*$productLink->amount, 2, ',', ' ') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table style="float: right; width: 100%; font-size:12px; margin: 10px 0;">
    <tr>
        <td style="text-align: right;">Итого: </td>
        <td style="width: 100px; text-align: right;">
            {{ number_format($order->getProductsSum()+$order->getDeliveryPrice(), 2, ',', ' ') }}
        </td>
    </tr>
    @if($order->bonus > 0)
        <tr>
            <td style="text-align: right;">Скидка по бонусной программе: </td>
            <td style="width:100px;text-align:right;">{{ number_format($order->bonus, 2, ',', ' ') }}</td>
        </tr>
    @endif
    <tr>
        <td style="text-align: right;">Без налога (НДС) </td>
        <td style="width: 100px; text-align: right;">-</td>
    </tr>
    <tr>
        <td style="text-align: right;">Всего к оплате: </td>
        <td style="width: 100px; text-align: right;">{{ number_format($order->getPaymentSum(), 2, ',', ' ') }}</td>
    </tr>
</table>

<div>Всего наименований {{ count($order->productLinks) }}, на сумму {{ number_format($order->getPaymentSum(), 2, ',', ' ') }} руб.</div>
<div style="font-size: 14px;font-weight: bold;padding: 5px 0;">{{ priceWordString($order->getPaymentSum()) }} руб. 00 коп.</div>
<div style="width: 100%; border-bottom: 2px solid #000;"></div>
<div style="width: 100%; height: 20px;"></div>
<table style="width: 100%; margin: 20px 0;">
    <tr>
        <td style="width: 90px;">Руководитель</td>
        <td style="border-bottom: 1px solid #000; text-align: right;">Герасименко С.В.</td>
        <td style="width:110px;padding-left:40px;">Бухгалтер</td>
        <td style="border-bottom:1px solid #000;text-align:right;">Сингаевская А.И.</td>
    </tr>
</table>

<div style="font-size:12px;margin-top:40px;font-weight: bold;">
    ВНИМАНИЕ! Данный счет действителен в течение 5 (пяти) рабочих дней. В случае оплаты после истечения указанного срока Поставщик не гарантирует цену и наличие товара на складе.
</div>

@if($order->cour_id)
    <div class="cour_id">{{ $order->cour_id }}</div>
@endif

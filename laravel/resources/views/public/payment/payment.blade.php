@extends('public.app')

@section('title', 'Оплата заказа')

@section('content')

    @if($order)
        <div class="payment">
            <div class="card">
                <div class="card__content">
                    <div class="payment__title">Оплата заказа банковской картой</div>
                    <p class="text-muted font-s13">На оформление платежа Сбербанком выделяется 20 минут, приготовьте заранее Вашу банковскую карту.</p>

                    @include('public._partials.componentError')

                    <div class="payment__icon">
                        <svg fill="#555" enable-background="new 0 0 512 512" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                            <path d="m488 40h-464c-13.255 0-24 10.745-24 24v304c0 13.255 10.745 24 24 24h200v-16h-200c-4.418 0-8-3.582-8-8v-16h208v-16h-208v-272c0-4.418 3.582-8 8-8h464c4.418 0 8 3.582 8 8v168h16v-168c0-13.255-10.745-24-24-24z"/>
                            <path d="m112 80h-64c-4.418 0-8 3.582-8 8v64c0 4.418 3.582 8 8 8h64c4.418 0 8-3.582 8-8v-64c0-4.418-3.582-8-8-8zm-8 64h-48v-48h48z"/>
                            <path d="m432 80c-22.091 0-40 17.909-40 40s17.909 40 40 40c22.08-.026 39.974-17.92 40-40 0-22.091-17.909-40-40-40zm0 64c-13.255 0-24-10.745-24-24s10.745-24 24-24 24 10.745 24 24-10.745 24-24 24z"/>
                            <path d="m288 208v-16h-240c-4.418 0-8 3.582-8 8v64c0 4.418 3.582 8 8 8h200v-16h-192v-48z"/>
                            <path d="m40 296h96v16h-96z"/>
                            <path d="m152 296h16v16h-16z"/>
                            <path fill="#41aa47" d="m384 216c-70.692 0-128 57.308-128 128s57.308 128 128 128c70.658-.084 127.916-57.342 128-128 0-70.692-57.308-128-128-128zm0 240c-61.856 0-112-50.144-112-112s50.144-112 112-112c61.828.066 111.934 50.172 112 112 0 61.856-50.144 112-112 112z"/>
                            <path fill="#41aa47" d="m450.344 290.344-98.344 98.344-34.344-34.344-11.312 11.312 40 40c1.5 1.5 3.534 2.344 5.656 2.344s4.156-.844 5.656-2.344l104-104z"/>
                        </svg>
                    </div>

                    <div class="payment__order__title">Заказ №{{ $order->number_formatted }}</div>

                    <div class="payment__table">

                        <table class="table">
                            <tbody>
                                @foreach($order->productLinks as $productLink)
                                    <tr>
                                        <td>{{ $productLink->product->name }}</td>
                                        <td>
                                            @if($productLink->price)
                                            {{ $productLink->amount }} шт. x {{ $productLink->price }} руб.
                                            @else
                                                Бесплатно
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                {{--<tr>--}}
                                    {{--<td>Стоимость доставки</td>--}}
                                    {{--<td>{{ $order->getDeliveryPrice() }} руб.</td>--}}
                                {{--</tr>--}}
                                <tr class="font-w600 font-s16">
                                    <td>Сумма к оплате</td>
                                    <td>{{ $order->getPaymentSum() }} руб.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="payment__description">
                        <p class="text-muted font-s13">Для оплаты покупки Вы будете перенаправлены на платежный шлюз ОАО "Сбербанк России" для ввода реквизитов Вашей карты.</p>
                        <p class="text-muted font-s13">Соединение с платежным шлюзом и передача информации осуществляется в защищенном режиме с использованием протокола шифрования SSL.</p>
                    </div>
                    <div class="payment__btn">
                        <form action="/payment/{{ $order->id }}" method="post">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-lg btn-primary">Перейти к оплате</button>
                        </form>
                    </div>
                </div>
            </div>
            @include('public.payment.paymentFooter')
        </div>
    @else
        <div class="paymentResult">
            <div class="card">
                <div class="card__content">
                    <div class="paymentResult__title">Заказ не найден</div>
                    <div class="paymentResult__icon">
                        <img src="{{ $cdn }}/cards_payment/result-fail.png" alt="">
                    </div>
                    <div class="paymentResult__description">
                        Возможно ошибка в ссылке на оплату. Если вы уверены, что ссылка верна, сообщите по эл. почте или телефону.
                    </div>
                </div>
            </div>
            @include('public.payment.paymentFooter')
        </div>
    @endif

@stop
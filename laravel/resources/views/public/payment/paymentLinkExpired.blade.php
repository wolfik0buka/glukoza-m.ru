@extends('public.app')

@section('title', 'Оплата — срок действия ссылки истек')

@section('content')

    <div class="paymentResult">
        <div class="card">
            <div class="card__content">
                <div class="paymentResult__lead">Уважаемый покупатель!</div>
                <div class="paymentResult__title">Срок действия платежной ссылки истек</div>
                <div class="paymentResult__icon">
                    <img src="{{ $cdn }}/cards_payment/result-fail.png" alt="">
                </div>
                <div class="paymentResult__description">
                    Если ваш заказ актуален - обратитесь пожалуйста по эл. почте или телефону.
                </div>
            </div>
        </div>

        @include('public.payment.paymentFooter')

    </div>

@stop
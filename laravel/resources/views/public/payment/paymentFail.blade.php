@extends('public.app')

@section('title', 'Оплата — платеж не прошел')

@section('content')

    <div class="paymentResult">
        <div class="card">
            <div class="card__content">
                <div class="paymentResult__lead">Уважаемый покупатель!</div>
                <div class="paymentResult__title">Платёж не прошёл</div>
                <div class="paymentResult__icon">
                    <img src="{{ $cdn }}/cards_payment/result-fail.png" alt="">
                </div>
                <div class="paymentResult__description">
                    По вопросам, связанным с выполнением оплаченного заказа, обращайтесь по эл. почте или телефону.
                </div>
            </div>
        </div>

        @include('public.payment.paymentFooter')

    </div>

@stop
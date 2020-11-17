@extends('public.app')

@section('title', 'Оплата — успешно')

@section('content')

    <div class="paymentResult">
        <div class="card">
            <div class="card__content">
                <div class="paymentResult__lead">Уважаемый покупатель!</div>
                <div class="paymentResult__title">Платёж успешно совершён</div>
                <div class="paymentResult__icon">
                    <img src="{{ $cdn }}/cards_payment/result-success.png" alt="">
                </div>
                <div class="paymentResult__description">
                    По вопросам, связанным с выполнением оплаченного заказа, обращайтесь по эл. почте или телефону.
                </div>
            </div>
        </div>

        @include('public.payment.paymentFooter')

    </div>

@stop
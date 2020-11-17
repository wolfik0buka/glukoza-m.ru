@extends('public.mail.mailLayout')

@section('title', 'Оплата заказа')

@section('content')
    <p>Заказ №{{ $order->number_formatted }} подтвержден.</p>
    <p>
        <a href="{{ env('WEB_URL', 'https://glukoza-med.ru') }}/payment/{{ $order->payment_hash }}">Перейти к оплате заказа</a>
    </p>

@stop
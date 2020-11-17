<?php $step = 5 ?>
@extends('public.basket.basketLayout')

@section('title', 'Корзина: Подтверждение заказа')

@section('basket')
    <div class="panel top-20">
        <div class="row remove-margin">

            <div class="col-xs-12">
                <h3>Контактная информация:</h3>
            </div>

            <div class="col-xs-12">
                {{ Session::get('pers_data.fio') }},
                телефон: {{ Session::get('pers_data.phone') }}
                @if(Session::get('pers_data.email') != '') , e-mail: {{ Session::get('pers_data.email') }} @endif
                @if(Session::get('pers_data.comment') != '') ,комментарий: {{ Session::get('pers_data.comment') }} @endif
            </div>

            <div class="col-xs-12">
                <h3>Способ получения:</h3>
            </div>

            <div class="col-xs-12 bottom-30">
                {{ Session::get('delivery.name') }}
                @if((Session::get('delivery.id') == 6) || (Session::get('delivery.id') == 7) || (Session::get('delivery.id') == 11))
                    ({{ Session::get('delivery.point_name') }}, {{ Session::get('delivery.point_address') }},
                    тел: {{ Session::get('delivery.point_phone') }})
                @endif
                @if((Session::get('type.id') == 2))
                    - {{ Session::get('delivery.city_name') }} {{ Session::get('delivery.adr') }}
                @endif
            </div>
        </div>
    </div>

    <div class="bottom-40">
        <form name="backform" action="{{ route('input_personal_data') }}" method="post" id="back_form"></form>
        <button onclick="$('#back_form').submit()" type="button" class="btn btn-default">Назад</button>
        <button onclick="to_order()" class="btn btn-primary to_order">Оформить</button>
    </div>
@stop
<?php $step = 4 ?>
@extends('public.basket.basketLayout')

@section('title', 'Корзина: Контактная информация')

@section('basket')
    <div class="row">

        <div class="col-xs-12 bottom-15">
            <h3>Контактная информация:</h3>
        </div>

        <form method="post" action="{{ route('finish_input') }}" id="go_form">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Фамилия, Имя *</label>
                    <input @if(Session::get('user')) value="{{ Session::get('user.name') }}" @endif type="text" class="form-control" name="fio">
                </div>
                <div class="form-group">
                    <label>Телефон *</label>
                    <input @if(Session::get('user')) value="{{ Session::get('user.phone') }}" @endif type="text" class="form-control" name="phone">
                </div>
                <div class="form-group">
                    <label>E-mail *</label>
                    <input @if(Session::get('user')) value="{{ Session::get('user.email') }}" @endif type="text" class="form-control" name="email">
                </div>
                <div class="form-group">
                    <label>Комментарий к заказу</label>
                    <textarea class="form-control" name="comment"></textarea>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="pers_data">
                            Согласен на <a class="uni-link personal_data_checkbox" style="cursor: pointer;">обработку персональных данных</a>
                        </label>

                    </div>
                </div>
            </div>
        </form>

        <div class="col-sm-6 dop_fld">
            <div class="attention">
                <p class="title">ВНИМАНИЕ!</p>
                <p>При самовывозе со склада или из магазина Ваш заказ действителен в течение 3-х рабочих дней.</p>
                <p>Если Вы (или Ваш представитель) не забрали заказ своевременно - он аннулируется.</p>
            </div>
        </div>

        <form name="backform" action="{{ route('select_delivery') }}" method="post" id="back_form">
            <input type="hidden" name="delivery_type" value="{{ Session::get('type.id') }}">
        </form>

        <div class="col-xs-12 top-20">
            <button onclick="$('#back_form').submit()" type="button" class="btn btn-default">Назад</button>
            <button onclick="go_form_pers()" type="button" class="btn btn-primary">Далее</button>
        </div>

    </div>

@endsection
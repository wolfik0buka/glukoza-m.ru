@extends('public.app')

@section('title', $seo->title)
@section('description', $seo->description)
@section('keywords', $seo->keywords)

@section('content')
    <div class="page_PaymentCards">
        <div class="container">
            <div class="breadcrumbs top-15">
                <a href="/">Главная</a> <i class="fa fa-long-arrow-right"></i>
                {!! $seo->title !!}
            </div>
        </div>
        <div class="container">
            <div class="row bottom-30">
                <div class="col-xs-12">


                    <div class="card">
                        <div class="card-block arial font-s15">
                            <h1>{!! $seo->title !!}</h1>
                            <p><strong>Общество с ограниченной ответственностью "Сингер-Мед"</strong></p>
                            <p>ИНН 7814557450</p>
                            <p>Фактический адрес:  г. Санкт-Петербург, ул. Стародеревенская, дом 34к1, ТК «Мир», 2 этаж, секция 2-15</p>
                            <p>Юридический адрес: 197082, г. Санкт-Петербург, Камышовая улица, дом 38, корпус 1, пом. 54</p>
                            <p>Телефон: +7 (812) 244-41-02.</p>
                            <p>Электронная почта: glukoza@glukoza-med.ru</p>

                            <h3>Банковские реквизиты</h3>
                            <p>Расчетный счет 40702810855070001781 в банке СЕВЕРО-ЗАПАДНЫЙ БАНК СБЕРБАНКА РФ, БИК 044030653, к/с 30101810500000000653</p>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
@stop
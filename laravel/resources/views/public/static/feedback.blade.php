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
                            <p>
                                Если у вас есть замечания к работе сайта glukoza-med.ru или предложения по улучшению нашей
                                работы, оставьте отзыв в форме ниже.
                            </p>
                            <p>
                                Мы постараемся вам помочь, если информации на странице товара оказалось для вас недостаточно.
                            </p>
                            <p>
                                Вы также можете задать вопросы по телефонам горячей линии
                                <a href="tel:+78122443474 ">+7 (812) 244-34-74 </a>.
                            </p>

                            <div class="feedback">
                                <form class="feedback__form" id="feedback__form">
                                    <div class="row">
                                        <div class="top-30">
                                            <input type="text" class="fio_for_pot" name="pot">
                                            <div class="form-group col-sm-6">
                                                <label>Фамилия, Имя *</label>
                                                <input type="text" class="form-control" name="fio">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label>Телефон *</label>
                                                <input type="text" class="form-control" name="phone">
                                            </div>
                                            <div class="form-group col-xs-12">
                                                <label>Ваш вопрос *</label>
                                                <textarea  class="form-control" name="question"></textarea>
                                            </div>
                                            <div id="feedback__errors" class="col-xs-12"></div>
                                            <div id="feedback__success" class=" col-xs-12"></div>
                                            <div class="checkbox col-xs-12">
                                                <label>
                                                    <input type="checkbox" id="pers_data" checked name="pers_data">
                                                    Согласен на <a class="uni-link" target="_blank" href="/confirm">обработку
                                                        персональных данных</a>
                                                </label>
                                            </div>

                                            <div class="top-20 bottom-15 col-xs-12" >
                                                <input type="submit" id="#responseSubmit" class="btn btn-primary btn-mobile" value="Отправить">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@extends('public.app')

@section('title', $seo->title)
@section('description', $seo->description)
@section('keywords', $seo->keywords)

@section('opengraph')
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Глюкоза">
    <meta property="og:title" content="Глюкоза - Медицинская техника и товары для контроля диабета">
    <meta property="og:description" content="Интернет-магазин «Глюкоза» предлагает широкий ассортимент медицинской техники для использования в домашних и клинических условиях.">
    <meta property="og:url" content="https://glukoza-med.ru">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:image" content="https://glukoza-med.ru/img/main_page/social_cover.jpg">
    <meta property="og:image:width" content="968">
    <meta property="og:image:height" content="504">
@stop

@section('content')
    <div class="page_main">

        <div class="mainSliders__wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8">
                        <router-view name="mainSlider"></router-view>
                    </div>
                    <div class="col-sm-4">
                        <router-view name="mainSliderSmall" :products='{{ json_encode($main_slider_products) }}'></router-view>
                    </div>
                </div>
            </div>
        </div>


        {{--<div class="container">--}}
            {{--<div class="col-xs-12">--}}
                {{--@include('public.page_main.notice')--}}
            {{--</div>--}}
        {{--</div>--}}

        <router-view name="bestOffers" inline-template>
            @include('public.page_main.best_offers')
        </router-view>

        <div class="container">
            <div class="row top-40 bottom-40">

                <div class="col-md-6">
                    <div class="service service__lead">
                        <div class="service_content">
                            <h1 class="title">Медицинская техника и&nbsp;товары для&nbsp;контроля&nbsp;диабета</h1>
                            <ul>
                                <li>доставка заказов по всей России</li>
                                <li>наличный и безналичный расчет в магазинах</li>
                                <li>наглядная демонстрация товара при покупке</li>
                                <li>консультации со специалистами</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="service service_small">
                        <img src="/img/main_page/icon_loyalty.svg" alt="Бонусная программа интернет-магазина Глюкоза">
                        <div class="service_content">
                            <div class="title">Бонусная программа</div>
                            <p>Получайте бонусы за каждую покупку. Оплачивайте следующие покупки бонусами.</p>
                            <a href="/bonusnaya-programma">Подробнее</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="service service_small">
                        <img src="/img/main_page/icon_delivery.svg" alt="Доставка из интернет-магазина Глюкоза">
                        <div class="service_content">
                            <div class="title">Удобная доставка</div>
                            <p>Курьером по Санкт-Петербургу и пригородам, почтой или курьерской службой в регионы.</p>
                            <a href="/dostavka">Подробнее</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="bg-white">
            <div class="container">
                <div class="row top-40 bottom-40">

                    <div class="col-md-6 col-md-push-6">
                        <h2>{!! $page->title !!}</h2>
                        <div class="top-15 font-s14 text-muted">
                            <p><strong>Интернет-магазин «Глюкоза»</strong> предлагает широкий ассортимент медицинской
                                техники для использования в домашних и клинических условиях. Мы делаем все возможное для
                                расширения ассортимента предлагаемых товаров, чтобы у наших Клиентов был ВЫБОР! </p>
                            <div class="collapse" id="main_page_more">
                                <p>В нашем интернет-магазине представлены сахарозаменители на основе стевии, глюкометры,
                                    тест-полоски, анализаторы состава крови, инсулиновые помпы, устройства для введения
                                    инсулина, другие расходные материалы и сопутствующие товары, которые необходимы для
                                    контроля сахарного диабета людям с различным типом заболевания, возрастом, ритмом
                                    жизни, комплекцией, привычками и достатком. </p>
                                <p>Каждый день мы стараемся найти редкие товары, не представленные ни в аптечных сетях,
                                    ни в салонах медтехники и доставить их нашим покупателям.</p>
                                <p><strong>У нас скидка по дисконтной карте 5 % при покупке в магазине.</strong></p>
                                <p><strong>Мы принимаем в ремонт</strong> трансмиттеры МиниЛинк.</p>
                                <p><strong>Мы высказываем свое мнение</strong>, а не пытаемся просто продать.</p>
                                <p><strong>У нас:</strong></p>
                                <ul>
                                    <li>низкие цены;</li>
                                    <li>наличный и безналичный расчет;</li>
                                    <li>доставка заказов клиентам в течение 1-2 дней;</li>
                                    <li>обязательная наглядная демонстрация товара при покупке;</li>
                                    <li>консультации со специалистами;</li>
                                    <li>внимательное отношение к клиентам;</li>
                                    <li>особые условия для детей с сахарным диабетом.</li>
                                </ul>
                                <p>Мы предлагаем приборы и расходные материалы для людей, страдающих сахарным
                                    диабетом:</p>
                                <ul>
                                    <li>глюкометры и анализаторы состава крови;</li>
                                    <li>тест-полоски для глюкометров и анализаторов, а также визуальные полоски по
                                        моче;
                                    </li>
                                    <li>многоразовые и одноразовые автоланцеты;</li>
                                    <li>контрольные растворы;</li>
                                    <li>инсулиновые помпы Medtronic, наборы для инфузий инсулина, сенсоры, сертеры и
                                        мониторы;
                                    </li>
                                    <li>шприц-ручки и иглы к ним (от 4 мм);</li>
                                    <li>шприцы для введения инсулина;</li>
                                    <li>сахарозаменители на основе стевии;</li>
                                    <li>весы с отображением результатов в хлебных единицах.</li>
                                </ul>
                            </div>
                            <p>
                                <a class="btn btn-sm btn-default font-s13 top-10"
                                   data-toggle="collapse"
                                   data-target="#main_page_more">Читать далее</a>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6 col-md-pull-6">
                        @include('public.page_main.partners')
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop
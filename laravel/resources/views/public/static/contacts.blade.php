@extends('public.app')

@section('title', $seo->title)
@section('description', $seo->description)
@section('keywords', $seo->keywords)

@section('content')
    <script>
        function shop_map(name, point, center, id_box, zoom = 16) {
            ymaps.ready(init);

            function init() {
                var myMap = new ymaps.Map(id_box, {center: center, zoom: zoom});
                var myGeoObject = new ymaps.GeoObject({geometry: {type: "Point", coordinates: point}, properties: {}});
                myMap.geoObjects.add(myGeoObject);
            }
        }
    </script>


    <div class="page_contacts">
        <div class="container">
            <div class="breadcrumbs top-15">
                <a href="/">Главная</a> >
                {!! $page->title !!}
            </div>
            <h1>{!! $page->title !!}</h1>
        </div>
        <div class="container">
            <div class="row bottom-30">
                <div class="col-xs-12">
                    <div class="panel">
                        <div class="panel-body">



                            <div class="row bottom-30">
                                <div class="col-sm-6">
                                    <h3>Магазин «Глюкоза»</h3>
                                    <p>
                                        Станция метро Озерки (7 мин. пешком).<br>
                                        Санкт-Петербург, Улица Сикейроса, дом 10, корпус 4, литера А, ТК «Бульвар», секция 30.
                                    </p>
                                    <p>
                                        <strong>Режим работы:</strong> 10:00-20:00, без выходных
                                    </p>
                                    <p><strong>Телефон:</strong> {{ getSettings('phone') }}</p>
                                    <p><strong>E-mail:</strong> <a href="mailto:shop@glukoza-med.ru">shop@glukoza-med.ru</a></p>
                                </div>
                                <div class="col-sm-6">
                                    <h3>Работа с юридическими лицами</h3>

                                    <p>
                                    	<strong>Телефон:</strong>
                                    	<br>
                                    	+7 (812)244-41-02
                                    	<br>
                                    	+7 (812) 244-41-92
                                    </p>
                                    <p>
                                    	<strong>E-mail:</strong><br>
                                    	<a href="mailto:glukoza@glukoza-med.ru">glukoza@glukoza-med.ru</a>
                                    	<br>
                                    	<a href="mailto:tiv@glukoza-med.ru">tiv@glukoza-med.ru</a>
                                    	<br>
                                    	<a href="skv@glukoza-med.ru">skv@glukoza-med.ru</a>
                                    </p>
                                </div>
                                <div class="col-xs-12">
                                    <div id="map2" class="map"></div>
                                    <script>shop_map("Магазин Глюкоза", [60.035459, 30.329675], [60.035459, 30.329675], "map2");</script>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-xs-12">
                                    <h2>Наши партнеры в других регионах РФ:</h2>
                                    <p><strong>&quot;Медтехника 33&quot;</strong></p>
                                    <p>Поставщик оборудования и расходных материалов для интервенционной радиологии, эндо- и общей хирургии, а также товаров для контроля сахарного диабета.</p>
                                    <p><strong>Адрес:</strong> Россия, г.Владимир, ул. Батурина, д.39</p>
                                    <p><strong>Телефон:</strong> +7 (4922) 43-01-36</p>
                                    <p><strong>Сайт:</strong> medteh33.ru</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
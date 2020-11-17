@extends('public.app')

@section('java_box')
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    {{--@if(Session::get('type.id') == 1)--}}

    {{--@endif--}}
    <script src="/js/basket.js?v=3"></script>
    <script src="/js/jquery.bootstrap-touchspin.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 breadcrumbs_path">
                <div class="col-xs-2">Корзина</div>
                <div class="col-xs-2">Тип получения</div>
                <div class="col-xs-2 active">Способ получения</div>
                <div class="col-xs-2">Ввод личных данных</div>
                <div class="col-xs-2">Подтверждение заказа</div>
            </div>
        </div>
        <div class="row basket">
            <div class="basket_box">
                @if(count($result) > 0)
                    @include('public.basket.tovar_delivery_list')
                @else
                    <div class="col-xs-12 text-center">Корзина пуста</div>
                @endif
            </div>
            <div class="col-xs-12">
                <h3>{{ $type->dop }}:</h3>
            </div>
            <form method="post" action="{{ route('input_personal_data') }}" id="go_form">
                <div class="col-xs-6">
                    @foreach($delivery as $key => $value)
                        <div class="radio">
                            <label>
                                <input onclick="get_dop(this.value)" @if($key == 0) checked @endif type="radio"
                                       name="delivery" value="{{ $value->id }}">
                                {{ $value->name_full }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="col-xs-6 dop_fld">
                    @if(Session::get('type.id') == 1)
                        <div class="dop_wrap">
                            <div id="from_magaz">
                                <p><strong>Адреса магазинов:</strong></p>
                                <div class="form-group">
                                    <select class="form-control" name="delivery">
                                        <option value="1">
                                            г. Санкт-Петербург, Большой Сампсониевский пр., д. 62, оф. 202
                                        </option>
                                        <option value="5">
                                            г. Санкт-Петербург, ул. Сикейроса д. 10 к. 4 лит А ТК "Бульвар" помещение
                                            4/2
                                        </option>
                                        <option value="8">
                                            г. Вологда, ул.Благовещенская, д. 26
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div id="from_point">
                                <p><strong>Точки самовывоза:</strong></p>
                                <div class="form-group">
                                    <label><strong>Выберите свой город</strong></label>
                                    <button type="button" class="btn btn-default" id="map_button">Выбрать</button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </form>
            <form name="backform" action="{{ route('select_type') }}" method="post" id="back_form"></form>
            <div class="col-xs-12">
                <button onclick="$('#back_form').submit()" type="button" class="btn btn-danger">Назад</button>
                <button onclick="go_form()" type="button" class="btn btn-success">Далее</button>
            </div>

            {{--<input style="margin-top: 20px; width: 500px; font-size: 18px" type="button" id="map_button"--}}
            {{--value="Нажмите, чтобы создать карту пунктов выдачи"/>--}}
            {{--Выбаранная точка:--}}
            {{--<div id="test"></div>--}}

        </div>
    </div>
    <script type="text/javascript">
        // Настройки виджета:
        // Если объект map_config не задан, настройки будут использованы "по умолчанию":
        var map_config = {
            "target": "map_button", // id кнопки - по умолчанию map_button
            "city": "137", // id_city - по умолчанию 137 - Санкт-Петербург, можно использовать функцию
            "onload": null, // функция вызываемая по загрузке виджета - по умолчанию ничего не вызывается
            "onselect": function (info) { // функция выбора ПВЗ - по умолчанию только console.log(info);
                console.log(info);
                alert("Выбрана точка:"
                    + "\ninfo.city_id: " + info.city_id
                    + "\ninfo.id_obl: " + info.id_obl
                    + "\ninfo.city_name: " + info.city_name
                    + "\ninfo.point.id: " + info.point.id
                    + "\nindo.point.address: " + info.point.address
                    + "\ninfo.point.name: " + info.point.name
                    + "\ninfo.point.phone: " + info.point.phone
                    + "\ninfo.point.time: " + info.point.time
                    + "\ninfo.point.work: " + info.point.work
                    + "\ninfo.point.weight: " + info.point.weight
                );
                document.getElementById("test").innerHTML = info.point.name;
            },
            "oncancel": function () { // функиця отмены выбора - по умолчанию только console.log(message)
                console.log('map select cancel');
                alert('Выбор отменен!');
            },
            "show_price": true, // показывать поле стоимость - по умолчанию true
            "show_button": true, // показывать кнопку "Заберу отсюда"
            "price": function (value) { // функция вызываемая при формировании строки стоимости - по умолчанию return value;
                return value + ' Ваши данные (например доп сбор 150р.)'
            }
        };
    </script>
    <script src="/js/lk-map.js?=1"></script>
@endsection
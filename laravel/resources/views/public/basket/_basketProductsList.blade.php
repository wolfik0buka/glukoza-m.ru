@if(count($result) > 0)

        <div class="row top-20">
            <div class="col-xs-12">
                <div class="panel">
                <div class="list-group">
                    @foreach($result as $key => $item)
                        <div class="list-group-item">
                            <div class="row top-10 bottom-10">

                                <div class="col-sm-3 text-center bottom-10">
                                    <img src="/img/resize/resize.php?src=https://glukoza-med.ru/img/catalog/pic_{{ $item->id }}.jpg&h=100&q=90" alt="">
                                    <a class="block text-muted font-s12" href="{{ route('basket.del_item', ['id' => $item->id]) }}">
                                        Удалить из заказа
                                    </a>
                                </div>

                                <div class="col-sm-9">
                                    <div class="font-s16 font-w500 bottom-15">
                                        <a target="_blank" href="{{ link_product($item->linksToCats->first()->id_cat,$item->id) }}">
                                            {{ $item->name }}
                                        </a>
                                    </div>
                                    <p>
                                        Цена:
                                        @if($item->podzakaz == 0)
                                            @if(count($item->tovar_1c_att) > 0)
                                                {{ priceFull($item->tovar_1c_att->price) }}
                                            @else
                                                {{ priceFull(0) }}
                                            @endif
                                        @else
                                            {{ priceFull($item->price) }}
                                        @endif
                                        руб.
                                    </p>
                                    <div class="spinner_wrapper">
                                        Количество:
                                        <input class="spiner form-control" type="text" name="amount"
                                               style="text-align: center;"
                                               data-id="{{ $item->id }}"
                                               value="{{ Session::get('basket')[$item->id] }}">
                                    </div>
                                    <div class="top-10">
                                        Сумма:
                                        <span id="sum{{ $item->id }}">
                                            @if($item->podzakaz == 0)
                                                @if(count($item->tovar_1c_att) > 0)
                                                    {{ priceFull(Session::get('basket')[$item->id]*$item->tovar_1c_att->price) }}
                                                @else
                                                    {{ priceFull(0) }}
                                                @endif
                                            @else
                                                {{ priceFull(Session::get('basket')[$item->id]*$item->price) }}
                                            @endif
                                        </span>
                                        руб.
                                    </div>
                                </div>
                            </div>


                        </div>
                    @endforeach

                    @if(Session::get('delivery.price') != 0)
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9">
                                    <div class="font-s16 font-w500 bottom-15">
                                        {{ Session::get('delivery.name') }}
                                    </div>
                                    Цена: {{ priceFull(Session::get('delivery.price')) }} руб.
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="list-group-item text-center">
                        <span class="font-s16 font-w500">
                            Итого:
                            <span id="itog">
                                {{ priceFull( Session::get('delivery.price') + $result->sum(function($obj) {
                                       if($obj->podzakaz == 0) {
                                           if(count($obj->tovar_1c_att) > 0) {return Session::get('basket')[$obj->id]*$obj->tovar_1c_att->price;} else {return 0;}
                                       } else {
                                           return Session::get('basket')[$obj->id]*$obj->price;
                                       }
                                    }))
                                }}
                            </span>  руб.
                        </span>
                    </div>
                    @if(Session::has('user'))
                        <tr>
                            <th style="text-align: right;">Будет списано бонусов:</th>
                            <th style="text-align: right;">{{ number_format(Session::get('user.bonus_use'), 2, ',', ' ') }}</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th style="text-align: right;">К оплате:</th>
                            <th id="itog" style="text-align: right;">
                                {{ number_format(Session::get('delivery.price') + $result->sum(function($obj) {
                                       if(count($obj->tovar_1c_att) > 0) {return Session::get('basket')[$obj->id]*$obj->tovar_1c_att->price;} else {return 0;}
                                    }) - Session::get('user.bonus_use'), 2, ',', ' ')
                                }}
                            </th>
                            <td></td>
                        </tr>
                        @endif
                    </tfoot>
                </div> {{-- list-group --}}
                    </div>
                @if(Session::has('user') && \Route::currentRouteName() == 'basket')
                    <form class="form-inline bottom-15" method="post" id="bonus_use" action="{{ route('bonus_use') }}">
                        <div class="form-group">
                            <label>Накоплено {{ Session::get('user.bonus_all') }} бонусов, использовать</label>
                            <input type="text" class="form-control" name="bonus_use" value="{{ Session::get('user.bonus_all') }}">
                            <button class="btn btn-success" onclick="bonus_use()">Применить</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
@else
    <div class="panel top-30">
        <div class="panel-body">
            <div class="col-xs-12 text-center">Корзина пуста</div>
        </div>
    </div>
@endif
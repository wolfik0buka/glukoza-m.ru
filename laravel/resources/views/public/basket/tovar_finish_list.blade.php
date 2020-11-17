<div class="col-xs-12">

    <table class="table table-condensed">
        <thead>
        <tr>
            <th>№ п/п</th>
            <th>Наименование</th>
            <th>Цена, руб</th>
            <th>Кол-во, шт</th>
            <th>Стоимость, руб</th>
        </tr>
        </thead>
        <tbody>
        @foreach($result as $key => $item)
            <tr>
                <td>
                    {{ $key+1 }}
                </td>
                <td>{{ $item->name }}</td>
                <td style="text-align: right;">
                    @if($item->podzakaz == 0)
                        @if(count($item->tovar_1c_att) > 0)
                            {{ number_format($item->tovar_1c_att->price, '2', ',', ' ') }}
                        @else
                            {{ number_format(0, '2', ',', ' ') }}
                        @endif
                    @else
                        {{ number_format($item->price, '2', ',', ' ') }}
                    @endif
                </td>
                <td style="text-align: center; width: 150px;">{{ Session::get('basket')[$item->id] }}</td>
                <td id="sum{{ $item->id }}" style="text-align: right;">
                    @if($item->podzakaz == 0)
                        @if(count($item->tovar_1c_att) > 0)
                            {{ number_format(Session::get('basket')[$item->id]*$item->tovar_1c_att->price, '2', ',', ' ') }}
                        @else
                            {{ number_format(0, '2', ',', ' ') }}
                        @endif
                    @else
                        {{ number_format(Session::get('basket')[$item->id]*$item->price, '2', ',', ' ') }}
                    @endif
                </td>
            </tr>
        @endforeach
        @if(Session::get('delivery.price') != 0)
            <tr>
                <td></td>
                <td>{{ Session::get('delivery.name') }}</td>
                <td style="text-align: right;">{{ number_format(Session::get('delivery.price'), '2', ',', ' ') }}</td>
                <td style="text-align: center; width: 150px;">1</td>
                <td style="text-align: right;">{{ number_format(Session::get('delivery.price'), '2', ',', ' ') }}</td>
                <td></td>
            </tr>
        @endif
        </tbody>
        <tfoot>
        <tr>
            <td colspan="4" style="text-align: right;">Итого:</td>
            <td id="sum_all" style="text-align: right;">
                {{ number_format(Session::get('delivery.price') + $result->sum(function($obj) {
                       if ($obj->podzakaz == 0) {
                           if (count($obj->tovar_1c_att) > 0) return Session::get('basket')[$obj->id]*$obj->tovar_1c_att->price; else return 0;
                       } else {
                           return Session::get('basket')[$obj->id]*$obj->price;
                       }
                    }), 2, ',', ' ')
                }}
            </td>
        </tr>
        @if(Session::has('user'))
            <tr>
                <th colspan="4" style="text-align: right;">Будет списано бонусов:</th>
                <th style="text-align: right;">{{ number_format(Session::get('user.bonus_use'), 2, ',', ' ') }}</th>
                <td></td>
            </tr>
            <tr>
                <th colspan="4" style="text-align: right;">К оплате:</th>
                <th id="sum_all" style="text-align: right;">
                    {{ number_format(Session::get('delivery.price') + $result->sum(function($obj) {
                           if ($obj->podzakaz == 0) {
                               if(count($obj->tovar_1c_att) > 0) return Session::get('basket')[$obj->id]*$obj->tovar_1c_att->price; else return 0;
                           } else {
                               return Session::get('basket')[$obj->id]*$obj->price;
                           }
                        }) - Session::get('user.bonus_use'), 2, ',', ' ')
                    }}
                </th>
                <td></td>
            </tr>
        @endif
        </tfoot>
    </table>
</div>
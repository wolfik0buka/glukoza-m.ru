<div class="col-xs-12">
    <table class="table table-condensed">
        <thead>
        <tr>
            <th>№ п/п</th>
            <th>Фото</th>
            <th>Наименование</th>
            <th>Цена, руб</th>
            <th>Кол-во, шт</th>
            <th>Стоимость, руб</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($result as $key => $item)
            <tr>
                <td>
                    {{ $key+1 }}
                </td>
                <td>
                    @if($item->usluga != 1)
                        <img src="/img/resize/resize.php?src=/img/catalog/pic_{{ $item->id }}.jpg&w=80&h=80&q=100">
                    @endif
                </td>
                <td>{{ $item->name }}</td>
                <td>
                    @if($item->podzakaz == 0)
                        {{ number_format($item->tovar_1c_att->price, '2', ',', ' ') }}
                    @else
                        {{ number_format($item->price, '2', ',', ' ') }}
                    @endif
                </td>
                <td style="text-align: center; width: 150px;">
                    <input class="spin form-control" type="text" name="amount"
                           style="text-align: center;"
                           data-id="{{ $item->id }}"
                           value="{{ Session::get('basket')[$item->id] }}">
                </td>
                <td id="sum{{ $item->id }}">
                    @if($item->podzakaz == 0)
                        {{ number_format(Session::get('basket')[$item->id]*$item->tovar_1c_att->price, '2', ',', ' ') }}
                    @else
                        {{ number_format(Session::get('basket')[$item->id]*$item->price, '2', ',', ' ') }}
                    @endif
                </td>
                <td>
                    <a href="{{ route('basket.del_item', ['id' => $item->id]) }}">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5" style="text-align: right;">Итого:</td>
            <td id="sum_all">
                {{ number_format($result->sum(
                       function($obj) {
                           if($obj->podzakaz == 0) {
                               return Session::get('basket')[$obj->id]*$obj->tovar_1c_att->price;
                           } else {
                               return Session::get('basket')[$obj->id]*$obj->price;
                           }
                       }), 2, ',', ' '
                   ) }}
            </td>
            <td></td>
        </tr>
        </tfoot>
    </table>
</div>
@include('public.basket.js')
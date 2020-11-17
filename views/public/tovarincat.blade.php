<div class="col-xs-12">
    <div class="row">
        <div class=" col-xs-12 tovar_item">
            <div class="col-xs-3">
                <img class="img_in_cat"
                     src="/img/resize/resize.php?src=/img/catalog/pic_{{ $result->id }}.jpg&w=180&q=100"
                     alt="{{ str_replace('"', '', $result->name)}}'">
            </div>
            <div class="col-xs-9">
                <div class="col-xs-12">
                    <a id="tovarName{{ $result->id }}" class="productName"
                       href="index.php?page=cat&amp;cat={{ $result->idCat }}&amp;idTov={{ $result->id }}">{{ $result->name }}</a>
                </div>
                <div class="col-xs-12 tb_5 art">
                    Артикул: {{ str_repeat('0', 5 - strlen($result->id))}}{{ $result->id }}</div>
                <div class="col-xs-12 tb_5 desc">{!! $result->description !!}</div>
                <div class="col-xs-12 tb_5">
                    <button type="button" class="btn btn-primary btn-sm"
                            onClick=window.open("index.php?page=cat&amp;cat={{ $result->idCat }}&amp;idTov={{ $result->id }}","_self")>
                        Подробнее
                    </button>
                    @if (($circle != 'red') || ($row->pres == 1) || ($row->podzakaz == 1))
                        <button type="button" class="btn btn-primary btn-sm"
                                onClick="addToBasket({{ $result->id }}, {{ $result->price_1c }})">В корзину
                        </button>
                    @endif
                </div>
                <div class="col-xs-12 price tb_10">
                    <div style="background-color: {{ $circle }}" class="circle"></div>
                    Цена: <span>{{ number_format($result->price_1c, 2, '-', ' ') }}</span> руб
                </div>
            </div>
        </div>
    </div>
</div>
@if(count($product->linksToCats) > 0)
    <div class="@if(isset($card_size)) {{ $card_size }} @else col-sm-6 @endif remove-padding">
        <div class="product_card">
            <div class="product_wrap">
                <div class="product__labels">
                @if($product->is_sale)
                    <span class="label label-danger">Акция</span>
                @endif
                @if($product->is_discont)
                    <span class="label label-primary">Скидка</span>
                @endif
                </div>
                
                <div class="pic col-xs-4">
                    <a href="/product/{{ $product->symbol_code }}">
                        <img
                            alt="{{ $product->name }}"
                            src="{{ $cdn }}/products/{{ $product->id }}/xs.jpg">
                    </a>
                </div>
                <div class="info col-xs-8">
                    @if((isset($product->tovar_1c_att->pres) && $product->tovar_1c_att->pres == 1) || ($product->podzakaz == 1))
                        <span class="text-success arial font-s13">
                            <i class="right-5 fa fa-circle"></i>В наличии
                        </span>
                    @else
                        <span class="text-danger arial font-s13">
                            <i class="right-5 fa fa-circle"></i>Отсутствует
                        </span>
                    @endif
                    <span class="text-muted font-s13 arial left-15 text-nowrap">
                        Арт. {{ str_repeat('0', 5 - strlen($product->id))}}{{ $product->id }}
                    </span>

                    <a class="product_name top-10"
                       href="/product/{{ $product->symbol_code }}">
                        {{ $product->name }}
                    </a>

                    @if((isset($product->tovar_1c_att->pres) && $product->tovar_1c_att->pres == 1) || ($product->podzakaz == 1))
                        <div class="product_price top-5">
                            @if(isset($product->price_old) and ($product->price_old > 0) and ($product->is_sale or $product->is_discont))
                            <div class="product_price__old">
                                {{$product->price_old}} руб
                            </div>
                            <div class="product_price__value product_price__value-sale">
                                <span class="font-s16 font-w500 bottom-10 text-nowrap">
                                    <span class="hidden-xs">Цена </span>
                                    @if($product->podzakaz == 1)
                                        {{ $product->price }}
                                    @else
                                        {{ $product->tovar_1c_att->price }}
                                    @endif
                                    <span class="font-w400 font-s15"><i class="fa fa-rub"></i></span>
                                </span>
                            </div>
                            @else
                            <div class="product_price__value">
                                <span class="font-s16 font-w500 bottom-10 text-nowrap">
                                    <span class="hidden-xs">Цена </span>
                                    @if($product->podzakaz == 1)
                                        {{ $product->price }}
                                    @else
                                        {{ $product->tovar_1c_att->price }}
                                    @endif
                                    <span class="font-w400 font-s15"><i class="fa fa-rub"></i></span>
                                </span>
                            </div>
                            @endif
                            <add-to-basket :product-id="{{ $product->id }}"
                                           :price="{{ ($product->podzakaz == 1) ? $product->price : $product->tovar_1c_att->price }}"
                                           name="{{ $product->name }}"
                                           pic="/img/resize/resize.php?src=https://glukoza-med.ru/img/catalog/pic_{{ $product->id }}.jpg&h=100&q=90"
                                           link="{{ link_product($product->linksToCats->first()->id_cat, $product->id) }}"
                                           inline-template>
                                <button type="button" class="btn btn-primary" v-on:click="add()">
                                    <i class="right-5 right-xs-0 fa fa-cart-plus"></i>
                                    <span class="hidden-xs">Купить</span>
                                </button>
                            </add-to-basket>
                        </div>
                    @endif
                    <div class="product_desc show_is_hover">{!! strip_tags($product->description, '<a>') !!}</div>
                </div>
            </div>
        </div>
    </div>
@endif
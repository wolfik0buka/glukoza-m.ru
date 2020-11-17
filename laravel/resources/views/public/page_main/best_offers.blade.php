<div class="offers">
    <div class="bg-white">
        <div class="container">
            <div class="row">

                <div class="container-fluid">
                    <div class="row top-30">
                        <div class="col-sm-6">
                            <div class="h2 bottom-15 top-0 color-text-default">
                                 Лучшие предложения
                            </div>
                        </div>
                        <div class="col-sm-6 font-w500 font-s16 text-xs-left text-right">
                            <a class="right-30" href="{{ $cat_url }}aktsii"><i class="right-5 fa fa-tags"></i>Акции</a>
                            <a href="{{ $cat_url }}diskont"><i class="right-5 fa fa-tags"></i>Дисконт</a>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 top-15">
                    <div class="products bottom-30 remove-border">

                        @foreach($bestOffers as $product)
                            @if(($product->tovar_1c_att["pres"] == 1) || ($product->podzakaz == 1))
                                @include('public.cats.product_card', [
                                    'product' => $product,
                                    'card_size' => 'bestOffers__item col-xs-12 col-sm-6 col-md-4'
                                    ])
                            @endif
                        @endforeach

                        <div class="row"></div>

                        <div v-if="hiddenProductsCount > 0" class="text-center top-20 bottom-15" v-cloak>
                            <span class="more_link" v-cloak v-if="!isShowAll" @click="show">Показать еще (@{{ hiddenProductsCount }})</span>
                            <span class="more_link" v-cloak v-if="isShowAll" @click="hide">Свернуть</span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
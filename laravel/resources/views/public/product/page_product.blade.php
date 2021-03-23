@extends('public.app')

@section('title', isset($seo) ? $seo->title : $product->name.' купить')
@section('description', isset($seo) ? $seo->description : '')
@section('keywords', isset($seo) ? $seo->keywords : '')

@section('content')
    <div class="bg-white">
        <div class="page_product">
        	<div class="page_product__card">
	            <div class="container">
	                @include('public.product._productBreadcrumbs')
	                <div class="row">
	                    <div class="col-sm-8">
	                        <h1>{{ isset($seo) ? $seo->h1 : $product->name }}</h1>

	                        <div class="page_product__pics">
	                            <img
	                                src="{{ $cdn }}/products/{{ $product->id }}/md.jpg"
	                                alt="{{ $product->name }}">
	                        </div>
	                    </div>
	                    <div class="col-sm-4">
	                        <div class="page_product__buyBlock">
                                @if ($product->is_sale || $product->is_discont)
                                <div class="product__labels bottom-10">
                                    @if($product->is_sale)
                                        <span class="label label-danger">Акция</span>
                                    @endif
                                    @if($product->is_discont)
                                        <span class="label label-primary">Скидка</span>
                                    @endif
                                </div>
                                @endif
	                            @if((isset($product->tovar_1c_att->pres) && $product->tovar_1c_att->pres == 1) || ($product->podzakaz == 1))

	                                <p class="text-muted font-s13">Артикул {{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</p>

	                                @if($product->podzakaz == 1)
	                                    <div class="price">
	                                        Цена <strong>{{ $product->price }}</strong> 
                                            @if ($product->price_old and ($product->is_sale or $product->is_discont))
                                                <span class="price__old">{{$product->price_old}}</span>
                                            @endif
                                            руб.
	                                    </div>
	                                @else
	                                    <div class="price">
	                                        Цена <strong>{{ $product->tovar_1c_att->price }}</strong> руб.
	                                    </div>
	                                @endif

	                                <add-to-basket
	                                    :product-id="{{ $product->id }}"
	                                    :price="{{ ($product->podzakaz == 1) ? $product->price : $product->tovar_1c_att->price }}"
	                                    name="{{ $product->name }}"
	                                    pic="/img/resize/resize.php?src=https://glukoza-med.ru/img/catalog/pic_{{ $product->id }}.jpg&h=100&q=90"
	                                    link="/product/{{ $product->symbol_code }}"
	                                    inline-template>
	                                    <button type="button" class="btn btn-primary btn-block btn-lg top-10 bottom-10" v-on:click="add()">
	                                        <i class="right-5 fa fa-cart-plus"></i> Купить
	                                    </button>
	                                </add-to-basket>

	                                <div class="page_product__availableStatus">
	                                    <i class="fa fa-check right-5 text-success"></i> В наличии, доставим
	                                    {{ \Carbon\Carbon::now()->format("Hi") < '1729' ? 'сегодня' : 'завтра' }}
	                                </div>

	                                <div class="productDelivery">
	                                    <div class="font-s15">Доставка и самовывоз</div>
	                                    <a href="#anchor_spb" class="productDelivery__link">Санкт-Петербург</a>
	                                    <a href="#anchor_rf" class="productDelivery__link">Другие города РФ</a>
	                                </div>
	                            @else
	                                <request-product-out-of-stock
	                                    :id="{{ $product->id }}"
	                                    v-cloak>
	                                </request-product-out-of-stock>
	                            @endif
	                        </div>
	                    </div>
	                </div>
	            </div>
	            @if (count($product->relatedProducts->where('is_available', true)) > 0)
		            <div class="container">
		            	<div class="row">		            		 
                            <div class="bottom-40">
                                <div class="h2">Сопутствующие товары</div>
                            </div>                     
		            	</div>
		            </div>
		            <div class="container-fluid">
		            	<div class="row left-0 right-0">
	                        <div class="products products_row bottom-20">
	                            @foreach($product->relatedProducts->where('is_available', true) as $relatedProduct)
	                                @include('public.cats.product_card', [
	                                    'product' => $relatedProduct,
	                                    'card_size' => 'related-product'
	                                ])
	                            @endforeach
	                        </div>
	                    </div>
		            </div>
		            
	             @endif
	            <div class="container">
	            	<div class="row">
	                    <div class="col-sm-12 top-20">
                            <div id="product__tabs">
                            <ul>
                                @if(mb_strlen($product->description) > 5 || mb_strlen($product->desc_full) > 5)
                                    <li><a href="#product_description"><span>Описание</span></a></li>
                                @endif
                                <li><a href="#product_delivery"><span>Доставка и самовывоз</span></a></li>

                                <li><a href="#product_responses"><span>Отзывы</span></a></li>

                            </ul>
                            @if(mb_strlen($product->description) > 5 || mb_strlen($product->desc_full) > 5)
                            <div id="product_description">
                                @include('public.product._productDescription')
                            </div>
                            @endif
                            
                            <div id="product_delivery">
                                @include('public.product._productDelivery')
                            </div>
                            <div id="product_responses">
                                @include('public.product._productResponses')
                            </div>
                            </div>

	                        
	                        
	                    </div>
	                    <!-- <div class="col-sm-4"></div> -->
	                </div>
	            </div>
            </div>

        </div>
    </div>


    <div class="container">
        <div class="row top-30 bottom-40">
            <div class="col-xs-12">
                <div class="h2">
                    Еще из раздела <a title="{{ $cat->name }} в интернет-магазине" href="{{ $cat_url.$cat->slug }}">{{ $cat->name }}</a>
                </div>
            </div>
            <div class="products">
                <div class="col-xs-12">
                    @foreach($related as $product)
                        @include('public.cats.product_card', ['product' => $product, 'card_size' => 'col-sm-6 col-md-4'])
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@stop
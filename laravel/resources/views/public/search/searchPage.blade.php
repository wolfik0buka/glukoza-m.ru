@extends('public.app')

@section('title')Поиск@stop

@section('content')
    <div class="container">
        <div class="row top-15 bottom-30">

            <div class="col-xs-12">

                <div class="breadcrumbs">
                    <a href="/">Главная</a> >
                    Поиск по сайту
                </div>

                <h1>Поиск по сайту</h1>

                <div class="panel">
                    <div class="panel-body font-s16 text-center">
                        <p>Результаты поиска по запросу «{{ $query }}»</p>
                        <p class="bottom-0 font-w500 font-s20">Найдено товаров: {{ count($products) }} ({{ count($products->where('available', 1)) }} в наличии)</p>
                    </div>
                </div>

                @if(count($products))
                    <div class="products">
                        @foreach($products as $product)
                            @include('public.cats.product_card', ['card_size' => 'col-sm-6'])
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
@stop
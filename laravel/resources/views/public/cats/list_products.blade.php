@extends('public.app')

@section('title', $seo->title)
@section('description', $seo->description)
@section('keywords', $seo->keywords)

@section('content')
    <div class="container">
        <div class="row top-15 bottom-30">

            <div class="col-sm-12 col-md-9 col-md-push-3 bottom-30">
                @include('public.cats.breadcrumbs')
                <h1>{{ mb_strlen($cat->h1) > 3 ? $cat->h1 : $cat->name }}</h1>
                @if(count($cat->childs) > 0)
                    <div class="child_cats top-15 bottom-30">
                        <div class="row">
                            @each('public.cats.category_card', $cat->childs, 'cat')
                        </div>
                    </div>
                @endif

                @if(count($cat->products) > 0)
                    <div class="sortLine bottom-20">
                        <span class="right-15">Сортировать:</span>
                        @foreach($sort_links as $link)
                            <a href="{{$link['link']}}" class="sortLine__link">
                                {{$link['name']}}
                                @if ($link['order'] === 'asc')
                                    <i class="fa fa-sort-amount-asc"></i>
                                @else
                                    <i class="fa fa-sort-amount-desc"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                    <div class="products">
                        @foreach($cat->products as $product)
                            @include('public.cats.product_card', ['card_size' => 'col-sm-6'])
                        @endforeach
                    </div>
                @endif

                @if($cat->down_text)
                    <div class="row ">
                        <div class="col-sm-12 panel top-15 bottom-30">
                            <br>
                            {!! $cat->down_text !!}
                        </div>
                    </div>
                        
                @endif
            </div>

            <div class="col-sm-12 col-md-3 col-md-pull-9 ">
                @include('public.cats.sidebar')
            </div>

        </div>
    </div>
@stop
@extends('public.app')

@section('title', $seo->title)
@section('description', $seo->description)
@section('keywords', $seo->keywords)

@section('content')
    <div class="page_static">
        <div class="container">
            <div class="breadcrumbs top-15">
                <a href="/">Главная</a> >
                {!! $page->title !!}
            </div>
            <h1>{!! $page->title !!}</h1>
        </div>
        <div class="container">
            <div class="row bottom-30">
                <div class="col-xs-12">
                    <div class="panel">
                        <div class="panel-body arial">
                            {!! $page->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
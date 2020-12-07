@extends('public.app')

@section('title', $seo->title)
@section('description', $seo->description)
@section('keywords', $seo->keywords)

@section('content')
    <div class="container">
        <div class="breadcrumbs top-15">
            <a href="/">Главная</a> >
            {!! $seo->title !!}
        </div>
    </div>
    <div class="container">
        @foreach($responses as $response)
            <div class="row top-15 bottom-30">
                <div class="col-xs-12">
                    <div class="card">
                        <div class="response">
                            <div class="response__head">
                                <div class="response__author">{{$response->fio}}</div>
                                <div class="response__date">{{$response->created_at->format('d-m-Y')}}</div>
                            </div>
                            <div class="response__body">
                                {{$response->response}}
                            </div>
                            @if ($response->answer)
                                <div class="response__answer">
                                    {{$response->answer}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop
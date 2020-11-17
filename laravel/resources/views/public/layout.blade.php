@extends('public.app')

@section('title', $data['seo']['title'])
@section('description', $data['seo']['description'])
@section('keywords', $data['seo']['keywords'])

@section('content')
    <div class="bg-white">

        <div class="page_head">
            <div class="container">
                <h1>{!! $data['page']['h1'] !!}</h1>
            </div>
        </div>

        <div class="breadcrumbs">
            <div class="container">
                Главная
            </div>
        </div>

        <div class="container">
            <div class="row bottom-30">
                <div class="col-xs-12 top-10">
                    {!! $data['page']['content'] !!}
                </div>
            </div>
        </div>

    </div>
@stop
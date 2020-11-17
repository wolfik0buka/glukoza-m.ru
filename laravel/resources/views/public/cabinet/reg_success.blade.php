@extends('public.app')

@section('title', 'Регистрация пользователя')

@section('content')
    <div class="page_static">
        <div class="container">
            <div class="breadcrumbs top-15">
                <a href="#">Главная</a> > Регистрация
            </div>
            <h1>Регистрация</h1>
        </div>
        <div class="container">
            <div class="row bottom-30">
                <div class="col-xs-12 top-5">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="col-xs-12 top-15 bottom-15">{{ $txt }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
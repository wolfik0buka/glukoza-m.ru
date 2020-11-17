<?php $step = 1 ?>
@extends('public.basket.basketLayout')

@section('title', 'Корзина: Мои товары')

@section('basket')
    <div class="row">

        @if(count($result))
            <div class="col-xs-12">
                <a href="{{ route('select_type') }}" class="btn btn-primary">Продолжить</a>
            </div>
        @endif

    </div>
@endsection
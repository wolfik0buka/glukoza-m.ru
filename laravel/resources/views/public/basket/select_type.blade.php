<?php $step = 2 ?>
@extends('public.basket.basketLayout')

@section('title', 'Корзина: Тип получения заказа')

@section('basket')

    <div class="row">

        <div class="col-xs-12">
            <h3>Тип получения заказа:</h3>
        </div>

        <form method="post" action="{{ route('select_delivery') }}">
            <div class="col-xs-4">
                @foreach($types as $key => $value)
                    <div class="radio">
                        <label>
                            <input @if(Session::has('type'))
                                   @if(Session::get('type.id') == $value->id) checked @endif
                                   @else
                                   @if($key == 0) checked @endif
                                   @endif
                                   type="radio"
                                   name="delivery_type"
                                   value="{{ $value->id }}">
                            {{ $value->name }}
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="col-xs-12 top-20">
                <a href="{{ route('basket') }}" class="btn btn-default">Назад</a>
                <button type="submit" class="btn btn-primary">Далее</button>
            </div>
        </form>

    </div>

@endsection
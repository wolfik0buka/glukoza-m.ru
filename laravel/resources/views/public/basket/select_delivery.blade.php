<?php $step = 3; ?>
@extends('public.basket.basketLayout')

@section('title', 'Корзина: Способ получения')

@section('basket')

    <div class="row">

        <div class="col-xs-12">
            <h3>{{ $type->dop }}:</h3>
        </div>

        <div class="col-xs-12">
            {{--<router-view name="basketDeliveryPickup"--}}
                         {{--:types='{!! $delivery !!}'>--}}
            {{--</router-view>--}}
            <form method="post" action="{{ route('input_personal_data') }}" id="go_form">
                <div class="row">
                    <div class="col-xs-12">
                        @foreach($delivery as $key => $value)
                            <div class="radio">
                                <label>
                                    <input
                                        onclick="get_dop(this.value)" @if($key == 0) checked @endif
                                        type="radio"
                                        name="delivery"
                                        value="{{ $value->id }}">
                                    {{ $value->name_full }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row top-20 dop_fld"></div>

            </form>
        </div>

        <form name="backform" action="{{ route('select_type') }}" method="post" id="back_form"></form>

        <div class="col-xs-12 top-20">
            <button onclick="$('#back_form').submit()" type="button" class="btn btn-default">Назад</button>
            <button onclick="go_form()" type="button" class="btn btn-primary">Далее</button>
        </div>
    </div>

@endsection
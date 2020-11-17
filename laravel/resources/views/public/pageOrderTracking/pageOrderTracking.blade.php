@extends('public.app')

@section('title', $seo->title)
@section('description', $seo->description)
@section('keywords', $seo->keywords)

@section('content')
    <div class="tracking">

        <div class="container">
            <div class="breadcrumbs top-15">
                <a href="/">Главная</a> <i class="fa fa-long-arrow-right"></i>
                {!! $seo->title !!}
            </div>
            <h1>{!! $seo->h1 !!}</h1>
        </div>

        <div class="container">
            @include('public._partials.componentError')
            <div class="row bottom-30">
                <div class="col-xs-12">
                    <div class="card">
                        <div class="card__content">

                            <div class="card__title arial font-w600 bottom-10">Введите трек-номер</div>
                            <form class="tracking__form" action="/track-orders" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <input
                                            name="parcel_number"
                                            class="form-control input-lg font-s18"
                                            placeholder="XXXXXXXXXX"
                                            value="{{ $parcel_number ? $parcel_number : '' }}">
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Найти</button>
                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>


                    @if($parcel)
                        <div class="card arial tracking__status">
                            <div class="card__content text-center">
                                <h2 class="arial bottom-15 font-w600 font-s16 text-center">
                                    Отправление №{{ $parcel['@DispatchNumber'] }} в г. {{ $parcel['Status']['@CityName'] }}
                                </h2>
                                <div class="font-w600 font-s24">
                                    Статус: {{ $parcel['Status']['@Description'] }} <br>
                                </div>
                                <div class="font-s15 text-muted top-5">
                                    Обновлено {{ $parcel['Status']['date'] }} {{ $parcel['Status']['time'] }}
                                </div>
                            </div>
                        </div>
                    @endif


                    @if($parcel)
                        <div class="card arial">
                            <div class="card__content bottom-0">
                                <h2 class="bottom-0 font-w600 font-s18">История перемещений</h2>
                            </div>
                            <table class="table tracking__history">
                                <thead>
                                    <tr>
                                        <th>Статус</th>
                                        <th>Расположение</th>
                                        <th>Дата</th>
                                    </tr>
                                </thead>
                                <tbody>
									@foreach(collect($parcel['Status']['State']) as $historyItem)					
                                      <tr>
                                            <td>{{ $historyItem['@Description'] }}</td>
                                            <td>{{ $historyItem['@CityName'] }}</td> 
                                            <td>{{ $historyItem['date'] }}</td>
                                        </tr>  
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
@stop
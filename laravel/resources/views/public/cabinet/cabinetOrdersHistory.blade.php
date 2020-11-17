@extends('public.app')

@section('title', $seo->title)

@section('content')
    <div class="profileOrdersHistory">

        <div class="container">
            <div class="breadcrumbs top-15">
                <a href="/">Главная</a> > Мои заказы
            </div>
            <h1>{{ $seo->h1 }}</h1>
        </div>

        <div class="container bottom-30">

            @include('public._partials.componentError')

            <div class="row">
                <div class="col-sm-8">
                    @foreach($user->orders->sortByDesc('date_order') as $order)
                        <div class="card arial">

                            <div class="card__content">
                                <div class="card__title bottom-5 font-w600">
                                    Заказ №{{ $order->number_formatted }} <span class="text-muted font-s14 font-w400">от {{ $order->date_order }}</span>
                                </div>
                                @if($order->status===3&&$order->status_pay===1)
                                    <span class="badge font-w600">Выполнен</span>
                                @elseif($order->status===1)
                                    <span class="label label-success font-w600">Новый</span>
                                @elseif($order->status===4)
                                    <span class="label label-danger font-w600">Отменен</span>
                                @endif

                                @if($order->status===3 && $order->status_pay===1 && (round((+$order->getPaymentSum()/100), 2)>0))
                                    <div class="badge">+ {{ round((+$order->getPaymentSum()/100), 2) }} бонусов</div>
                                @endif

                                @if($order->bonus > 0)
                                    <div class="badge">Использовано {{ $order->bonus }} бонусов</div>
                                @endif

                                <div class="top-15">

                                    <button
                                        class="btn btn-default"
                                        type="button"
                                        data-toggle="collapse"
                                        data-target="#order_{{ $order->id }}">
                                        Подробнее
                                    </button>

                                    @if($order->payment_type_id===2 && $order->payment_hash && $order->status_pay===0)
                                        <a
                                            href="/payment/{{ $order->payment_hash }}"
                                            class="btn btn-primary"
                                            type="button">
                                            Оплатить заказ
                                        </a>
                                    @endif

                                </div>

                            </div>

                            <div class="collapse" id="order_{{ $order->id }}">
                                <table class="table profileOrdersHistory__table">
                                    <tbody>
                                        @foreach($order->productLinks as $productLink)
                                            <tr>
                                                <td class="pr-15">
                                                    {{ $productLink->product->name }}
                                                    <div class="text-muted">{{ $productLink->amount }} x {{ $productLink->price }} руб.</div>
                                                </td>
                                                <td class="text-right pl-15">{{ $productLink->amount*$productLink->price }} руб.</td>
                                            </tr>
                                        @endforeach
                                        @if($order->bonus > 0)
                                            <tr>
                                                <td class="font-w600">Скидка по бонусной программе</td>
                                                <td class="text-right font-w600">{{ $order->bonus }} руб.</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="font-w600">Сумма заказа</td>
                                            <td class="text-right font-w600">{{ $order->getPaymentSum() }} руб.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    @endforeach
                </div>
                <div class="col-sm-4">
                    @include('public.cabinet.cabinetSidebar')
                </div>
            </div>



        </div>

    </div>
@stop
@extends('public.app')

@section('java_box')
    {{--<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">--}}
    {{--<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>--}}
    <script src="/js/jquery.bootstrap-touchspin.js"></script>
    <script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>

    @if($step === 3)
        <script src="/js/basketDeliveryWidget.js?2"></script>
        <script src="/js/lk-map.js?v=1"></script>
    @endif
@stop

@section('content')
    <div class="container top-20">

        <div class="row bottom-40 basket">

            <div class="col-xs-12">
                <h1>Корзина</h1>
            </div>

            <?php
                if ($step < 3) {
                    $content_size = count($result) ? 'col-md-9' : 'col-xs-12';
                } else {
                    $content_size = 'col-xs-12';
                }
            ?>

            <div class="basket_content {{ $content_size }}">
                @include('public.basket._basketBreadcrumbs', ['step'.($step ? $step : 1) => 1])

                @if($step === 1)
                    @include('public.basket._basketProductsList')
                @endif

                @yield('basket')

                @if($step > 1)
                    <div class="basket_box">
                        @include('public.basket._basketProductsList')
                    </div>
                @endif
            </div>

            @if($step < 3)
                <div class="col-md-3">
                    @if(count($result))
                        <fast-checkout></fast-checkout>
                    @endif
                </div>
            @endif

        </div>
    </div>
@endsection
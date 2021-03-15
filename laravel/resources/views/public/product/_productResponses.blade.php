@if (count($product->responses) > 0)
    @foreach($product->responses as $response)
        <div class="row top-15 bottom-30">
            <div class="col-xs-12">
                <div class="card">
                    <div class="response">
                        <div class="response__head">
                            <div class="response__author">{{$response->fio}}</div>
                            <div class="response__rating">
                                <div class="rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($response->rating >= $i)
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    @else
                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                    @endif
                                @endfor
                                </div>
                            </div>
                            
                            <div class="response__date">{{$response->created_at->format('d-m-Y')}}</div>
                        </div>
                        <div class="response__body">
                            {{$response->response}}
                        </div>
                        @if (mb_strlen($response->answer) > 4)
                            <div class="response__answer">
                                {{$response->answer}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="row top-15">
        <div class="col-xs-12">
            <p>
                Отзывов пока нет
            </p>
        </div>
    </div>
@endif
@include('public.product._productResponseForm')
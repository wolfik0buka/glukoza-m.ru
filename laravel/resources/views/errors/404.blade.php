@extends('public.app')

@section('title', 'Ошибка 404 - страницы не существует')

@section('content')
    <div class="bg-white">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 top-30">
                    <h1>Такой страницы у нас нет..</h1>
                    <p class="bottom-30 font-s16 text-muted">Мы не смогли найти страницу с таким адресом. Возможно в ссылке, по которой вы перешли, допущена ошибка.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row top-30 bottom-40">
            <div class="col-xs-12">
                <div class="h2">Выберите нужный раздел:</div>
            </div>
            <div class="col-xs-12 top-15">
                <?php
                    $parentCats = $cats
                        ->where('parent', 1)
                        ->sortByDesc(function($cat) use($cats) { return $cats->where('parent', $cat->id)->count(); })
                ?>
                <div class="page404__cats">
                    @foreach($parentCats as $cat)
                        <div class="col-xs-12 col-sm-4 col-md-3 card remove-padding">
                            <div class="card-block">
                                <a class="font-s19 color-text-default font-lh120" href="{{ link_cat($cat->id) }}" title="{{ $cat->name }}">
                                    {{ $cat->name }}
                                </a>
                                <div class="page404__childCats top-15">
                                    <?php
                                    $childCats = $cats->where('parent', $cat->id)
                                    ?>
                                    @if(count($childCats))
                                        @foreach($childCats as $child)
                                            <a class="page404__childCats__item" href="{{ link_cat($child->id) }}">{{ $child->name }}</a>
                                        @endforeach
                                    @else
                                        <a href="{{ link_cat($cat->id) }}">Смотреть все</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        yMetr
    </script>

@stop
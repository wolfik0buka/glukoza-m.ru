
<div class="cat_sidebar">
    <div class="row">
        <div class="container-fluid">
            @if($cat->parent > 1)
                @if($cat->parentCat)
                    <div class="title">{{ $cat->parentCat->name }}</div>
                    <div class="list-group">
                        @foreach($cats->where('parent', $cat->parent) as $child)
                            <a class="list-group-item @if($child->id == $cat->id) active @endif "
                               title="{{ $child->name }}"
                               href="{{ $cat_url.$child->slug }}">
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@if($filter['is_filter'])
<div class="cat_sidebar filter" style="margin-top: 20px;">
    <div class="row">
        <div class="container-fluid">
            <div class="filter__title">Фильтр</div>
            <form action="" method="GET" id="product_filter">
                <div>
                    <input 
                        type="checkbox" 
                        name="is_filter"
                        value="true"
                        class="filter__enable"
                        checked
                    >
                    @foreach ($filter['props'] as $slug => $prop)
                        <div class="filter-box">
                            <div class="filter-box__title do_expand_filter_box">
                                {{$prop["name"]}}
                                <i class="fa fa-chevron-up"></i>
                            </div>
                            <div class="filter_body">
                                @if ($slug !== 'price')
                                    {{ '', $counter = 0 }}
                                    {{ '', $max_option = 5 }}

                                     @foreach ($prop["items"] as $option_slug => $variant)
                                        {{'',$counter++ }}
                                        @if ($counter === $max_option +1 )
                                            <div class="filter-box__closed-options">
                                        @endif
                                        <label class="label-group">
                                                <input
                                                    type="checkbox"
                                                    name="f_{{$slug}}[{{$option_slug}}]"
                                                    value="true"
                                                    @if ($variant['checked'])
                                                        checked
                                                    @endif
                                                >
                                            {{$variant["value"]}}
                                        </label><!-- /input-group -->
                                    @endforeach
                                    @if ($counter > $max_option)
                                            </div>
                                            <div class="filter-box__show-all">
                                                Показать еще {{$counter - $max_option}}
                                            </div>
                                            @endif

                                @else
                                    <div id="price-slider"></div>
                                    <script>
                                        var slider_settings = {
                                            max: {{$prop['items']['max']}},
                                            min: {{$prop['items']['min']}},
                                            cur_min: {{$prop['items']['cur_min']}},
                                            cur_max: {{$prop['items']['cur_max']}},
                                        };
                                    </script>
                                    <script src="/js/filter.js"></script>
                                    <div class="prices_values">
                                        <div class="prices_value">
                                            <label class="price_value">
                                                От:
                                                <input type="text" name="price[from]" id="price_from">
                                            </label>
                                            <label class="price_value">
                                                До:
                                                <input type="text" name="price[to]" id="price_to">
                                            </label>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="buttons">
                    <input type="submit" value="Показать" class="btn btn-primary">
                    <input type="reset" id="form_reset" value="Сбросить" class="btn btn-light">
                </div>
            </form>
        </div>
    </div>
</div>
@endif
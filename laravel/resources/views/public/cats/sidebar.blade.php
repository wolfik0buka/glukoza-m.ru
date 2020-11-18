
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
<div class="cat_sidebar" style="margin-top: 20px;">
    <div class="row">
        <div class="container-fluid">
            <div class="title">Фильтр</div>
            <form action="" method="GET">
                <div class="filter">
                    <input 
                        type="checkbox" 
                        name="is_filter"
                        value="true"
                        checked
                    >
                    @foreach ($filter['props'] as $slug => $prop)
                        <div class="title">{{$prop["name"]}}</div>
                        <div class="filter_body">
                            @if ($slug !== 'price')
                                @foreach ($prop["items"] as $option_slug => $variant)
                                    <label class="input-group">
                                            <input 
                                                type="checkbox" 
                                                name="f_{{$slug}}[{{$option_slug}}]"
                                                value="true"
                                            >
                                        {{$variant["value"]}} 
                                        <span class="filter__total">({{$variant["count"]}})</span>
                                    </label><!-- /input-group -->
                                @endforeach
                            @else
                                <p>Здесь Цена</p>
                            @endif

                        </div>
                    @endforeach
                </div>
                <div class="buttons">
                    <input type="submit" value="Показать" class="btn btn-primary">
                    <input type="reset" value="Сбросить" class="btn btn-light">
                </div>
            </form>
        </div>
    </div>
</div>
@endif
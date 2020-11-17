<div class="breadcrumbs">
    @if($cat->parentCat->id >= 0)
        <a href="/">Главная</a> >
        @if($cat->parent === 1)
            {{ $cat->name }}
        @else
            @if($cat->parentCat && $cat->parentCat !== NULL)
                @if($cat->parentCat->parentCat 
                    && $cat->parentCat->parentCat !== NULL
                    && $cat->parentCat->parent !==1)
                    <a href="{{ $cat_url.$cat->parentCat->parentCat->slug }}">{{ $cat->parentCat->parentCat->name }}</a>
                    >
                @endif
                <a href="{{ $cat_url.$cat->parentCat->slug }}">{{ $cat->parentCat->name }}</a>
                >
                {{ $cat->name }}
            @endif
        @endif
    @endif
</div>

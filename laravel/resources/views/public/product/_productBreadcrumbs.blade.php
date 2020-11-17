<div class="breadcrumbs">
    <a href="/">Главная</a>
    <i class="fa fa-long-arrow-right"></i>
    @if($cat->parentCat)
        @if($cat->parentCat->id > 0)
            <a href="{{ $cat_url.$cat->parentCat->slug }}" title="{{ $cat->parentCat->name }}">
                {{ $cat->parentCat->name }}
            </a>
            <i class="fa fa-long-arrow-right"></i>
        @endif
    @endif
    <a title="{{ $cat->name }}" href="{{ $cat_url.$cat->slug }}">
        {{ $cat->name }}
    </a>
</div>
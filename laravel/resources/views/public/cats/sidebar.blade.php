
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
<div class="col-sm-6 col-md-4">
    <div class="item">
        <a href="{{ $cat_url.$cat->slug }}">
            <div class="">{{ $cat->name }}</div>
        </a>
        <p class="text-muted">Товаров: {{ count($cat->products) }}</p>
    </div>
</div>
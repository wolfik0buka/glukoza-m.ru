@if(mb_strlen($product->description) > 5 || mb_strlen($product->desc_full) > 5)
    @if(mb_strlen($product->description) > 20)
        <div class="page_product__description">
            <div class="_title">Кратко</div>
            <div class="_content">
                {!! $product->description !!}
            </div>
        </div>
    @endif

    @if(mb_strlen($product->desc_full) > 30)
        <div class="page_product__description">
            <div class="_title">Описание товара</div>
            <div class="_content">
                {!! $product->desc_full !!}
            </div>
        </div>
    @endif

@endif
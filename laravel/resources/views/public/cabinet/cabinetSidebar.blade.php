<div class="card">
    <div class="card__list">
        <div class="card__listItem">
            <a class="font-s16" href="/cabinet/profile">Профиль</a>
        </div>
        <div class="card__listItem">
            <a class="font-s16" href="/cabinet/profile/history">Мои заказы</a>
        </div>
    </div>
</div>


<div class="card top-15">
    <div class="card__content">
        <div class="card__title">Бонусная программа</div>
    </div>
    <div class="card__list">
        <div class="card__listItem font-s17">Баланс: {{ $bonuses->balance }}</div>
        @if($bonuses->all > 0)
            <div class="card__listItem text-muted font-s13">
                За все время начислено {{ $bonuses->all }} баллов, из которых использовано {{ $bonuses->used }} баллов.
            </div>
        @endif
    </div>
</div>
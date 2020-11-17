<template>
    <div class="panel basket__summary">

        <div class="font-s17 font-w500">
            Ваш заказ
        </div>

        <div class="basket__summaryItem">
            <div class="basket__summaryLabel">
                {{ $store.getters.order.products.length | numString('товар|товара|товаров') }} на сумму
            </div>
            <div class="basket__summaryValue">
                {{ $store.getters.orderProductsSum }} р.
            </div>
        </div>

        <div class="basket__summaryItem">
            <div class="basket__summaryLabel">
                Скидка по бонусной программе (<a target="_blank" href="https://glukoza-med.ru/index.php?page=stat&alias=bonus">Что это?</a>)
            </div>
            <div class="basket__summaryValue">
                -{{ $store.state.basketStore.order.bonus }} р.
            </div>
        </div>

        <div v-if="order.delivery && (order.delivery_price || order.delivery_price === 0)" class="basket__summaryItem">
            <div class="basket__summaryLabel">
                {{ order.dop_fld }}
            </div>
            <div class="basket__summaryValue">
                <span v-if="!order.delivery_is_free && order.delivery_price > 0">{{ order.delivery_price }} р.</span>
                <span v-if="order.delivery_is_free">Бесплатно</span>
            </div>
        </div>

        <div class="basket__summaryItem">
            <div class="basket__summaryLabel">
                К оплате
            </div>
            <div class="basket__summaryValue">
                {{ $store.getters.orderFullSum }} р.
            </div>
        </div>


        <div v-if="order.user_id">
            <div class="top-30 font-s17 font-w500">
                Ваши бонусы
            </div>
            <div class="basket__summaryItem">
                <div class="basket__summaryLabel">
                    Баланс
                </div>
                <div class="basket__summaryValue">
                    {{ $store.state.basketStore.bonusBalance }}
                </div>
            </div>

            <div class="text-muted font-s14 top-10">
                <a @click="showBonusForm = !showBonusForm">Использовать бонусы</a>
                <div v-if="showBonusForm" class="panel panel-body top-15">
                    <div class="basket__formHelp">Введите количество</div>
                    <div class="basket__formHelp bottom-5">(не более {{ $store.state.basketStore.bonusBalance }})</div>
                    <input class="basket__formField fullwidth" v-model.number="bonus_use">
                    <button @click="setBonusUse()"
                            class="btn btn-block btn-primary top-15">
                        Применить
                    </button>
                </div>
            </div>

        </div>


        <div v-if="$store.getters.validation.length > 0">
            <div class="top-30 font-s17 font-w500">
                Осталось заполнить:
            </div>
            <span class="text-muted font-s14 top-5 font-lh150">
                {{ $store.getters.validationResultString }}
            </span>
        </div>

    </div>
</template>

<script>
    module.exports = {
        name: 'basketSummary',
        data() {
            return {
                bonus_use: 0,
                showBonusForm: false
            }
        },
        computed: {
            order: {
                get() { return this.$store.getters.order },
                set() { this.$store.dispatch('updateOrder', this.order) }
            }
        },
        methods: {
            setBonusUse() {
                if (this.bonus_use > this.$store.state.basketStore.bonusBalance) {
                    this.bonus_use = this.$store.state.basketStore.bonusBalance
                }
                if (this.bonus_use > this.$store.getters.orderFullSum) {
                    this.bonus_use = this.$store.getters.orderFullSum
                }
                this.order.bonus = this.bonus_use
                this.showBonusForm = false
                this.$store.dispatch('updateOrder', this.order)
            }
        }
    }
</script>

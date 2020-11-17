<template>
    <div class="basket__layout">
        <div class="container">
            <div class="row">

                <div class="col-xs-12">
                    <h1>Корзина</h1>
                </div>

                <div v-if="($store.getters.order.products.length > 0) && (!$store.getters.orderDone) && !isFastCheckout">
                    <div class="col-xs-12 col-md-9">

                        <div class="basket__fastCheckoutLink">
                            Нет времени? <a @click=switchToFastCheckout() class="link">Оформите заказ в один клик</a>
                        </div>

                        <basketProducts></basketProducts>

                        <deliveryType></deliveryType>

                        <basketPayment></basketPayment>

                        <basketContacts></basketContacts>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <basketSummary></basketSummary>
                    </div>
                    <div class="col-xs-12">
                        <basketValidation></basketValidation>
                    </div>
                </div>

                <div v-if="($store.getters.order.products.length > 0) && (!$store.getters.orderDone) && isFastCheckout">
                    <div class="col-xs-12 col-md-9 bottom-50">
                        <div class="basket__fastCheckoutLink">
                            <a @click="isFastCheckout = false" class="link">
                                <i class="fa fa-undo right-5"></i>Вернуться к полному варианту
                            </a>
                        </div>
                        <fastCheckout></fastCheckout>
                    </div>
                </div>

                <div v-if="$store.getters.orderDone">
                    <basketDone></basketDone>
                </div>

                <div class="col-xs-12" v-if="($store.getters.order.products.length == 0) && (!$store.getters.orderDone)">
                    <basketEmpty></basketEmpty>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: 'basketLayout',
        props: ['user', 'bonus_balance'],
        data() {
            return {
                isFastCheckout: false
            }
        },
        mounted() {
            if (this.user) {
                this.$store.commit('setBonusBalance', this.bonus_balance)
            }
        },
        computed: {
            order() {
                return this.$store.getters.order
            }
        },
        methods: {
            switchToFastCheckout() {
                this.isFastCheckout = true
                _ym.goal('SwitchToFastCheckout')
            }
        },
        components: {
            basketProducts: require('./_products.vue').default,
            deliveryPickup: require('./_deliveryPickup.vue').default,
            deliveryType: require('./_deliveryType.vue').default,
            basketContacts: require('./_contacts.vue').default,
            basketValidation: require('./basketValidation.vue').default,
            basketEmpty: require('./basketEmpty.vue').default,
            basketSummary: require('./basketSummary.vue').default,
            basketDone: require('./basketDone.vue').default,
            fastCheckout: require('./fastCheckout.vue').default,
            basketPayment: require('./basketPayment.vue').default,
        }
    }
</script>

<style lang="less">
</style>
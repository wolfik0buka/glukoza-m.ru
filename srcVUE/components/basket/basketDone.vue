<template>
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-body">
                <div class="h1">Спасибо, ваш заказ оформлен!</div>
                <div class="font-s16">
                    <p>На номер {{ $store.getters.order.phone }} мы отправили СМС-подтверждение заказа.</p>
                    <p v-if="$store.getters.order.email">
                        На почту {{ $store.getters.order.email }} отправлена детальная информация по вашему заказу.
                    </p>
                    <p v-if="$store.getters.order.payment_type_id===2">
                        Ссылка на оплату будет отправлена вам после подтверждения заказа.
                    </p>
                </div>
                <div class="top-20">
                    <a href="/" class="btn btn-primary">Продолжить покупки</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: 'basketDone',
        data() {
            return {
                //
            }
        },
        mounted() {

        }
    }
</script>

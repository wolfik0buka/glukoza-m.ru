<template>
    <tbody v-if="order.delivery===4">
        <tr>
            <td colspan="3">
                <div class="card__title font-w500 font-roboto mt-15">Почта России</div>
            </td>
        </tr>
        <tr>
            <td>Трек-номер</td>
            <td>
                <input type="number" class="control" v-model.number="post_id">
                <div v-if="order.post_id">
                    <a
                        target="_blank"
                        class="btn btn-default mt-10"
                        :href="'https://www.pochta.ru/TRACKING#'+order.post_id">
                        Открыть отслеживание заказа
                    </a>
                    <button
                        v-if="order.post_id"
                        @click="$store.dispatch('sendSmsRussianPostTrackingCode')"
                        class="btn btn-default mt-10">
                        Отправить смс и email с номером покупателю
                    </button>
                    <div class="orderEditor__timeshot" v-if="order.delivery_track_sended_at">
                        Смс и email c кодом отслеживания отправлены {{ order.delivery_track_sended_at }}
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</template>

<script>
    module.exports = {
        name: "order-process-russian-post",
        props: [],
        data() {
            return {}
        },
        computed: {
            order() {
                return this.$store.state.order
            },
            post_id: {
                get() {
                    return this.order.post_id
                },
                set(val) {
                    this.order.post_id = val
                    this.$store.dispatch('updateOrder', this.order)
                }
            }
        },
        methods: {},
    }
</script>

<style lang="scss">

</style>
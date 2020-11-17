<template>


        <tbody v-if="order.payment_type_id===2">
            <tr>
                <td colspan="2">
                    <div class="card__title font-w500 font-roboto">Сбербанк</div>
                    <a target="_blank" href="https://securepayments.sberbank.ru/mportal3">Кабинет оператора</a>
                </td>
            </tr>
            <tr>
                <td>Статус</td>
                <td>
                    <div v-if="!order.sberbank_order_id">
                        Попыток оплаты не было
                    </div>

                    <div v-if="order.sberbank_order_id">

                        <div
                            v-if="!sberbankOrderStatus"
                            class="loader loader-small">
                        </div>

                        <div v-if="sberbankOrderStatus">

                            <div v-if="sberbankOrderStatus.actionCodeDescription">
                                {{ sberbankOrderStatus.actionCodeDescription }}
                            </div>

                            <div v-if="!sberbankOrderStatus.actionCodeDescription && sberbankOrderStatus.orderStatus==2">
                                Успешно оплачено
                                <span v-if="sberbankOrderStatus.paymentAmountInfo">{{ sberbankOrderStatus.paymentAmountInfo.depositedAmount / 100 }} руб.</span>
                            </div>

                            <div v-if="sberbankOrderStatus.paymentAmountInfo">
                                <div v-if="sberbankOrderStatus.paymentAmountInfo.paymentState">
                                    {{ sberbankOrderStatus.paymentAmountInfo.paymentState }}
                                    <span v-if="sberbankOrderStatus.paymentAmountInfo.paymentState === 'REFUNDED'">Оформлен возврат</span>
                                    <span v-if="sberbankOrderStatus.paymentAmountInfo.paymentState === 'APPROVED'">Резерв (нужно подтвердить)</span>
                                </div>
                            </div>
                        </div>

                        <div class="orderEditor__timeshot" v-if="order.payment_attempt_at">
                            Последнее взаимодействие {{ order.payment_attempt_at }}
                        </div>

                    </div>
                </td>
            </tr>
            <tr>
                <td>Оплата картой</td>
                <td>
                    <button
                      v-if="isSendPayLinkAvailable"
                      @click="$store.dispatch('sendPaymentLink')"
                      class="btn btn-default">
                        Отправить ссылку на оплату (письмо и смс)
                    </button>
                    <button
                      disabled
                      v-if="!isSendPayLinkAvailable"
                      class="btn btn-default">
                        Ссылка на оплату отправлена
                    </button>
                    <div class="orderEditor__timeshot" v-if="order.paylink_sended_at">
                        Ссылка отправлена {{ order.paylink_sended_at }}
                    </div>
                </td>
            </tr>
        </tbody>

</template>

<script>
    module.exports = {
        name: "orderSberbank",
        props: [],
        data() {
            return {}
        },
        computed: {
            order() {
                return this.$store.state.order
            },
            sberbankOrderStatus() {
                return this.$store.state.sberbankOrderStatus
            },
            timeFromSendingPayLink() {
                return moment().diff(moment(this.order.paylink_sended_at), 'minutes', false);
            },
            isSendPayLinkAvailable() {
                return !(this.order.is_success || this.order.is_done || this.order.status_pay);
            }
        },
        methods: {},
        mounted() {
            if (this.order.sberbank_order_id) {
                this.$store.dispatch('getSberbankOrderStatus')
            }
        }
    }
</script>

<style lang="scss">

</style>
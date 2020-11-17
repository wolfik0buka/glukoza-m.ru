<template>
    <div>

        <div class="top--20 bottom-20">Окончательную стоимость сообщит менеджер при подтверждении заказа</div>

        <div class="basket__formItem">
            <div class="basket__formLabel">
                Адрес доставки
            </div>
            <div class="basket__formContainer">
                <textarea rows="8"
                          class="basket__formField textfield fullwidth"
                          @keyup="$store.dispatch('updateOrder', order)"
                          v-model="order.adr">
                </textarea>
                <div class="basket__formHelp">
                    Улица, дом, корпус, квартира/офис
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    module.exports = {
        name: 'basketDeliverySpbDayToDay',
        computed: {
            order: {
                get() { return this.$store.getters.order },
                set() { this.$store.dispatch('updateOrder', this.order) }
            },
        }
    }
</script>

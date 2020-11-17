<template>
    <div>
        <div class="basket__formItem">
            <div class="basket__formLabel">
                Почтовый индекс
            </div>
            <input type="text"
                   id="basket_delivery_post_index"
                   class="basket__formField"
                   @change="$store.dispatch('updateOrder', order)"
                   v-model="order.post_index">
        </div>

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
                    Область, город, улица, дом, корпус, квартира/офис
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    module.exports = {
        name: 'basketDeliveryRussianPost',
        computed: {
            order() {
                return this.$store.getters.order
            },
        },
        created() {
            let self = this
            $script.ready('maskedinput', function() {
                $('#basket_delivery_post_index').mask( "999999", {
                    autoclear: false,
                    completed: function() {
                        self.order.post_index = this.val()
                        self.$store.dispatch('updateOrder', self.order)
                    }
                })
            })
        }
    }
</script>

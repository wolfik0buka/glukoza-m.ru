<template>
    <div>
        <div v-if="order.delivery" class="card basket__panel bottom-20">
            <div class="panel-body">
                <h2>Способ оплаты</h2>
                
                <div class="basket__formItem bottom-0">
                    <div class="basket__formLabel"></div>
                    
                    <div class="basket__formContainer">
                        <div class="radio" v-if="[2, 3].indexOf(order.delivery)>=0">
                            <label>
                                <input type="radio" v-model="payment_type_id" :value="4">
                                Банковской картой курьеру
                                <div class="input-helper text-muted font-s13"></div>
                            </label>
                        </div>
                        
                        <div class="radio" v-if="order.delivery!==4">
                            <label>
                                <input type="radio" v-model="payment_type_id" :value="1">
                                Наличными при получении
                                <div class="input-helper text-muted font-s13"></div>
                            </label>
                        </div>
                        
                        <div class="radio">
                            <label>
                                <input type="radio" v-model="payment_type_id" :value="2">
                                Картой на сайте
                                <div class="input-helper text-muted font-s13">Оплата будет доступна после подтверждения заказа по телефону</div>
                            </label>
                        </div>
                        
                        <div class="radio" v-if="[1,5,13,8].indexOf(order.delivery)>=0">
                            <label>
                                <input type="radio" v-model="payment_type_id" :value="3">
                                Картой при получении заказа
                                <div class="input-helper text-muted font-s13">Только при получении в магазинах «Глюкоза»</div>
                            </label>
                        </div>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: "basketPayment",
        props: [],
        data() {
            return {};
        },
        computed: {
            delivery_id() {
                return this.$store.getters.order.delivery;
            },
            order() {
                return this.$store.getters.order;
            },
            payment_type_id: {
                get() {
                    return this.order.payment_type_id;
                },
                set(val, oldVal) {
                    if (+val !== +oldVal) {
                        this.order.payment_type_id = val;
                        this.$store.dispatch("updateDelivery", this.order);
                    }
                },
            }
        },
        watch: {
            delivery_id(val, oldVal) {
                if (val !== oldVal) {
                    if ([4].indexOf(val) >= 0) {
                        this.payment_type_id = 2;
                    } else {
                        this.payment_type_id = 1;
                    }
                }
            }
        },
    };
</script>

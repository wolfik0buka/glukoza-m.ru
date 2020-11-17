<template>
    <div>
        
        <div class="alert alert-danger font-s16 font-lh140" v-if="showErrors">
            <span class="font-w500">Необходимо заполнить:</span>
            {{ $store.getters.validationResultString }}
        </div>
        
        <div class="bottom-50">
            
            <button
                v-if="!$store.state.basketStore.isLoading"
                @click="checkout()"
                class="basket__checkoutButton">Оформить заказ
            </button>
            
            <button
                v-if="$store.state.basketStore.isLoading"
                class="basket__checkoutButton disabled">Сохраняем..
            </button>
        
        </div>
    
    </div>
</template>

<script>
    module.exports = {
        name: "basketValidation",
        data() {
            return {
                showErrors: false
            }
        },
        computed: {
            order() {
                return this.$store.getters.order;
            }
        },
        methods: {
            checkout() {
                if (this.$store.getters.validation.length > 0) {
                    this.showErrors = true;
                } else {
                    this.$store.dispatch("doneOrder");
                }
            }
        }
    };
</script>

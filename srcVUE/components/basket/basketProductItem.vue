<template>
    <div class="list-group-item basket__product">
        
        <div class="basket__product__remove" @click="removeProduct()">
            <i class="fa fa-times"></i>
        </div>
        
        <div class="row top-10 bottom-10">
            <div class="col-sm-2 text-center bottom-10">
                <a
                    class="basket__product__pic"
                    target="_blank"
                    :href="product.link">
                    <img
                        :src="'https://cdn.glukoza-med.ru/products/'+product.id+'/xs.jpg'"
                        :alt="product.name">
                </a>
            </div>
            <div class="col-sm-10">
                <div class="font-w500 right-50">
                    {{ product.name }}
                </div>
                <div class="text-muted top-5">
                    <div class="font-s15 top-5">
                        Цена: {{ sum }} руб.
                        <span v-if="amount > 1">({{ product.price }} руб. X {{ amount }})</span>
                    </div>
                    <div class="basket__product__amount">
                        <i @click="decrementAmount()" class="fa fa-minus"></i>
                        <input v-model.number="amount">
                        <i @click="incrementAmount()" class="fa fa-plus"></i>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
</template>

<script>
    module.exports = {
        name: "basketProductItem",
        props: ["product"],
        computed: {
            amount: {
                get() {
                    return this.product.amount;
                },
                set(val) {
                    if (val < 1) {
                        this.product.amount = 1;
                    } else {
                        this.product.amount = val;
                    }
                    this.$store.commit("updateProduct", this.product);

                    this.reCalcDelivery();
                }
            },
            sum() {
                return this.amount * this.product.price;
            }
        },
        methods: {
            incrementAmount() {
                this.product.amount += 1;
                this.$store.commit("updateProduct", this.product);
                this.$store.dispatch("updateOrder");
                this.reCalcDelivery();
            },
            decrementAmount() {

                if (this.product.amount > 1) {
                    this.product.amount -= 1;
                    this.$store.commit("updateProduct", this.product);
                    this.$store.dispatch("updateOrder");
                    this.reCalcDelivery();
                }
            },
            removeProduct() {
                this.$store.dispatch("removeProductFromOrder", this.product);
                this.reCalcDelivery();
            },
            reCalcDelivery() {
                if (this.$store.getters.order.city_id === 137 && this.$store.getters.order.delivery !== 2) {
                    this.$store.dispatch("updateDelivery");
                }
            }
        },
    };
</script>

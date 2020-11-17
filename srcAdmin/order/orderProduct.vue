<template>
    <tr>
        <td>{{ index+1 }}</td>
        <td>{{ product.id }}</td>
        <td>
            <div class="orderProduct__name">
                <div
                    class="orderProduct__deliveryFixer text-center"
                    v-if="+productLink.product.usluga===1"
                    data-toggle="tooltip"
                    data-placement="top"
                    :data-original-title="[$store.state.order.delivery_price_fixed===0 ? 'Зафиксировать стоимость доставки' : 'Разблокировать стоимость доставки']">
                    <div
                        @click="$store.dispatch('setFixedOrderDeliveryPrice', 1)"
                        v-if="$store.state.order.delivery_price_fixed===0">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24">
                            <path fill="#777" d="M12 17c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6-9h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6h1.9c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm0 12H6V10h12v10z"/>
                        </svg>
            
                    </div>
                    <div
                        @click="$store.dispatch('setFixedOrderDeliveryPrice', 0)"
                        v-if="$store.state.order.delivery_price_fixed===1">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24">
                            <path fill="#333" d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>
                    </div>
                </div>
                {{ product.name }}
            </div>
        </td>
        <td>
            <numberSpin
              v-if="+productLink.product.usluga<1"
              @input=updateProductAmount
              :value=+productLink.amount>
            </numberSpin>
        </td>
        <td class="td_price" style="text-align: right;">
            <priceEditor
                @input="updatePrice"
                :price="productLink.price"
                :bordered="true">
            </priceEditor>
        </td>
        <td style="text-align:right;">
            {{ +productLink.price * +productLink.amount | currency }}
        </td>
        <td>
            <i @click="remove()" class="red fa fa-trash-o"></i>
        </td>
    </tr>
</template>

<script>
    module.exports = {
        name: "order-product",
        props: [
            'product',
            'productLink',
            'index'
        ],
        data() {
            return {}
        },
        computed: {
            priceFormatted() {
                return formatCurrency(this.productLink.price)
            }
        },
        methods: {
            updateProductAmount(value) {
                this.productLink.amount = parseInt(value)
                this.$emit('update', this.productLink)
            },
            updatePrice(value) {
                this.productLink.price = parseFloat(value)
                this.$emit('update', this.productLink)
            },
            remove() {
                this.$emit('remove', this.productLink)
            }
        },
        beforeMount() {
        }
    }
</script>

<style lang="scss">
    .orderProduct{
        &__name{
            min-height: 30px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;
        }
        &__deliveryFixer{
            width: 24px;
            height: 30px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding-top: 3px;
            margin-right: 5px;
        }
    }
</style>
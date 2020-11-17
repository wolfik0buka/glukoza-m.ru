<template>
    <div>

        <modal
            @close="isShowFormAddProduct=false"
            v-if="isShowFormAddProduct"
            title="Добавить товар">
            <orderAddProduct @add="addProduct"></orderAddProduct>
        </modal>

        <div class="card font-roboto">

            <div class="card__content">
                <div v-if="!order" class="loader"></div>
                <div v-if="order">
                    <div class="card__title">
                        Товары в заказе
                        <a
                          @click="isShowFormAddProduct=!isShowFormAddProduct"
                          class="font-s15 ml-15">
                            + добавить
                        </a>
                    </div>
                </div>
            </div>

            <table v-if="order" class="table orderProducts__table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Арт.</th>
                        <th>Товар</th>
                        <th>Кол-во, шт</th>
                        <th width="140px" class="text-right text-nowrap">Цена, руб</th>
                        <th class="text-right text-nowrap">Сумма, руб</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <orderProduct
                        v-for="(productLink, index) in productLinks"
                        :index="index"
                        :productLink="productLink"
                        :product="productLink.product"
                        @remove="$store.dispatch('removeOrderProduct', productLink.id)"
                        @update="updateOrderProduct"
                        :key="productLink.id">
                    </orderProduct>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4"></th>
                        <th class="text-right text-nowrap">Товаров на сумму:</th>
                        <th class="text-right">{{ $store.getters.orderProductsSum | currency }}</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="4"></th>
                        <th class="text-right">Баллы:</th>
                        <th class="text-right">{{ order.bonus }}</th>
                        <th>
                            <i
                              v-if="order.bonus>0"
                              @click="resetOrderPoints()"
                              class="red fa fa-trash-o">
                            </i>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="4"></th>
                        <th class="text-right">К оплате:</th>
                        <th class="text-right">{{ $store.getters.orderFullSum | currency }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

        </div>

    </div>
</template>

<script>
    module.exports = {
        name: "orderProducts",
        props: [],
        data() {
            return {
                isShowFormAddProduct: false
            }
        },
        computed: {
            order() {
                return this.$store.state.order
            },
            productLinks() {
                return _.sortBy(this.order.product_links, link => {
                    return link.product.usluga
                })
            }
        },
        methods: {
            updateOrderProduct(orderProduct) {
                this.order.product_links[this.getOrderProductIndex(orderProduct)] = orderProduct
                this.$store.commit('setOrder', this.order)
                axios.post('/admin/orders/products/update', orderProduct).then(response => {
                    this.throttledUpdateDeliveryProduct()
                })
            },
            updateDeliveryProduct() {
                this.$store.dispatch('updateDeliveryProduct')
            },
            throttledUpdateDeliveryProduct: _.throttle(function(){this.updateDeliveryProduct()}, 1000),
            getOrderProductIndex(orderProduct) {
                return _.findIndex(this.order.product_links, orderProduct)
            },
            addProduct(payload) {
                this.$store.dispatch('addProductToOrder', {
                    product_id: payload.product.id,
                    price: payload.product.real_price
                })
            },
            resetOrderPoints() {
                this.order.bonus = 0
                this.$store.dispatch('updateOrder', this.order)
            }
        },
        beforeMount() {
        },
        components: {
            orderProduct: require('./orderProduct.vue').default,
            orderAddProduct: require('./orderAddProduct.vue').default,
        }
    }
</script>

<style lang="scss">
    .orderProducts{
        &__table{
            margin-bottom: 0;
            margin-top: 10px;
            thead, tfoot{
                tr{
                    th{
                        font-weight: 500;
                        font-size: 14px;
                        padding: 6px 10px;
                        border-bottom-width: 1px;
                        &:first-of-type{
                            padding-left: 15px;
                        }
                    }
                }
            }
            tbody{
                tr{
                    td{
                        padding: 6px 10px;
                        font-size: 14px;
                        &:first-of-type{
                            padding-left: 15px;
                        }
                    }
                }
            }
        }
    }
</style>
<template>
    <tbody v-if="order" :class="{active: expanded}">
        <tr :class="{ done: order.is_done === 1, preorder: order.is_preorder === 1 }">
            <td>
                <div>
                    <a
                        data-toggle="tooltip"
                        data-placement="top"
                        data-original-title="Номер заказа на Глюкозе"
                        :href="'/admin_new/index.php?page=order&id='+order.id">
                        {{ order.glukoza_number_formatted }}
                    </a>
                </div>
                <div
                    class="onmediOrderId"
                    v-if="order.onmedi_order_id"
                    data-toggle="tooltip"
                    data-placement="top"
                    data-original-title="Номер заказа на Онмеди">
                    {{ order.onmedi_order_id }}
                </div>
            </td>
            <td>{{ order.date_order|momentFormat("HH:mm") }}</td>
            <td>
                <a
                    data-toggle="tooltip"
                    data-placement="top"
                    data-original-title="Клиент"
                    :href="'/admin_new/index.php?page=order&id='+order.id">
                    {{ order.fio ? order.fio : "Не указано" }}
                </a>
                <div
                    class="font-s12  text-muted"
                    v-if="order.is_preorder === 1">
                    Запрос на товар не в наличии
                </div>
            </td>
            <td>
                <div
                    data-toggle="tooltip"
                    data-placement="top"
                    data-original-title="Способ получения"
                    v-if="$store.state.deliveries">
                    {{ $store.state.deliveries[order.delivery] ? $store.state.deliveries[order.delivery].name : "" }}
                </div>
            </td>
            <!--<td>-->
            <!--<div-->
            <!--data-toggle="tooltip"-->
            <!--data-placement="top"-->
            <!--data-original-title="Ответственный">-->
            <!--{{ getManager(order.manager_id).name }}-->
            <!--</div>-->
            <!--</td>-->
            <td class="remove-padding-l remove-padding-r text-center">
                
                <i @click="expandDetails()" class="fa fa-file-text-o"></i>
            </td>
            <td>
                <div
                    v-if="order.status === 4"
                    data-toggle="tooltip"
                    data-placement="top"
                    data-original-title="Заказ отменен">
                    <i class="black icon fa fa-thumbs-o-up"></i>
                </div>
                <div
                    v-if="order.status !== 4"
                    data-toggle="tooltip"
                    data-placement="top"
                    :data-original-title="order.status_agreed === 1 ? 'Согласовано' : 'Не согласовано'">
                    <i class="red icon fa fa-thumbs-o-up" :class="[order.status_agreed==1 ? 'green' : 'red' ]"></i>
                </div>
            </td>
            <td>
                <div
                    v-if="order.status === 1"
                    data-toggle="tooltip"
                    data-placement="top"
                    data-original-title="Не отправлено"><i class="red icon fa fa-truck"></i>
                </div>
                <div
                    v-if="order.status === 2"
                    data-toggle="tooltip"
                    data-placement="top"
                    data-original-title="Передан курьеру"><i class="yellow icon fa fa-truck"></i>
                </div>
                <div
                    v-if="order.status === 3"
                    data-toggle="tooltip"
                    data-placement="top"
                    data-original-title="Отправлено"><i class="green icon fa fa-truck"></i>
                </div>
                <div
                    v-if="order.status === 4"
                    data-toggle="tooltip"
                    data-placement="top"
                    data-original-title="Заказ отменен"><i class="black icon fa fa-truck"></i>
                </div>
            </td>
            <td>
                <div v-if="(order.delivery===4) || (+order.payment_type_id===2)">
                    <div
                        v-if="order.status_pay===1"
                        data-toggle="tooltip"
                        data-placement="top"
                        data-original-title="Оплачен">
                        <i class="green icon fa fa-rub"></i>
                    </div>
                    <div
                        v-if="order.status_pay===0"
                        data-toggle="tooltip"
                        data-placement="top"
                        data-original-title="Не оплачен">
                        <i class="red icon fa fa-rub"></i>
                    </div>
                </div>
            </td>
            <td class="text-right">
                <div v-html="order.order_sum"></div>
            </td>
            <td class="font-s13 compact">
                <div v-html="order.comment_my"></div>
            </td>
        </tr>
        <tr v-if="expanded">
            <td colspan="10" class="orderDetails">
                <div class="card">
                    
                    <div v-if="isLoading" class="loader"></div>
                    
                    <table class="table table-condensed mb-0" v-if="!isLoading && orderDetailed">
                        <tr v-for="productLink in orderDetailed.product_links" :key="productLink.id">
                            <td>{{ productLink.product.articul }}</td>
                            <td>{{ productLink.product.name }}</td>
                            <td><span v-if="productLink.product.usluga !== 1">{{ productLink.amount }} шт.</span></td>
                            <td><span v-if="productLink.price > 0">{{ productLink.price }} руб.</span></td>
                        </tr>
                    </table>
                    
                </div>
            </td>
        </tr>
    </tbody>

</template>

<script>
    module.exports = {
        name: "ordersOrder",
        props: [
            "order",
        ],
        data() {
            return {
                expanded: false,
                orderDetailed: false,
                isLoading: false,
            };
        },
        computed: {},
        methods: {
            getManager(manager_id) {
                if (!manager_id) {
                    return this.$store.state.doers[1];
                }
                return _.find(this.$store.state.doers, {id: manager_id});
            },
            expandDetails() {
                if (!this.orderDetailed) {
                    this.getOrderDetailed();
                }
                this.expanded = !this.expanded;
            },
            getOrderDetailed() {
                this.isLoading = true;
                this.$store.dispatch("getOrder", this.order.id).then(order => {
                    this.orderDetailed = order;
                    this.isLoading = false;
                });
            }
        },
        beforeMount() {
        }
    };
</script>

<style lang="scss" scoped>
    tbody {
        position: relative;
        &.active {
            border: 2px solid #333;
        }
        tr {
            position: relative;
        }
    }
    
    .iconDetails {
        cursor: pointer;
    }
    
    .orderDetails {
        padding: 0 !important;
    }
</style>